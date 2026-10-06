<!-- header.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include $_SERVER['DOCUMENT_ROOT'].'/PAGINADEOFICIAIS/config/config.php';

// Verifique se o usuÃ¡rio estÃ¡ logado
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
//$is_portaria = isset($_SESSION['user_id']) && !$is_admin;



// Recupere os postos do banco de dados
try {
    $stmt = $pdo->prepare('SELECT id, descricao, imagem FROM postos');
    $stmt->execute();
    $postos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao recuperar postos: " . $e->getMessage();
}

// Consulta SQL para buscar os dados dos oficiais com seus postos e imagens
$stmt = $pdo->query('
    SELECT o.id, o.nome, p.descricao, p.imagem, o.status, o.localizacao, o.posto_id 
    FROM oficiais o
    JOIN postos p ON o.posto_id = p.id
    ORDER BY o.localizacao
');
$oficiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
  $stmt = $pdo->query('SELECT id, username FROM users');
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erro ao recuperar usuÃ¡rios: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>PAPEM - Quadro de Oficiais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="/PAGINADEOFICIAIS/js/bootstrap.min.js"></script>
    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   
    <script src="js/scripts.js"></script>
    <script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['login_error'])): ?>
            $('#loginModal').modal('show');
        <?php endif; ?>
    });
</script>



</head>

<body>
<header id="topo">
    <div class="navbar-left">
        <img src="imagens/brasao2.png" id="papem" alt="Conecta PAPEM" class="navbar-logo">
        <div class="navbar-text">
            <h6 class="top-site-name">Marinha do Brasil</h6>
            <h1>Pagadoria de Pessoal da Marinha</h1>
            <p>"ORDEM, PRONTIDÃO E REGULARIDADE"</p>
        </div>
    </div>




 <div class="navbar-right">
  <!-- Bot�o de Logout -->

 <?php if ($is_logged_in): ?>
  <span class="navbar-text text-info">
    Olá, <?php echo htmlspecialchars($_SESSION['username']); ?>!
</span>

 <!-- A��es Administrativas -->
<?php if ($is_admin): ?>
            <div class="dropdown">
                <button class="glass-button dropdown-toggle" type="button" id="adminDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ações Administrativas
                </button>
                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addUserModal">Adicionar Usuário</a>
                    <hr>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">Redefinir Senha</a>
                    <hr>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#resetUserPasswordModal">Redefinir Senha de Usuário</a>
                </div>
            </div>
        <?php endif; ?>
   <button class="glass-button logout-button" onclick="window.location.href='views/logout.php'">Logout</button>
        <?php else: ?>

        <button class="btn btn-primary  login-button" data-toggle="modal" data-target="#loginModal">Login</button>
      
        <?php endif; ?>
  
    </div>


<!-- R�dio Marinha -->
<div class="radio-section">
<img class="radiomarinha" src="imagens/radio_marinha.png" alt="R�dio Marinha" >
<audio controls>
        <source src="https://stm0.inovativa.net/listen/radiomarinha/radio.mp3" type="audio/mpeg">
    
    </audio>
      
       </div>
    
        

</header>





<div class="header-bottom-area-bg">



      </div>















<!-- Modal de Login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <?php if (isset($_SESSION['login_error'])): ?>
          <div class="alert alert-danger">
            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
          </div>
        <?php endif; ?>
        <form action="views/process_login.php" method="POST">
          <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Adicionar UsuÃ¡rio -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Adicionar Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="views/process_add_user.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div id="usernameError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="is_admin">Administrador:</label>
                        <input type="checkbox" id="is_admin" name="is_admin">
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de sucesso ao adicionar usuário -->
<div class="modal fade" id="userAddSuccessModal" tabindex="-1" role="dialog" aria-labelledby="userAddSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userAddSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Usuário adicionado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Erro ao adicionar usuário -->
<div id="userAddErrorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userAddErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userAddErrorModalLabel">Erro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="userAddErrorMessage">Usuário ja existe!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de Erro -->
<div id="passwordErrorModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Erro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p id="passwordErrorMessage">A nova senha e a confirmação não coincidem.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Usuário adicionado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Redefinir Senha -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Redefinir Senha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="changePasswordForm" action="views/process_change_password.php" method="POST">
          <div class="form-group">
            <label for="current_password">Senha Atual:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
          </div>
          <div class="form-group">
            <label for="new_password">Nova Senha:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirmar Nova Senha:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
          <button type="submit" class="btn btn-primary">Redefinir Senha</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Sucesso para RedefiniÃ§Ã£o de Senha -->
<div class="modal fade" id="passwordChangeSuccessModal" tabindex="-1" role="dialog" aria-labelledby="passwordChangeSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Senha redefinida com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Erro -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo isset($_SESSION['error']) ? $_SESSION['error'] : ''; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Redefinir Senha de UsuÃ¡rio -->
<div class="modal fade" id="resetUserPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetUserPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetUserPasswordModalLabel">Redefinir Senha de Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resetUserPasswordForm" action="views/process_reset_user_password.php" method="POST">
                    <div class="form-group">
                        <label for="reset_username">Usuário:</label>
                        <select class="form-control" id="reset_username" name="username" required>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reset_new_password">Nova Senha:</label>
                        <input type="password" class="form-control" id="reset_new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="reset_confirm_password">Confirmar Nova Senha:</label>
                        <input type="password" class="form-control" id="reset_confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Redefinir Senha</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- Modal de Sucesso para Redefinição de Senha do Usuário -->
<div class="modal fade" id="resetPasswordSuccessModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Senha do usuário redefinida com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>





</body>
</html>