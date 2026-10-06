<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const DEFAULT_ADMIN = 'admin';

    public function session(Request $request): JsonResponse
    {
        $username = $request->session()->get('username');
        $isAuthenticated = (bool) $request->session()->get('user_id');

        return response()->json([
            'authenticated' => $isAuthenticated,
            'username'      => $username,
            'isAdmin'       => $username === self::DEFAULT_ADMIN,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = strtolower(trim($credentials['username']));
        $password = $credentials['password'];

        $user = User::where('username', $username)->first();

        $bootstrap = $this->bootstrapPassword();

        if (!$user) {
            if ($bootstrap && $username === self::DEFAULT_ADMIN && $password === $bootstrap) {
                $user = User::create([
                    'username' => self::DEFAULT_ADMIN,
                    'password' => Hash::make($bootstrap),
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Usuário ou senha incorretos'], 401);
            }
        }

        if (!$this->verifyPassword($password, $user->password)) {
            if ($bootstrap && $this->isLegacyHash($user->password) && $username === self::DEFAULT_ADMIN && $password === $bootstrap) {
                $user->password = Hash::make($bootstrap);
                $user->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Usuário ou senha incorretos'], 401);
            }
        }

        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('username', $user->username);

        return response()->json(['success' => true, 'message' => 'Autenticado com sucesso']);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function check(Request $request): JsonResponse
    {
        return response()->json([
            'authenticated' => (bool) $request->session()->get('user_id'),
        ]);
    }

    public function listUsers(Request $request): JsonResponse
    {
        if (!$this->isAdminSession($request)) {
            return response()->json(['success' => false, 'message' => 'Acesso restrito ao usuário administrador'], 403);
        }

        $users = User::select('id', 'username')->get();
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function createUser(Request $request): JsonResponse
    {
        if (!$this->isAdminSession($request)) {
            return response()->json(['success' => false, 'message' => 'Acesso restrito ao usuário administrador'], 403);
        }

        // Normalize before uniqueness check to avoid case-collision DB exceptions
        $request->merge(['username' => strtolower(trim($request->input('username', '')))]);

        $data = $request->validate([
            'username' => 'required|string|min:3|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['success' => true, 'user' => ['id' => $user->id, 'username' => $user->username]], 201);
    }

    public function updateUser(Request $request, int $id): JsonResponse
    {
        if (!$this->isAdminSession($request)) {
            return response()->json(['success' => false, 'message' => 'Apenas o administrador pode editar usuários'], 403);
        }

        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => 'sometimes|nullable|string|min:1|max:255',
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if ($username === null && $password === null) {
            return response()->json(['success' => false, 'message' => 'Envie ao menos usuário ou senha para atualizar'], 400);
        }

        if ($username !== null) {
            $normalized = strtolower(trim($username));
            if (!$normalized) {
                return response()->json(['success' => false, 'message' => 'O nome de usuário não pode conter apenas espaços em branco'], 400);
            }
            if ($user->username === self::DEFAULT_ADMIN && $normalized !== self::DEFAULT_ADMIN) {
                return response()->json(['success' => false, 'message' => 'O usuário admin não pode ter o nome alterado'], 400);
            }
            $conflict = User::where('username', $normalized)->where('id', '!=', $id)->first();
            if ($conflict) {
                return response()->json(['success' => false, 'message' => 'Já existe um usuário com este nome'], 409);
            }
            $user->username = $normalized;
        }

        if ($password !== null) {
            $trimmedPassword = trim($password);
            if (strlen($trimmedPassword) < 6) {
                return response()->json(['success' => false, 'message' => 'A nova senha deve ter pelo menos 6 caracteres'], 400);
            }
            $user->password = Hash::make($trimmedPassword);
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Usuário atualizado com sucesso', 'user' => ['id' => $user->id, 'username' => $user->username]]);
    }

    private function bootstrapPassword(): ?string
    {
        $p = env('ADMIN_BOOTSTRAP_PASSWORD', '');
        return (is_string($p) && strlen($p) >= 8) ? $p : null;
    }

    private function verifyPassword(string $input, string $stored): bool
    {
        if (str_starts_with($stored, '$2')) {
            return Hash::check($input, $stored);
        }

        if (!str_contains($stored, ':')) {
            return hash_equals($stored, $input);
        }

        // Legacy scrypt format (salt:hex) — cannot verify without native scrypt.
        // Return false; the caller handles the admin-default upgrade path.
        return false;
    }

    private function isLegacyHash(string $hash): bool
    {
        return !str_starts_with($hash, '$2');
    }

    private function isAdminSession(Request $request): bool
    {
        return $request->session()->get('username') === self::DEFAULT_ADMIN
            && (bool) $request->session()->get('user_id');
    }
}
