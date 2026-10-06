/*
 * Sistema de Visualização da Marinha do Brasil
 * Painel Administrativo
 * 
 * Autor: 2SG Bruna Rocha
 * Marinha do Brasil
 */

import React, { useState, useEffect, useCallback } from "react";
import { Link } from "wouter";
import { 
  Tabs, 
  TabsContent, 
  TabsList, 
  TabsTrigger 
} from "@/components/ui/tabs";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { 
  Card, 
  CardContent, 
  CardDescription, 
  CardFooter, 
  CardHeader, 
  CardTitle 
} from "@/components/ui/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Label } from "@/components/ui/label";
import { useDisplay, PDFDocument } from "@/context/DisplayContext";
import { useToast } from "@/hooks/use-toast";
import { resolveBackendUrl } from "@/utils/backend";
import { 
  Sheet, 
  SheetContent, 
  SheetDescription, 
  SheetHeader, 
  SheetTitle, 
  SheetTrigger 
} from "@/components/ui/sheet";
import { HoverCard, HoverCardContent, HoverCardTrigger } from "@/components/ui/hover-card";
import { WeatherAlerts } from "@/components/WeatherAlerts";
import { MilitaryEditor } from "@/components/MilitaryEditor";
import { TagBadges } from "@/components/TagBadges";
import { Lock, LogOut, Loader2 } from "lucide-react";
// Dados dos oficiais baseados no quadro acda Marinha

// ✅ FIXED: Import the correct data structures
import { 
  OFFICERS_LIST, 
  MASTERS_LIST, 
  type OfficerData, 
  type MasterData,
  RANK_DISPLAY_MAP,
  RANK_FULL_NAME_MAP
} from "@/data/officersData";


const getErrorMessage = (error: unknown): string => {
  if (error instanceof Error) {
    return error.message;
  }
  if (typeof error === 'string') {
    return error;
  }
  if (error && typeof error === 'object' && 'message' in error) {
    return String((error as { message: unknown }).message);
  }
  return 'Erro desconhecido';
};

// Adicionar no topo do arquivo, após os imports
const handleAsyncError = (error: unknown, defaultMessage: string = "Erro desconhecido"): string => {
  if (error instanceof Error) {
    return error.message;
  }
  if (typeof error === 'string') {
    return error;
  }
  return defaultMessage;
};



// ✅ FIXED: Create compatible data arrays for backward compatibility
const OFFICERS_DATA = OFFICERS_LIST.map(officer => ({
  name: officer.name,
  rank: officer.rank,
  specialty: officer.specialty || null, // ✅ CORREÇÃO: Converter undefined para null
  fullRankName: officer.fullRankName
}));

const MASTERS_DATA = MASTERS_LIST.map(master => ({
  name: master.name,
  rank: master.rank,
  specialty: master.specialty || null, 
  fullRankName: master.fullRankName
}));

// ✅ FIXED: Define proper types for the component
interface MilitaryPersonnel {
  id: number;
  name: string;
  type: 'officer' | 'master';
  rank: string;
  specialty?: string | null;
  fullRankName?: string;
  active?: boolean;
}

interface AdminUser {
  id: number;
  username: string;
}



const Admin: React.FC = () => {
 const {
    plasaDocuments,
    escalaDocuments,
    cardapioDocuments,
    addDocument,
    deleteDocument,
    escalaAlternateInterval,
    setEscalaAlternateInterval,
    cardapioAlternateInterval,
    setCardapioAlternateInterval,
    scrollSpeed,
    setScrollSpeed,
    autoRestartDelay,
    setAutoRestartDelay,
    isLoading
  } = useDisplay();
  
  const { toast } = useToast();


  const [isAuthChecking, setIsAuthChecking] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [sessionUsername, setSessionUsername] = useState<string | null>(null);
  const [sessionIsAdmin, setSessionIsAdmin] = useState(false);
  const [loginForm, setLoginForm] = useState({ username: "admin", password: "" });
  const [loginError, setLoginError] = useState<string | null>(null);
  const [isLoggingIn, setIsLoggingIn] = useState(false);
  const isAdminUser = sessionIsAdmin;

  const [userForm, setUserForm] = useState({ username: '', password: '', confirmPassword: '' });
  const [userCreationError, setUserCreationError] = useState<string | null>(null);
  const [userCreationSuccess, setUserCreationSuccess] = useState<string | null>(null);
  const [isCreatingUser, setIsCreatingUser] = useState(false);
  const [userList, setUserList] = useState<AdminUser[]>([]);
  const [userEdits, setUserEdits] = useState<Record<number, { username: string; password: string; confirmPassword: string }>>({});
  const [isLoadingUsers, setIsLoadingUsers] = useState(false);
  const [userListError, setUserListError] = useState<string | null>(null);
  const [updatingUserIds, setUpdatingUserIds] = useState<Record<number, boolean>>({});


  const applySessionData = useCallback((data: unknown) => {
    const sessionPayload = (data ?? {}) as {
      authenticated?: unknown;
      username?: unknown;
      isAdmin?: unknown;
    };

    setIsAuthenticated(Boolean(sessionPayload.authenticated));
    setSessionUsername(
      typeof sessionPayload.username === 'string' ? sessionPayload.username : null
    );
    setSessionIsAdmin(Boolean(sessionPayload.isAdmin));
  }, []);

  const verifySession = useCallback(async () => {
    setIsAuthChecking(true);

    try {
      const response = await fetch(resolveBackendUrl('/api/admin/session'), {
        credentials: 'include',
      });

      const data = await response.json().catch(() => null);

      if (response.ok) {
        applySessionData(data);

        if (data?.authenticated) {
          setLoginError(null);
        }
      } else {
        setIsAuthenticated(false);
        setSessionUsername(null);
        setSessionIsAdmin(false);
      }
    } catch (error) {
      console.error('Erro ao verificar sessão:', error);
      setIsAuthenticated(false);
      setSessionUsername(null);
      setSessionIsAdmin(false);
    } finally {
      setIsAuthChecking(false);
    }
  }, [applySessionData]);

  useEffect(() => {
    void verifySession();
  }, [verifySession]);


  const handleLoginSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setLoginError(null);
    setIsLoggingIn(true);

    try {
      const response = await fetch(resolveBackendUrl('/api/admin/login'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          username: loginForm.username.trim(),
          password: loginForm.password,
        }),
      });

      const data = await response.json().catch(() => null);

      if (!response.ok || !data?.success) {
        const message = data?.message || 'Usuário ou senha incorretos';
        throw new Error(message);
      }

      setIsAuthenticated(true);
      await verifySession();
      setLoginForm((current) => ({ ...current, password: '' }));
      setLoginError(null);

      toast({
        title: 'Login realizado',
        description: 'Bem-vindo ao painel administrativo.',
      });
    } catch (error) {
      const message = handleAsyncError(error, 'Não foi possível realizar o login.');
      setLoginError(message);
    } finally {
      setIsLoggingIn(false);
    }
  };

  const handleLogout = async () => {
    try {
      await fetch(resolveBackendUrl('/api/admin/logout'), {
        method: 'POST',
        credentials: 'include',
      });
    } catch (error) {
      console.error('Erro ao encerrar sessão:', error);
    } finally {
      setIsAuthenticated(false);
      setSessionUsername(null);
      setSessionIsAdmin(false);
      setLoginForm({ username: 'admin', password: '' });
      setLoginError(null);

      toast({
        title: 'Sessão encerrada',
        description: 'Você saiu do painel administrativo.',
      });
    }
  };

  const handleUserCreation = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setUserCreationError(null);
    setUserCreationSuccess(null);

    const username = userForm.username.trim();
    const password = userForm.password.trim();

    if (!username || !password) {
      setUserCreationError('Informe usuário e senha para criar a conta.');
      return;
    }

    if (password !== userForm.confirmPassword.trim()) {
      setUserCreationError('As senhas não coincidem.');
      return;
    }

    setIsCreatingUser(true);

    try {
      const response = await fetch(resolveBackendUrl('/api/admin/users'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ username, password }),
      });

      const data = await response.json().catch(() => null);

      if (!response.ok || !data?.success) {
        const message = data?.message || 'Não foi possível criar o usuário.';
        throw new Error(message);
      }

      setUserCreationSuccess('Usuário criado com sucesso.');
      setUserForm({ username: '', password: '', confirmPassword: '' });

      void fetchUsers();

      toast({
        title: 'Usuário cadastrado',
        description: `O usuário ${username} foi criado e já pode fazer login.`,
      });
    } catch (error) {
      const message = handleAsyncError(error, 'Erro ao criar usuário.');
      setUserCreationError(message);
    } finally {
      setIsCreatingUser(false);
    }
  };

  const fetchUsers = useCallback(async () => {
    if (!isAuthenticated || !isAdminUser) {
      setUserList([]);
      setUserEdits({});
      return;
    }

    setIsLoadingUsers(true);
    setUserListError(null);

    try {
      const response = await fetch(resolveBackendUrl('/api/admin/users'), {
        credentials: 'include',
      });

      const data = await response.json().catch(() => null);

      if (!response.ok || !data?.success) {
        const message = data?.message || 'Não foi possível carregar os usuários.';
        throw new Error(message);
      }

      const loadedUsers: AdminUser[] = Array.isArray(data.users) ? data.users : [];
      setUserList(loadedUsers);
      setUserEdits(
        loadedUsers.reduce<Record<number, { username: string; password: string; confirmPassword: string }>>((acc, user) => {
          acc[user.id] = { username: user.username, password: '', confirmPassword: '' };
          return acc;
        }, {})
      );
    } catch (error) {
      const message = handleAsyncError(error, 'Erro ao carregar a lista de usuários.');
      setUserListError(message);
    } finally {
      setIsLoadingUsers(false);
    }
  }, [isAdminUser, isAuthenticated]);

  useEffect(() => {
    void fetchUsers();
  }, [fetchUsers]);

  const updateUserEdits = (id: number, changes: Partial<{ username: string; password: string; confirmPassword: string }>) => {
    setUserEdits((current) => ({
      ...current,
      [id]: { ...current[id], ...changes },
    }));
  };

  const handleUserUpdate = async (userId: number) => {
    const currentEdit = userEdits[userId];

    if (!currentEdit) return;

    const username = currentEdit.username.trim();
    const password = currentEdit.password.trim();
    const confirmPassword = currentEdit.confirmPassword.trim();

    if (!username) {
      toast({ title: 'Usuário inválido', description: 'O nome de usuário não pode ficar vazio.', variant: 'destructive' });
      return;
    }

    if (password && password !== confirmPassword) {
      toast({ title: 'Senhas diferentes', description: 'A confirmação de senha não confere.', variant: 'destructive' });
      return;
    }

    setUpdatingUserIds((current) => ({ ...current, [userId]: true }));

    try {
      const response = await fetch(resolveBackendUrl(`/api/admin/users/${userId}`), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          username,
          password: password || undefined,
        }),
      });

      const data = await response.json().catch(() => null);

      if (!response.ok || !data?.success) {
        const message = data?.message || 'Não foi possível atualizar o usuário.';
        throw new Error(message);
      }

      toast({ title: 'Usuário atualizado', description: `Dados de ${username} salvos com sucesso.` });

      setUserEdits((current) => ({
        ...current,
        [userId]: { username, password: '', confirmPassword: '' },
      }));

      setUserList((current) => current.map((user) => (user.id === userId ? { ...user, username } : user)));
    } catch (error) {
      const message = handleAsyncError(error, 'Erro ao atualizar usuário.');
      toast({ title: 'Falha ao atualizar', description: message, variant: 'destructive' });
    } finally {
      setUpdatingUserIds((current) => {
        const next = { ...current };
        delete next[userId];
        return next;
      });
    }
  };



  // Estados para upload de documentos
  const [docUnit, setDocUnit] = useState<"EAGM" | "1DN" | undefined>(undefined);
  const [selectedDocType, setSelectedDocType] = useState<
    "plasa" | "escala" | "cardapio" | null
  >(null);
  const [docTitle, setDocTitle] = useState("");
  const [docUrl, setDocUrl] = useState("");
  const [docCategory, setDocCategory] = useState<"oficial" | "praca" | undefined>(undefined);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [isUploading, setIsUploading] = useState(false);
  const [uploadProgress, setUploadProgress] = useState(0);
  
  // Estados para oficiais de serviço
  const [dutyOfficers, setDutyOfficers] = useState({
    officerName: "",
    officerRank: undefined as string | undefined,
    masterName: "",
    masterRank: undefined as string | undefined,
    validFrom: undefined as string | undefined,
    updatedAt: undefined as string | undefined
  });
  const [isLoadingOfficers, setIsLoadingOfficers] = useState(false);
  
  // 🔥 NOVO: Estados para dados dinâmicos da combobox
  const [availableOfficers, setAvailableOfficers] = useState<MilitaryPersonnel[]>([]);
  const [availableMasters, setAvailableMasters] = useState<MilitaryPersonnel[]>([]);
  const [isLoadingComboboxData, setIsLoadingComboboxData] = useState(false);

  // Estados para edição de militares - agora carregados da API

  const [dbOfficers, setDbOfficers] = useState<MilitaryPersonnel[]>([]);
  const [dbMasters, setDbMasters] = useState<MilitaryPersonnel[]>([]);
  const [newOfficerName, setNewOfficerName] = useState("");
  const [newMasterName, setNewMasterName] = useState("");
  const [editingOfficer, setEditingOfficer] = useState<{id: number, name: string} | null>(null);
  const [editingMaster, setEditingMaster] = useState<{id: number, name: string} | null>(null);
  const [loadingMilitary, setLoadingMilitary] = useState(false);
  
  // Estados para o editor militar
  const [militaryEditorOpen, setMilitaryEditorOpen] = useState(false);
  const [editingMilitary, setEditingMilitary] = useState<MilitaryPersonnel | null>(null);
  const [militaryPersonnel, setMilitaryPersonnel] = useState<MilitaryPersonnel[]>([]);

  const formatRankWithSpecialty = (
    rank?: string | null,
    specialty?: string | null
  ) => {
    const rankText = rank ? rank.toUpperCase() : "";
    const specialtyText = specialty ? ` (${specialty.toUpperCase()})` : "";
    return `${rankText}${specialtyText}`.trim();
  };

  // Padrão que aceita APENAS patentes militares válidas (não qualquer palavra)
  // Patentes: 1T, 2T, CT, CC, CF, CMG, CA (oficiais) | 1SG, 2SG, 3SG, CB, SO, MN, SD (praças)
  const DUTY_NAME_PATTERN = /^(1T|2T|CT|CC|CF|CMG|CA|1SG|2SG|3SG|CB|SO|MN|SD)\s*(?:\([A-Z0-9-]+\))?\s+(.+)$/;

  const normalizeDutyNameValue = (value?: string | null): string => {
    if (!value) {
      return "";
    }

    const trimmed = value.trim();
    if (!trimmed) {
      return "";
    }

    const upper = trimmed.toUpperCase();
    const match = upper.match(DUTY_NAME_PATTERN);
    if (match) {
      // Match[1] é a patente, match[2] é o nome completo
      return match[2].trim();
    }

    // Se não há match com o padrão de patente, retorna o valor completo
    // Isso preserva nomes compostos como "LARISSA CASTRO"
    return upper;
  };

  const normalizeDutyRankValue = (value?: string | null): string | undefined => {
    if (!value) {
      return undefined;
    }

    const trimmed = value.trim();
    if (!trimmed) {
      return undefined;
    }

    return trimmed.toUpperCase();
  };

  const formatMilitaryLabel = (
    military: Pick<MilitaryPersonnel, "rank" | "specialty" | "name"> | null | undefined
  ) => {
    if (!military) {
      return "";
    }

    const baseRank = formatRankWithSpecialty(military.rank, military.specialty);
    const rankPart = baseRank
      ? `${baseRank}${military.specialty ? "" : " (S/E)"}`.trim()
      : "";
    const namePart = military.name ? military.name.toUpperCase() : "";
    return [rankPart, namePart].filter(Boolean).join(" ");
  };

  const resolveFullRankName = (
    military: Pick<MilitaryPersonnel, "rank" | "fullRankName"> | null | undefined
  ) => {
    if (!military) {
      return "";
    }

    if (military.fullRankName) {
      return military.fullRankName;
    }

    if (!military.rank) {
      return "";
    }

    return RANK_FULL_NAME_MAP[military.rank as keyof typeof RANK_FULL_NAME_MAP] ?? "";
  };

  // Função para converter nomes salvos no banco para formato de exibição correto
  const convertToDisplayFormat = (fullName: string, type: 'officer' | 'master'): { displayName: string; rank: string; specialty: string | null; name: string } => {
    if (!fullName) return { displayName: '', rank: '', specialty: null, name: '' };
    
    console.log('🔄 Convertendo para display:', fullName, 'tipo:', type);
    
    // Se já está no formato correto (ex: "1T (IM) ELIEZER"), extrair dados
    const formatoCorreto = /^([A-Z0-9]+)\s*(?:\(([A-Z0-9-]+)\))?\s+(.+)$/;
    const match = fullName.match(formatoCorreto);
    
    if (match) {
      const [, rank, specialty, name] = match;
      console.log('✅ Formato já correto:', { rank, specialty, name });
      return {
        displayName: fullName,
        rank: rank,
        specialty: specialty || null,
        name: name
      };
    }
    
    // Se é apenas um nome (ex: "ALEXANDRIA"), buscar na base de dados
    const personnelData = type === 'officer' ? OFFICERS_DATA : MASTERS_DATA;
    const militaryData = personnelData.find(p => p.name === fullName || p.name.includes(fullName));
    
  if (militaryData) {
    const displayName = `${militaryData.rank.toUpperCase()}${militaryData.specialty ? ` (${militaryData.specialty.toUpperCase()})` : ''} ${militaryData.name}`;
    console.log('✅ Convertido da base:', displayName);
    return {
      displayName,
      rank: militaryData.rank.toUpperCase(),
      specialty: militaryData.specialty || null, // ✅ CORREÇÃO: Converter undefined para null
      name: militaryData.name
    };
  }
    // Verificar se contém graduação por extenso e converter
    const rankMapping = {
      'Primeiro-Tenente': '1T',
      '1º Tenente': '1T',
      'Segundo-Tenente': '2T', 
      '2º Tenente': '2T',
      'Capitão-Tenente': 'CT',
      'Capitão de Corveta': 'CC',
      'Capitão de Fragata': 'CF',
      'Primeiro-Sargento': '1SG',
      '1º Sargento': '1SG',
      'Segundo-Sargento': '2SG',
      '2º Sargento': '2SG',
      'Terceiro-Sargento': '3SG',
      '3º Sargento': '3SG'
    };
    
    for (const [fullRank, abbrev] of Object.entries(rankMapping)) {
      if (fullName.includes(fullRank)) {
        const nameOnly = fullName.replace(fullRank, '').trim();
        const militaryData = personnelData.find(p => p.name === nameOnly);
        
        if (militaryData) {
          const displayName = `${abbrev}${militaryData.specialty ? ` (${militaryData.specialty.toUpperCase()})` : ''} ${militaryData.name}`;
          console.log('✅ Convertido por extenso:', displayName);
          return {
            displayName,
            rank: abbrev,
            specialty: militaryData.specialty,
            name: militaryData.name
          };
        }
      }
    }
    
    console.log('⚠️ Não encontrado na base, usando fallback');
    return { displayName: fullName, rank: '', specialty: null, name: fullName };
  };

  // Carregar dados dos militares da API
  const loadMilitaryPersonnel = async () => {
    try {
      setLoadingMilitary(true);
      const response = await fetchBackend('/api/military-personnel');
      
        if (response.ok) {
          const result = await response.json();
          const personnel = (result.data || []) as MilitaryPersonnel[];

          setDbOfficers(personnel.filter(p => p.type === 'officer'));
          setDbMasters(personnel.filter(p => p.type === 'master'));

          // Atualizar lista completa para o editor
          setMilitaryPersonnel(personnel);
      }
    } catch (error) {
      console.error('Erro ao carregar militares:', error);
    } finally {
      setLoadingMilitary(false);
    }
  };

  // Funções para o editor militar
  const handleEditMilitary = (military: any) => {
    setEditingMilitary(military);
    setMilitaryEditorOpen(true);
  };
  
  const handleSaveMilitary = async (updatedMilitary: Partial<any>) => {
    try {
      const isEditing = editingMilitary && editingMilitary.id;
      const url = isEditing ? 
        getBackendUrl(`/api/military-personnel/${editingMilitary.id}`) : 
        getBackendUrl('/api/military-personnel');
      
      const response = await authorizedFetch(url, {
        method: isEditing ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedMilitary)
      });
      
      if (response.ok) {
        await loadMilitaryPersonnel();
        await loadDutyOfficers(); // Refresh duty officers data
        
        toast({
          title: isEditing ? "Militar atualizado" : "Militar criado",
          description: isEditing ? 
            "As informações foram atualizadas com sucesso" : 
            "Novo militar adicionado ao sistema"
        });
      }
    } catch (error) {
      console.error('Erro ao salvar militar:', error);
      toast({
        title: "Erro",
        description: "Não foi possível salvar as informações",
        variant: "destructive"
      });
    }
  };
  
  const handleDeleteMilitary = async (militaryId: number) => {
    if (!confirm('Tem certeza que deseja remover este militar?')) return;
    
    try {
      const response = await fetchBackend(`/api/military-personnel/${militaryId}`, {
        method: 'DELETE'
      });
      
      if (response.ok) {
        await loadMilitaryPersonnel();
        toast({
          title: "Militar removido",
          description: "O militar foi removido do sistema",
          variant: "destructive"
        });
      }
    } catch (error) {
      console.error('Erro ao remover militar:', error);
      toast({
        title: "Erro", 
        description: "Não foi possível remover o militar",
        variant: "destructive"
      });
    }
  };

  // Carregar dados na inicialização
  useEffect(() => {
    if (!isAuthenticated) {
      return;
    }

    void loadMilitaryPersonnel();
  }, [isAuthenticated]);

  // Funções para gerenciar oficiais com persistência
  const addOfficer = async () => {
    if (newOfficerName.trim()) {
      try {
        const response = await fetchBackend('/api/military-personnel', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            name: newOfficerName.trim(),
            type: 'officer',
            rank: '1t',
            fullRankName: `1º Tenente ${newOfficerName.trim()}`,
            active: true
          })
        });

        if (response.ok) {
          await loadMilitaryPersonnel();
          setNewOfficerName("");
          toast({
            title: "Oficial adicionado",
            description: `${newOfficerName.trim()} foi salvo no sistema`,
          });
        } else {
          throw new Error('Erro ao salvar');
        }
      } catch (error) {
        toast({
          title: "Erro",
          description: "Não foi possível salvar o oficial",
          variant: "destructive"
        });
      }
    }
  };

  const removeOfficer = async (id: number, name: string) => {
    try {
      const response = await fetchBackend(`/api/military-personnel/${id}`, {
        method: 'DELETE'
      });

      if (response.ok) {
        await loadMilitaryPersonnel();
        toast({
          title: "Oficial removido",
          description: `${name} foi removido do sistema`,
          variant: "destructive",
        });
      }
    } catch (error) {
      toast({
        title: "Erro",
        description: "Não foi possível remover o oficial",
        variant: "destructive"
      });
    }
  };

  const startEditOfficer = (id: number, name: string) => {
    setEditingOfficer({ id, name });
  };

const saveEditOfficer = async () => {
  if (!editingOfficer) {
    console.error('Erro: editingOfficer is null');
    return;
  }

  if (!editingOfficer.name.trim()) {
    toast({
      title: "Erro",
      description: "Nome não pode estar vazio",
      variant: "destructive"
    });
    return;
  }

  try {
    const response = await fetchBackend(`/api/military-personnel/${editingOfficer.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: editingOfficer.name.trim(),
        fullRankName: `1º Tenente ${editingOfficer.name.trim()}`
      })
    });

    if (response.ok) {
      await loadMilitaryPersonnel();
      setEditingOfficer(null);
      toast({
        title: "Oficial atualizado",
        description: "Nome do oficial foi atualizado com sucesso",
      });
    } else {
      throw new Error('Erro ao atualizar');
    }
  } catch (error) {
    toast({
      title: "Erro",
      description: "Não foi possível atualizar o oficial",
      variant: "destructive"
    });
  }
};

  const cancelEditOfficer = () => {
    setEditingOfficer(null);
  };

  // Funções para editar contramesres
  const startEditMaster = (id: number, name: string) => {
    setEditingMaster({ id, name });
  };

 const saveEditMaster = async () => {
  if (!editingMaster) {
    console.error('Erro: editingMaster is null');
    return;
  }

  if (!editingMaster.name.trim()) {
    toast({
      title: "Erro",
      description: "Nome não pode estar vazio",
      variant: "destructive"
    });
    return;
  }

  try {
    const response = await fetchBackend(`/api/military-personnel/${editingMaster.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: editingMaster.name.trim(),
        type: 'master',
        rank: '1sg',
        fullRankName: `1º Sargento ${editingMaster.name.trim()}`
      })
    });

    if (response.ok) {
      await loadMilitaryPersonnel();
      setEditingMaster(null);
      toast({
        title: "Contramestre atualizado",
        description: `${editingMaster.name.trim()} foi atualizado no sistema`,
      });
    } else {
      throw new Error('Erro ao atualizar');
    }
  } catch (error) {
    toast({
      title: "Erro",
      description: "Não foi possível atualizar o contramestre",
      variant: "destructive",
    });
  }
};

  const cancelEditMaster = () => {
    setEditingMaster(null);
  };

  // Funções para gerenciar Contramesres com persistência
  const addMaster = async () => {
    if (newMasterName.trim()) {
      try {
        const response = await fetchBackend('/api/military-personnel', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            name: newMasterName.trim(),
            type: 'master',
            rank: '1sg',
            fullRankName: `1º Sargento ${newMasterName.trim()}`,
            active: true
          })
        });

        if (response.ok) {
          await loadMilitaryPersonnel();
          setNewMasterName("");
          toast({
            title: "Contramestre adicionado",
            description: `${newMasterName.trim()} foi salvo no sistema`,
          });
        }
      } catch (error) {
        toast({
          title: "Erro",
          description: "Não foi possível salvar o contramestre",
          variant: "destructive"
        });
      }
    }
  };

  const removeMaster = async (id: number, name: string) => {
    try {
      const response = await fetchBackend(`/api/military-personnel/${id}`, {
        method: 'DELETE'
      });

      if (response.ok) {
        await loadMilitaryPersonnel();
        toast({
          title: "Contramestre removido",
          description: `${name} foi removido do sistema`,
          variant: "destructive",
        });
      }
    } catch (error) {
      toast({
        title: "Erro",
        description: "Não foi possível remover o contramestre",
        variant: "destructive"
      });
    }
  };

  // Estados para status do sistema
  const [serverStatus, setServerStatus] = useState<{
    connected: boolean;
    lastResponse: number | null;
    lastCheck: Date | null;
    documents: number;
  }>({
    connected: false,
    lastResponse: null,
    lastCheck: null,
    documents: 0
  });
  
  // Função para obter URL completa do backend - DETECTAR AMBIENTE
 const getBackendUrl = (path: string): string => resolveBackendUrl(path);

  const authorizedFetch = (url: string, init?: RequestInit) => {
    const options: RequestInit = { ...(init ?? {}) };
    options.credentials = 'include';
    return fetch(url, options);
  };

  const fetchBackend = (path: string, init?: RequestInit) => {
    return authorizedFetch(getBackendUrl(path), init);
  };
  
  // Função para verificar status do servidor
  const checkServerStatus = async () => {
    try {
      const response = await fetchBackend('/api/notices');
      setServerStatus(prev => ({
        ...prev,
        connected: response.ok,
        lastResponse: response.status,
        lastCheck: new Date(),
        documents: plasaDocuments.length + escalaDocuments.length + cardapioDocuments.length
      }));
      console.log("📢 Resposta do servidor:", response.status, response.ok ? 'OK' : 'ERROR');
    } catch (error) {
      setServerStatus(prev => ({
        ...prev,
        connected: false,
        lastResponse: null,
        lastCheck: new Date(),
        documents: plasaDocuments.length + escalaDocuments.length + cardapioDocuments.length
      }));
      console.error("❌ Erro de conexão com servidor:", error);
    }
  };

  // Função auxiliar para determinar categoria
  const determineCategory = (filename: string): "oficial" | "praca" | undefined => {
    const lowerFilename = filename.toLowerCase();
    if (lowerFilename.includes('oficial')) return 'oficial';
    if (lowerFilename.includes('praca')) return 'praca';
    return undefined;
  };
// Função para obter ícone e cor do tipo de documento
  const getDocumentTypeInfo = (type: string) => {
    switch (type) {
      case "plasa":
        return {
          icon: "📄",
          name: "PLASA",
          description: "Plano de Serviço",
          color: "bg-blue-50 border-blue-200 text-blue-800"
        };
      case "escala":
        return {
          icon: "📋",
          name: "Escala",
          description: "Escala de Serviço",
          color: "bg-green-50 border-green-200 text-green-800"
        };
      case "cardapio":
        return {
          icon: "🍽️",
          name: "Cardápio",
          description: "Cardápio Semanal",
          color: "bg-orange-50 border-orange-200 text-orange-800"
        };
      default:
        return {
          icon: "📄",
          name: "Documento",
          description: "Documento",
          color: "bg-gray-50 border-gray-200 text-gray-800"
        };
    }
  };

  // 🔥 NOVO: Carregar dados dinâmicos para combobox
  const loadComboboxData = async () => {
    console.log('🔄 Iniciando carregamento de dados combobox...');
    setIsLoadingComboboxData(true);
    try {
      const url = getBackendUrl('/api/military-personnel');
      console.log('🌐 URL da API:', url);
      
      const response = await authorizedFetch(url);
      const data = await response.json();
      
      console.log('📡 Resposta completa da API:', data);
      
      if (data.success && data.data) {
        const officers = data.data.filter((p: any) => p.type === 'officer');
        const masters = data.data.filter((p: any) => p.type === 'master');
        
        console.log('📊 Total personnel:', data.data.length);
        console.log('👮 Officers filtrados:', officers.length);
        console.log('⚓ Masters filtrados:', masters.length);
        
        setAvailableOfficers(officers);
        setAvailableMasters(masters);
        console.log('📋 Dados combobox carregados:', { officers: officers.length, masters: masters.length });
        console.log('🔍 DEBUG - Oficiais carregados:', officers.slice(0, 3));
        console.log('🔍 DEBUG - Contramestres carregados:', masters.slice(0, 3));
      } else {
        console.error('❌ Dados inválidos da API:', data);
      }
    } catch (error) {
      console.error('❌ Erro ao carregar dados combobox:', error);
    } finally {
      setIsLoadingComboboxData(false);
    }
  };

  // Carregar oficiais de serviço
  const loadDutyOfficers = async () => {
    setIsLoadingOfficers(true);
    try {
      const response = await fetchBackend('/api/duty-officers');
      const data = await response.json();

      if (data.success && data.officers) {
        console.log('👮 Dados carregados do servidor:', data.officers);
        const validFromDate = data.officers.validFrom
          ? new Date(data.officers.validFrom)
          : undefined;
        const updatedAtDate = data.officers.updatedAt
          ? new Date(data.officers.updatedAt)
          : undefined;

        setDutyOfficers({
          officerName: normalizeDutyNameValue(data.officers.officerName),
          officerRank: normalizeDutyRankValue(data.officers.officerRank),
          masterName: normalizeDutyNameValue(data.officers.masterName),
          masterRank: normalizeDutyRankValue(data.officers.masterRank),
          validFrom: validFromDate && !Number.isNaN(validFromDate.getTime())
            ? validFromDate.toISOString()
            : undefined,
          updatedAt: updatedAtDate && !Number.isNaN(updatedAtDate.getTime())
            ? updatedAtDate.toISOString()
            : undefined
        });
      }
    } catch (error) {
      console.error('Erro ao carregar oficiais:', error);
    } finally {
      setIsLoadingOfficers(false);
    }
  };

  // Salvar serviço
  const saveDutyOfficers = async () => {
    if (!dutyOfficers.officerName && !dutyOfficers.masterName) {
      toast({
        title: "Erro",
        description: "Selecione pelo menos um oficial ou contramestre.",
        variant: "destructive"
      });
      return;
    }

    setIsLoadingOfficers(true);
    try {
      console.log('💾 Salvando oficiais:', dutyOfficers);

      const sanitizedOfficerName = normalizeDutyNameValue(dutyOfficers.officerName);
      const sanitizedMasterName = normalizeDutyNameValue(dutyOfficers.masterName);
      const normalizedOfficerRank = normalizeDutyRankValue(dutyOfficers.officerRank);
      const normalizedMasterRank = normalizeDutyRankValue(dutyOfficers.masterRank);

      const validFromDate = (() => {
        if (dutyOfficers.validFrom) {
          const parsed = new Date(dutyOfficers.validFrom);
          if (!Number.isNaN(parsed.getTime())) {
            return parsed.toISOString();
          }
        }
        return new Date().toISOString();
      })();

      const officersData = {
        officerName: sanitizedOfficerName,
        masterName: sanitizedMasterName,
        officerRank: normalizedOfficerRank,
        masterRank: normalizedMasterRank,
        validFrom: validFromDate
      };

      console.log('📝 Dados sendo enviados:', officersData);

      const response = await fetchBackend('/api/duty-officers', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(officersData),
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();
      console.log('✅ Resposta do servidor:', data);
      
      if (data.success) {
        toast({
          title: "Sucesso",
          description: "Oficiais de serviço atualizados com sucesso!",
        });
        
        // Recarregar os dados para sincronizar
        await loadDutyOfficers();
      } else {
        throw new Error(data.error || 'Erro ao salvar oficiais');
      }
    } catch (error) {
      console.error('❌ Erro ao salvar oficiais:', error);
      const errorMessage = getErrorMessage(error);

      toast({
        title: "Erro",
        description: `Falha ao salvar oficiais: ${errorMessage}`,
        variant: "destructive"
      });
    } finally {
      setIsLoadingOfficers(false);
    }
  };

  // Funcionalidade de edição de nomes dos oficiais implementada abaixo

  useEffect(() => {
    if (!isAuthenticated) {
      return;
    }

    console.log("🔧 Admin carregado, avisos serão carregados do servidor");
    void loadDutyOfficers();
    void loadComboboxData(); // 🔥 CRÍTICO: Carregar dados dinâmicos para combobox
  }, [isAuthenticated]);

  // 🔥 NOVO: Recarregar dados combobox quando militar for editado
  useEffect(() => {
    if (!isAuthenticated) {
      return;
    }

    if (militaryPersonnel.length > 0) {
      void loadComboboxData();
    }
  }, [isAuthenticated, militaryPersonnel]);
  

  
  // Funções de upload de documentos
  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      
      console.log("📁 Arquivo selecionado:", {
        name: file.name,
        size: file.size,
        type: file.type,
        lastModified: new Date(file.lastModified)
      });

      if (file.size > 50 * 1024 * 1024) {
        toast({
          title: "Arquivo muito grande",
          description: "O arquivo deve ter no máximo 50MB",
          variant: "destructive"
        });
        return;
      }

      const isValidType = file.type === 'application/pdf' || 
                         file.type.startsWith('image/') ||
                         file.name.toLowerCase().endsWith('.pdf') ||
                         file.name.toLowerCase().endsWith('.jpg') ||
                         file.name.toLowerCase().endsWith('.jpeg') ||
                         file.name.toLowerCase().endsWith('.png') ||
                         file.name.toLowerCase().endsWith('.gif') ||
                         file.name.toLowerCase().endsWith('.webp');

      if (!isValidType) {
        toast({
          title: "Tipo de arquivo não suportado",
          description: "Use PDFs ou imagens (JPG, PNG, GIF, WEBP)",
          variant: "destructive"
        });
        return;
      }

      console.log("✅ Arquivo aceito:", file.type);
      setSelectedFile(file);
      
      if (!docTitle) {
        let fileName = file.name.replace(/\.[^/.]+$/, "");
        setDocTitle(fileName);
      }
      
      if (docUrl.startsWith('blob:')) {
        URL.revokeObjectURL(docUrl);
      }
      
      const fileUrl = URL.createObjectURL(file);
      setDocUrl(fileUrl);
      
      console.log("📄 Arquivo preparado para upload:", {
        name: file.name,
        size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
        type: file.type,
        previewUrl: fileUrl
      });
    }
  };

const handleDocumentSubmit = async (e: React.FormEvent) => {
  e.preventDefault();
  
  if (!docTitle) {
    toast({
      title: "Erro",
      description: "Título é obrigatório.",
      variant: "destructive"
    });
    return;
  }

  if (!selectedFile && !docUrl) {
    toast({
      title: "Erro",
      description: "Selecione um arquivo ou forneça uma URL.",
      variant: "destructive"
    });
    return;
  }

  if (!selectedDocType) {
    toast({
      title: "Erro",
      description: "Selecione o tipo de documento antes de enviar.",
      variant: "destructive"
    });
    return;
  }

  const docType = selectedDocType as "plasa" | "escala" | "cardapio";

  // Substituir a validação existente por:
  if (docType === "escala" && !docCategory) {
    toast({
      title: "Erro",
      description: "Selecione a categoria da escala (Oficial ou Praça).",
      variant: "destructive"
    });
    return;
  }

  if (docType === "cardapio" && !docUnit) {
    toast({
      title: "Erro",
      description: "Selecione a unidade do cardápio (EAGM ou 1DN).",
      variant: "destructive"
    });
    return;
  }

  // ✅ DECLARE typeInfo UMA VEZ SÓ aqui no início
  const typeInfo = getDocumentTypeInfo(docType);

  try {
    setIsUploading(true);
    setUploadProgress(0);
    
    if (selectedFile) {
      console.log("📤 Iniciando upload do arquivo:", selectedFile.name);
      
      // ✅ USE a variável typeInfo já declarada (sem const)
      toast({
        title: "Upload em andamento...",
        description: `Enviando ${typeInfo.name} ${selectedFile.name} para o servidor...`
      });

      const formData = new FormData();
      formData.append('pdf', selectedFile);
      
      formData.append('type', docType);
      formData.append('title', docTitle);
      
      if (docType === "escala" && docCategory) {
        formData.append('category', docCategory);

      }

      if (docType === "cardapio" && docUnit) {
        formData.append('unit', docUnit);
      }

      const progressInterval = setInterval(() => {
        setUploadProgress(prev => {
          if (prev >= 90) {
            clearInterval(progressInterval);
            return 90;
          }
          return prev + 10;
        });
      }, 200);

      const uploadUrl = getBackendUrl('/api/upload-pdf');
      
      console.log("📤 Enviando para:", uploadUrl);
      
      const uploadResponse = await authorizedFetch(uploadUrl, {
        method: 'POST',
        body: formData,
      });

      clearInterval(progressInterval);
      setUploadProgress(100);

      if (!uploadResponse.ok) {
        const errorData = await uploadResponse.json().catch(() => ({}));
        throw new Error(errorData.error || `Erro HTTP: ${uploadResponse.status}`);
      }

      const uploadResult = await uploadResponse.json();
      console.log("✅ Upload realizado com sucesso:", uploadResult);

      if (!uploadResult.success) {
        throw new Error(uploadResult.error || 'Upload falhou');
      }

      const serverRelativeUrl = String(uploadResult.data.url || '');
      const fullUrl = getBackendUrl(serverRelativeUrl);
      
      console.log("📄 Adicionando documento ao contexto:", {
        title: docTitle,
        url: fullUrl,
        type: docType,
        category: docType === "escala" ? docCategory : undefined
      });
      
      const uploadTags = Array.isArray(uploadResult.data?.tags)
        ? uploadResult.data.tags
        : [];

      const uploadUnit = (uploadResult.data?.unit as PDFDocument['unit'] | undefined)
        ?? (docType === "cardapio" ? docUnit : undefined);

      addDocument({
        title: docTitle,
        url: serverRelativeUrl,
        type: docType,
        category: docType === "escala" ? docCategory : undefined,
        unit: uploadUnit,
        tags: uploadTags,
        active: true
      });
      
      // ✅ USE a variável typeInfo já declarada (sem const)
      toast({
        title: "Sucesso!",
        description: `${typeInfo.name} enviado e salvo com sucesso.`
      });
      
    } else if (docUrl && !docUrl.startsWith('blob:')) {
      const fullUrl = docUrl.startsWith('http') ? docUrl : getBackendUrl(docUrl);
      
      addDocument({
        title: docTitle,
        url: fullUrl,
        type: docType,
        category: docType === "escala" ? docCategory : undefined,
        unit: docType === "cardapio" ? docUnit : undefined,
        tags: [],
        active: true
      });
      
      // ✅ USE a variável typeInfo já declarada (sem const)
      toast({
        title: "Sucesso!",
        description: `${typeInfo.name} adicionado com sucesso.`
      });
    }
    
    resetForm();

  } catch (error) {
    console.error('❌ Erro no upload:', error);
    
    let errorMessage = "Não foi possível enviar o arquivo. Tente novamente.";

    const baseErrorMessage = getErrorMessage(error);
    if (baseErrorMessage.includes('FILE_TOO_LARGE')) {
      errorMessage = "Arquivo muito grande. Máximo permitido: 50MB.";
    } else if (baseErrorMessage.includes('INVALID_FILE')) {
      errorMessage = "Tipo de arquivo não suportado. Use PDFs ou imagens.";
    } else if (baseErrorMessage.includes('MISSING_FIELDS')) {
      errorMessage = "Dados obrigatórios estão faltando.";
    } else if (baseErrorMessage.includes('fetch')) {
      errorMessage = "Erro de conexão. Verifique se o servidor está rodando.";
    } else if (baseErrorMessage !== 'Erro desconhecido') {
      errorMessage = `Erro: ${baseErrorMessage}`;
    }
    
    
    toast({
      title: "Erro no upload",
      description: errorMessage,
      variant: "destructive"
    });
  } finally {
    setIsUploading(false);
    setUploadProgress(0);
  }
};

  const resetForm = () => {
    setDocTitle("");
    if (docUrl.startsWith('blob:')) {
      URL.revokeObjectURL(docUrl);
    }
    setDocUrl("");
    setSelectedFile(null);
    setDocCategory(undefined);
    setDocUnit(undefined);
    setSelectedDocType(null);

    const fileInput = document.getElementById('docFile') as HTMLInputElement;
    if (fileInput) {
      fileInput.value = '';
    }
  };

  const removeDocument = async (id: string) => {
    if (confirm("Tem certeza que deseja remover este documento?")) {
      const doc = [...plasaDocuments, ...escalaDocuments, ...cardapioDocuments].find(d => d.id === id);
      
      if (doc && doc.url.includes('/uploads/')) {
        try {
          const filename = doc.url.split('/uploads/')[1];
          const deleteUrl = getBackendUrl(`/api/delete-pdf/${filename}`);
          const response = await authorizedFetch(deleteUrl, {
            method: 'DELETE'
          });
          
          if (response.ok) {
            const result = await response.json();
            console.log(`✅ Arquivo ${filename} removido do servidor:`, result);
          } else {
            console.log(`⚠️ Não foi possível remover ${filename} do servidor`);
          }
        } catch (error) {
          console.log("⚠️ Erro ao remover arquivo do servidor:", error);
        }
      }
      
      deleteDocument(id);
      toast({
        title: "Documento removido",
        description: "O documento foi removido com sucesso."
      });
    }
  };
  
  const handleEscalaIntervalChange = (
    e: React.ChangeEvent<HTMLInputElement>,
    min = 10,
    max = 300
  ) => {
    const value = parseInt(e.target.value);
    if (Number.isNaN(value)) {
      return;
    }

    if (value < min || value > max) {
      toast({
        title: "Valor inválido",
        description: `Informe um valor entre ${min} e ${max} segundos para alternância das escalas.`,
        variant: "destructive"
      });
      return;
    }

    setEscalaAlternateInterval(value * 1000);
    toast({
      title: "Intervalo de escalas atualizado",
      description: `Escalas agora alternam a cada ${value} segundos.`
    });
  };

  const handleCardapioIntervalChange = (
    e: React.ChangeEvent<HTMLInputElement>,
    min = 10,
    max = 300
  ) => {
    const value = parseInt(e.target.value);
    if (Number.isNaN(value)) {
      return;
    }

    if (value < min || value > max) {
      toast({
        title: "Valor inválido",
        description: `Informe um valor entre ${min} e ${max} segundos para alternância dos cardápios.`,
        variant: "destructive"
      });
      return;
    }

    setCardapioAlternateInterval(value * 1000);
    toast({
      title: "Intervalo de cardápios atualizado",
      description: `Cardápios agora alternam a cada ${value} segundos.`
    });
  };

  const handleScrollSpeedChange = (value: string) => {
    setScrollSpeed(value as "slow" | "normal" | "fast");
    toast({
      title: "Velocidade atualizada",
      description: `Velocidade de rolagem do PLASA definida como: ${
        value === "slow" ? "Lenta" : 
        value === "normal" ? "Normal" : "Rápida"
      }`
    });
  };

  const handleAutoRestartChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = parseInt(e.target.value);
    if (value >= 2 && value <= 10) {
      setAutoRestartDelay(value);
      toast({
        title: "Intervalo de reinício atualizado",
        description: `PLASA aguardará ${value} segundos no final antes de reiniciar.`
      });
    }
  };

 

  // Effect para verificar status do servidor periodicamente
  useEffect(() => {
    if (!isAuthenticated) {
      return;
    }

    void checkServerStatus();
    const interval = setInterval(() => {
      void checkServerStatus();
    }, 30000); // A cada 30 segundos
    return () => clearInterval(interval);
  }, [
    isAuthenticated,
    plasaDocuments.length,
    escalaDocuments.length,
    cardapioDocuments.length,
  ]);
  // Componente de Status do Servidor
  const ServerStatusIndicator = () => (
    <Card className="mb-6">
      <CardHeader className="pb-3">
        <CardTitle className="text-lg flex items-center gap-2">
          🖥️ Status do Sistema
          <Button 
            variant="outline" 
            size="sm" 
            onClick={checkServerStatus}
            className="ml-auto"
          >
            🔄 Verificar
          </Button>
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 items-stretch">
          {/* Status de Conexão */}
          <div className={`p-3 rounded-lg border flex flex-col items-start text-left gap-3 ${
            serverStatus.connected
              ? 'bg-green-50 border-green-200 text-green-800'
              : 'bg-red-50 border-red-200 text-red-800'
          }`}>
            <div className={`w-3 h-3 rounded-full ${
              serverStatus.connected ? 'bg-green-500' : 'bg-red-500'
            }`}></div>
            <span className="font-medium">
              {serverStatus.connected ? 'Conectado' : 'Desconectado'}
            </span>
            <div className="text-sm">
              {serverStatus.lastResponse ? `HTTP ${serverStatus.lastResponse}` : 'Sem resposta'}
            </div>
          </div>



          {/* Documentos */}
          <div className="p-3 rounded-lg border bg-purple-50 border-purple-200 text-purple-800 flex flex-col items-start text-left gap-3">
            <span className="text-xl">📁</span>
            <span className="font-medium">Documentos</span>
            <div className="text-sm">
              {serverStatus.documents} carregados
            </div>
          </div>

          {/* Última Verificação */}
          <div className="p-3 rounded-lg border bg-gray-50 border-gray-200 text-gray-800 flex flex-col items-start text-left gap-3">
            <span className="text-xl">⏰</span>
            <span className="font-medium">Última Check</span>
            <div className="text-sm">
              {serverStatus.lastCheck
                ? serverStatus.lastCheck.toLocaleTimeString('pt-BR', {
                    hour: '2-digit',
                    minute: '2-digit'
                  })
                : 'Nunca'
              }
            </div>
          </div>
        </div>

        {/* Status detalhado */}
        <div className="mt-4 p-3 bg-gray-50 rounded-lg">
          <div className="text-sm text-gray-600">
            <strong>URL do Backend:</strong> {getBackendUrl('/api')} | 
            <strong className="ml-2">Status:</strong> 
            <span className={`ml-1 ${
              serverStatus.connected ? 'text-green-600' : 'text-red-600'
            }`}>
              {serverStatus.connected ? '✅ Online' : '❌ Offline'}
            </span>
          </div>
        </div>
      </CardContent>
    </Card>
  );

  const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  if (isAuthChecking) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <Card className="w-full max-w-sm">
          <CardHeader className="text-center">
            <CardTitle className="flex items-center justify-center gap-2">
              <Loader2 className="h-5 w-5 animate-spin" />
              Verificando acesso
            </CardTitle>
            <CardDescription>
              Aguarde enquanto confirmamos sua sessão ativa.
            </CardDescription>
          </CardHeader>
        </Card>
      </div>
    );
  }

  if (!isAuthenticated) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <Card className="w-full max-w-md shadow-lg">
          <CardHeader className="text-center space-y-3">
            <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-navy/10">
              <Lock className="h-6 w-6 text-navy" />
            </div>
            <CardTitle>Painel Administrativo</CardTitle>
            <CardDescription>
              Informe suas credenciais para acessar o gerenciamento do sistema.
            </CardDescription>
          </CardHeader>
          <CardContent>
            {loginError && (
              <div className="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                {loginError}
              </div>
            )}
            <form className="space-y-4" onSubmit={handleLoginSubmit}>
              <div className="space-y-2">
                <Label htmlFor="username">Usuário</Label>
                <Input
                  id="username"
                  value={loginForm.username}
                  autoComplete="username"
                  onChange={(event) =>
                    setLoginForm((current) => ({
                      ...current,
                      username: event.target.value,
                    }))
                  }
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="password">Senha</Label>
                <Input
                  id="password"
                  type="password"
                  value={loginForm.password}
                  autoComplete="current-password"
                  onChange={(event) =>
                    setLoginForm((current) => ({
                      ...current,
                      password: event.target.value,
                    }))
                  }
                  required
                />
              </div>
              <Button type="submit" className="w-full" disabled={isLoggingIn}>
                {isLoggingIn ? (
                  <span className="flex items-center justify-center gap-2">
                    <Loader2 className="h-4 w-4 animate-spin" />
                    Entrando...
                  </span>
                ) : (
                  <span className="flex items-center justify-center gap-2">
                    <Lock className="h-4 w-4" />
                    Entrar
                  </span>
                )}
              </Button>
            </form>
          </CardContent>
          <CardFooter className="flex flex-col items-center gap-2 text-sm text-muted-foreground">
            <p>Usuário padrão: <span className="font-semibold">admin</span></p>
            <p>Senha padrão: <span className="font-semibold">tel@p@pem2025</span></p>
            <Link to="/" className="text-navy hover:underline">
              ← Voltar para a visualização pública
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 p-4 lg:p-8">
      <div className="max-w-6xl mx-auto">
        <header className="bg-navy text-white p-4 rounded-lg shadow-lg mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <div>
            <h1 className="text-2xl font-bold">Painel Administrativo</h1>
            <p className="text-gray-200">Gerencie documentos e avisos do sistema de visualização</p>
          </div>
          <div className="flex flex-col-reverse gap-3 md:flex-row md:items-center md:gap-4">
            <div className="text-sm text-gray-200">
              <span className="block text-xs uppercase tracking-wide text-gray-300">Usuário logado</span>
              <span className="font-semibold text-white">{sessionUsername ?? 'admin'}</span>
            </div>
            <Link to="/">
              <Button variant="secondary">
                📺 Visualizar Sistema
              </Button>
            </Link>
            <Button
              variant="outline"
              className="text-white border-white hover:bg-white hover:text-navy"
              onClick={() => window.open(getBackendUrl('/api/status'), '_blank')}
            >
              🔧 Status do Servidor
            </Button>
            <Button
              variant="destructive"
              onClick={handleLogout}
              className="flex items-center gap-2"
            >
              <LogOut className="h-4 w-4" />
              Sair
            </Button>
          </div>
        </header>
        
        {/* Status Panel */}
        <ServerStatusIndicator />
        
        <Tabs defaultValue="documentos" className="w-full">
        <TabsList className="w-full mb-6">
            <TabsTrigger value="documentos" className="flex-1">📄 Documentos</TabsTrigger>
            <TabsTrigger value="militares" className="flex-1">👮 Militares</TabsTrigger>
            <TabsTrigger value="sistema" className="flex-1">⚙️ Sistema</TabsTrigger>
            {isAdminUser && (
              <TabsTrigger value="usuarios" className="flex-1">👤 Usuários</TabsTrigger>
            )}
          </TabsList>
          
        
          
        {/* Aba de Documentos - CÓDIGO COMPLETO */}
<TabsContent value="documentos">
  <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {/* Upload New Document Form */}
    <Card className="border-navy">
      <CardHeader className="bg-navy text-white">
        <CardTitle>Adicionar Novo Documento</CardTitle>
        <CardDescription className="text-gray-200">
          Envie um novo documento PDF ou imagem para o sistema
        </CardDescription>
      </CardHeader>
      <form onSubmit={handleDocumentSubmit}>
        <CardContent className="space-y-4 pt-6">
          <div className="space-y-2">
            <Label htmlFor="docType">Tipo de Documento</Label>
            <Select
              value={selectedDocType ?? ""}
              onValueChange={(value) => {
                setSelectedDocType(value as "plasa" | "escala" | "cardapio");
                if (value !== "escala") {
                  setDocCategory(undefined);
                }
                if (value !== "cardapio") {
                  setDocUnit(undefined);
                }
              }}
            >
              <SelectTrigger>
                <SelectValue placeholder="Selecione o tipo" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="plasa">📄 PLASA - Plano de Serviço</SelectItem>
                <SelectItem value="escala">📋 Escala de Serviço</SelectItem>
                <SelectItem value="cardapio">🍽️ Cardápio Semanal</SelectItem>
              </SelectContent>
            </Select>
          </div>
          
          {selectedDocType === "escala" && (
            <div className="space-y-2">
              <Label htmlFor="docCategory">Categoria da Escala</Label>
              <Select 
                value={docCategory} 
                onValueChange={(value) => setDocCategory(value as "oficial" | "praca")}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Selecione a categoria" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="oficial">👨‍✈️ Oficiais</SelectItem>
                  <SelectItem value="praca">👨‍🔧 Praças</SelectItem>
                </SelectContent>
              </Select>
            </div>
          )}
          {selectedDocType === "cardapio" && (
  <div className="space-y-2">
    <Label htmlFor="docUnit">Unidade do Cardápio</Label>
    <Select 
      value={docUnit} 
      onValueChange={(value) => setDocUnit(value as "EAGM" | "1DN")}
    >
      <SelectTrigger>
        <SelectValue placeholder="Selecione a unidade" />
      </SelectTrigger>
      <SelectContent>
        <SelectItem value="EAGM">🏢 EAGM (Gastão Mota)</SelectItem>
        <SelectItem value="1DN">⚓ 1º DN (Distrito Naval)</SelectItem>
      </SelectContent>
    </Select>
  </div>
)}

          
          <div className="space-y-2">
            <Label htmlFor="docTitle">Título do Documento</Label>
            <Input 
              id="docTitle" 
              placeholder={`Ex: ${
                selectedDocType === "plasa" ? "PLASA - Junho 2025" : 
                selectedDocType === "escala" ? "Escala de Serviço - Junho 2025" :
                selectedDocType === "cardapio" ? "Cardápio - Semana 25/2025" :
                "Documento"
              }`}
              value={docTitle}
              onChange={(e) => setDocTitle(e.target.value)}
            />
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="docFile">Arquivo do Documento</Label>
            <Input 
              id="docFile"
              type="file"
              accept="application/pdf,image/*,.pdf,.jpg,.jpeg,.png,.gif,.webp"
              onChange={handleFileChange}
            />
            <div className="text-xs space-y-1">
              {selectedFile ? (
                <div className="text-green-600 bg-green-50 p-2 rounded">
                  ✅ <strong>Arquivo selecionado:</strong> {selectedFile.name} 
                  <br />
                  📏 <strong>Tamanho:</strong> {formatFileSize(selectedFile.size)}
                  <br />
                  📋 <strong>Tipo:</strong> {selectedFile.type}
                </div>
              ) : (
                <div className="text-gray-600">
                  📁 Aceita PDFs ou imagens (JPG, PNG, GIF, WEBP) - máximo 50MB
                </div>
              )}
            </div>
            <div className="text-xs text-blue-600 bg-blue-50 p-2 rounded">
              💡 <strong>Recomendação:</strong> PDFs são automaticamente convertidos para imagens para melhor compatibilidade
            </div>
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="docUrl">URL do Documento (alternativo)</Label>
            <Input 
              id="docUrl" 
              placeholder="https://exemplo.com/documento.pdf"
              value={docUrl.startsWith('blob:') ? '' : docUrl}
              onChange={(e) => setDocUrl(e.target.value)}
              type="url"
              disabled={!!selectedFile}
            />
            <p className="text-xs text-muted-foreground">
              Se não tiver arquivo para upload, pode fornecer uma URL direta.
            </p>
          </div>

          {isUploading && (
            <div className="space-y-2">
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className="bg-navy h-2 rounded-full transition-all duration-300"
                  style={{ width: `${uploadProgress}%` }}
                />
              </div>
              <p className="text-xs text-center text-navy">
                {uploadProgress < 100 ? `Enviando... ${uploadProgress}%` : "Processando..."}
              </p>
            </div>
          )}
        </CardContent>
        <CardFooter>
          <Button 
            type="submit" 
            className="w-full bg-navy hover:bg-navy-light"
            disabled={isUploading || (!selectedFile && !docUrl) || !docTitle}
          >
            {isUploading ? (
              <>
                <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Enviando...
              </>
            ) : (
              <>
                📤 Adicionar Documento
              </>
            )}
          </Button>
        </CardFooter>
      </form>
    </Card>
    
    {/* Document Lists Separadas */}
    <div className="space-y-6">
      {/* 📄 PLASA Documents */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            📄 Documentos PLASA
            <span className="text-sm font-normal text-gray-500">
              ({plasaDocuments.length})
            </span>
          </CardTitle>
          <CardDescription>
            Planos de Serviço e Boletins - Rolagem automática contínua 
          </CardDescription>
        </CardHeader>
        <CardContent>
          {plasaDocuments.length === 0 ? (
            <p className="text-muted-foreground text-center py-4">
              Nenhum documento PLASA cadastrado.
            </p>
          ) : (
            <ul className="space-y-2">
              {plasaDocuments.map((doc) => (
                <li key={doc.id} className="border rounded-md p-3 flex justify-between items-center document-card">
                  <div className="flex-1 min-w-0">
                    <div className="flex flex-wrap items-start gap-2 mb-1">
                      <span className="text-lg">
                        {doc.type === "plasa" ? "📄" : "📋"}
                      </span>
                      <p className="font-medium truncate">{doc.title}</p>
                      <span className={`text-xs px-2 py-0.5 rounded-full status-badge ${
                        doc.type === "plasa"
                          ? "bg-blue-100 text-blue-800"
                          : "bg-purple-100 text-purple-800"
                      }`}>
                        PLASA
                      </span>
                      <TagBadges
                        tags={doc.tags}
                        documentId={doc.id}
                        className={(doc.tags?.length ?? 0) > 2 ? "w-full" : ""}
                      />
                    </div>
                    <div className="flex items-center gap-4 text-xs text-muted-foreground">
                      <span className="flex items-center gap-1">
                        📅 {new Date(doc.uploadDate).toLocaleDateString('pt-BR')}
                      </span>
                      {doc.url.includes('/uploads/') && (
                        <span className="flex items-center gap-1 bg-green-100 text-green-800 px-2 py-0.5 rounded-full status-badge">
                          🌐 Servidor
                        </span>
                      )}
                      <span className="flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full status-badge">
                        📖 Rolagem
                      </span>
                    </div>
                  </div>
                  <div className="flex items-center gap-2 ml-2">
                    <Sheet>
                      <SheetTrigger asChild>
                        <Button variant="outline" size="sm" title="Visualizar documento">👁️</Button>
                      </SheetTrigger>
                      <SheetContent className="w-[85vw] sm:max-w-4xl">
                        <SheetHeader>
                          <SheetTitle>{doc.title}</SheetTitle>
                          <SheetDescription>
                            Visualização prévia do documento
                          </SheetDescription>
                        </SheetHeader>
                        <div className="mt-6 h-[80vh]">
                          <iframe 
                            src={doc.url} 
                            className="w-full h-full border rounded"
                            title={doc.title}
                          />
                        </div>
                      </SheetContent>
                    </Sheet>
                    <Button 
                      variant="destructive" 
                      size="sm"
                      onClick={() => removeDocument(doc.id)}
                      title="Remover documento"
                    >
                      🗑️
                    </Button>
                  </div>
                </li>
              ))}
            </ul>
          )}
        </CardContent>
      </Card>
      
      {/* 📋 ESCALA Documents */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            📋 Escalas de Serviço
            <span className="text-sm font-normal text-gray-500">
              ({escalaDocuments.filter(doc => doc.type === "escala").length})
            </span>
          </CardTitle>
          <CardDescription>
            Escalas de Oficiais e Praças - Alternância automática
          </CardDescription>
        </CardHeader>
        <CardContent>
          {escalaDocuments.filter(doc => doc.type === "escala").length === 0 ? (
            <p className="text-muted-foreground text-center py-4">
              Nenhuma escala cadastrada.
            </p>
          ) : (
            <ul className="space-y-2">
              {escalaDocuments.filter(doc => doc.type === "escala").map((doc) => (
                <li key={doc.id} className="border rounded-md p-3 flex justify-between items-center document-card">
                  <div className="flex-1 min-w-0">
                    <div className="flex flex-wrap items-start gap-2 mb-1">
                      <span className="text-lg">📋</span>
                      <p className="font-medium truncate">{doc.title}</p>
                      {doc.category && (
                        <span className={`text-xs px-2 py-0.5 rounded-full status-badge ${
                          doc.category === "oficial"
                            ? "bg-blue-100 text-blue-800"
                            : "bg-green-100 text-green-800"
                        }`}>
                          {doc.category === "oficial" ? "👨‍✈️ Oficiais" : "👨‍🔧 Praças"}
                        </span>
                      )}
                      <TagBadges
                        tags={doc.tags}
                        documentId={doc.id}
                        className={(doc.tags?.length ?? 0) > 2 ? "w-full" : ""}
                      />
                    </div>
                    <div className="flex items-center gap-4 text-xs text-muted-foreground">
                      <span className="flex items-center gap-1">
                        📅 {new Date(doc.uploadDate).toLocaleDateString('pt-BR')}
                      </span>
                      {doc.url.includes('/uploads/') && (
                        <span className="flex items-center gap-1 bg-green-100 text-green-800 px-2 py-0.5 rounded-full status-badge">
                          🌐 Servidor
                        </span>
                      )}
                      <span className="flex items-center gap-1 bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full status-badge">
                        🔄 Alternância
                      </span>
                    </div>
                  </div>
                  <div className="flex items-center gap-2 ml-2">
                    <Sheet>
                      <SheetTrigger asChild>
                        <Button variant="outline" size="sm" title="Visualizar escala">👁️</Button>
                      </SheetTrigger>
                      <SheetContent className="w-[85vw] sm:max-w-4xl">
                        <SheetHeader>
                          <SheetTitle>📋 {doc.title}</SheetTitle>
                          <SheetDescription>
                            Visualização prévia da escala de serviço
                          </SheetDescription>
                        </SheetHeader>
                        <div className="mt-6 h-[80vh]">
                          <iframe 
                            src={doc.url} 
                            className="w-full h-full border rounded"
                            title={doc.title}
                          />
                        </div>
                      </SheetContent>
                    </Sheet>
                    <Button 
                      variant="destructive" 
                      size="sm"
                      onClick={() => removeDocument(doc.id)}
                      title="Remover escala"
                    >
                      🗑️
                    </Button>
                  </div>
                </li>
              ))}
            </ul>
          )}
        </CardContent>
      </Card>

      {/* 🍽️ CARDÁPIO Documents */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            🍽️ Cardápios Semanais
            <span className="text-sm font-normal text-gray-500">
              ({cardapioDocuments.length})
            </span>
          </CardTitle>
          <CardDescription>
            Cardápios da Semana - Alternância automática
          </CardDescription>
        </CardHeader>
        <CardContent>
          {cardapioDocuments.length === 0 ? (
            <p className="text-muted-foreground text-center py-4">
              Nenhum cardápio cadastrado.
            </p>
          ) : (
            <ul className="space-y-2">
              {cardapioDocuments.map((doc) => (
                <li key={doc.id} className="border rounded-md p-3 flex justify-between items-center document-card">
                  <div className="flex-1 min-w-0">
                    <div className="flex flex-wrap items-start gap-2 mb-1">
                      <span className="text-lg">🍽️</span>
                      <p className="font-medium truncate">{doc.title}</p>
                      <span className="text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full status-badge">
                        CARDÁPIO
                      </span>
                      {doc.unit && (
                        <span className={`text-xs px-2 py-0.5 rounded-full status-badge ${
                          doc.unit === "EAGM"
                            ? "bg-blue-100 text-blue-800"
                            : "bg-green-100 text-green-800"
                        }`}>
                          {doc.unit === "EAGM" ? "🏢 EAGM" : "⚓ 1º DN"}
                        </span>
                      )}
                      <TagBadges
                        tags={doc.tags}
                        documentId={doc.id}
                        className={(doc.tags?.length ?? 0) > 2 ? "w-full" : ""}
                      />
                    </div>
                    <div className="flex items-center gap-4 text-xs text-muted-foreground">
                      <span className="flex items-center gap-1">
                        📅 {new Date(doc.uploadDate).toLocaleDateString('pt-BR')}
                      </span>
                      {doc.url.includes('/uploads/') && (
                        <span className="flex items-center gap-1 bg-green-100 text-green-800 px-2 py-0.5 rounded-full status-badge">
                          🌐 Servidor
                        </span>
                      )}
                      <span className="flex items-center gap-1 bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full status-badge">
                        🔄 Alternância
                      </span>
                    </div>
                  </div>
                  <div className="flex items-center gap-2 ml-2">
                    <Sheet>
                      <SheetTrigger asChild>
                        <Button variant="outline" size="sm" title="Visualizar cardápio">👁️</Button>
                      </SheetTrigger>
                      <SheetContent className="w-[85vw] sm:max-w-4xl">
                        <SheetHeader>
                          <SheetTitle>🍽️ {doc.title}</SheetTitle>
                          <SheetDescription>
                            Visualização prévia do cardápio semanal
                          </SheetDescription>
                        </SheetHeader>
                        <div className="mt-6 h-[80vh]">
                          <iframe 
                            src={doc.url} 
                            className="w-full h-full border rounded"
                            title={doc.title}
                          />
                        </div>
                      </SheetContent>
                    </Sheet>
                    <Button 
                      variant="destructive" 
                      size="sm"
                      onClick={() => removeDocument(doc.id)}
                      title="Remover cardápio"
                    >
                      🗑️
                    </Button>
                  </div>
                </li>
              ))}
            </ul>
          )}
        </CardContent>
      </Card>
    </div>
  </div>

</TabsContent>



          {/* Aba de Militares */}
          <TabsContent value="militares">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Formulário de Oficiais */}
              <Card className="border-navy">
                <CardHeader className="bg-navy text-white">
                  <CardTitle>👮 Oficiais de Serviço</CardTitle>
                  <CardDescription className="text-gray-200">
                    Configure o oficial do dia e contramestre do serviço
                  </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4 pt-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* Oficial do Dia */}
                    <div className="space-y-4 p-4 border rounded-lg bg-blue-50">
                      <h3 className="font-medium text-blue-800 flex items-center gap-2">
                        🎖️ Oficial do Dia
                      </h3>
                      


                      <div className="space-y-2">
                        <Label htmlFor="officerName">Nome do Oficial</Label>
                        <Select
                          value={normalizeDutyNameValue(dutyOfficers.officerName)}
                          onValueChange={(value) => {
                            console.log('🔄 Selecionando oficial:', value);
                            const officer = availableOfficers.find(
                              o => normalizeDutyNameValue(o.name) === value
                            );
                            console.log('👮 Oficial encontrado:', officer);

                            const normalizedName = officer
                              ? normalizeDutyNameValue(officer.name)
                              : normalizeDutyNameValue(value);
                            const formattedRank = officer
                              ? formatRankWithSpecialty(officer.rank, officer.specialty || null)
                              : normalizeDutyRankValue(dutyOfficers.officerRank);

                            const newOfficers = {
                              ...dutyOfficers,
                              officerName: normalizedName,
                              officerRank: formattedRank ?? dutyOfficers.officerRank,
                            };
                            console.log('🔄 Novo estado dos oficiais:', newOfficers);
                            setDutyOfficers(newOfficers);
                          }}
                          disabled={isLoadingOfficers}
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="Selecione o oficial" />
                          </SelectTrigger>
                          <SelectContent>
                            {availableOfficers.map((officer, index) => (
                              <SelectItem
                                key={`officer-${index}-${officer.name}`}
                                value={normalizeDutyNameValue(officer.name)}
                              >
                                {formatMilitaryLabel(officer)}
                              </SelectItem>
                            ))}
                            {isLoadingComboboxData && (
                              <SelectItem value="" disabled>
                                Carregando oficiais...
                              </SelectItem>
                            )}
                          </SelectContent>
                        </Select>
                      </div>
                    </div>

                    {/* Contramestre */}
                    <div className="space-y-4 p-4 border rounded-lg bg-green-50">
                      <h3 className="font-medium text-green-800 flex items-center gap-2">
                        ⚓ Contramestre do Serviço
                      </h3>
                      


                      <div className="space-y-2">
                        <Label htmlFor="masterName">Nome do Contramestre</Label>
                        <Select
                          value={normalizeDutyNameValue(dutyOfficers.masterName)}
                          onValueChange={(value) => {
                            console.log('🔄 Selecionando contramestre:', value);
                            const master = availableMasters.find(
                              m => normalizeDutyNameValue(m.name) === value
                            );
                            console.log('⚓ Contramestre encontrado:', master);

                            const normalizedName = master
                              ? normalizeDutyNameValue(master.name)
                              : normalizeDutyNameValue(value);
                            const formattedRank = master
                              ? formatRankWithSpecialty(master.rank, master.specialty || null)
                              : normalizeDutyRankValue(dutyOfficers.masterRank);

                            setDutyOfficers({
                              ...dutyOfficers,
                              masterName: normalizedName,
                              masterRank: formattedRank ?? dutyOfficers.masterRank
                            });
                          }}
                          disabled={isLoadingOfficers}
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="Selecione o contramestre" />
                          </SelectTrigger>
                          <SelectContent>
                            {availableMasters.map((master, index) => (
                              <SelectItem
                                key={`master-${index}-${master.name}`}
                                value={normalizeDutyNameValue(master.name)}
                              >
                                {formatMilitaryLabel(master)}
                              </SelectItem>
                            ))}
                            {isLoadingComboboxData && (
                              <SelectItem value="" disabled>
                                Carregando contramestres...
                              </SelectItem>
                            )}
                          </SelectContent>
                        </Select>
                      </div>
                    </div>
                  </div>

                  <div className="flex gap-2 pt-4">
                    <Button 
                      onClick={saveDutyOfficers}
                      disabled={isLoadingOfficers || (!dutyOfficers.officerName && !dutyOfficers.masterName)}
                      className="bg-navy hover:bg-navy/90"
                    >
                      {isLoadingOfficers ? "💾 Salvando..." : "💾 Salvar"}
                    </Button>
                    <Button 
                      variant="outline"
                      onClick={loadDutyOfficers}
                      disabled={isLoadingOfficers}
                    >
                      🔄 Recarregar
                    </Button>
                  </div>
                </CardContent>
              </Card>

              {/* Visualização Atual */}
              <Card>
                <CardHeader>
                  <CardTitle>👁️ Visualização Atual</CardTitle>
                  <CardDescription>
                    Como as informações aparecem na tela principal
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="p-4 border rounded-lg bg-gray-50">
                      <h4 className="font-medium mb-2 text-gray-700">
                        Informações Exibidas no Header:
                      </h4>
                      <div className="space-y-2 text-sm">
                        <div className="flex items-center gap-2">
                          <span className="w-2 h-2 bg-blue-500 rounded-full"></span>
                          <span className="flex items-center gap-2">
                            <strong>Oficial do Dia:</strong> 
                            {dutyOfficers.officerName ? (
                              <div className="flex items-center gap-2">
                                {(() => {
                                  const converted = convertToDisplayFormat(dutyOfficers.officerName, 'officer');
                                  return <span>{converted.displayName}</span>;
                                })()}
                              </div>
                            ) : (
                              <span>Não definido</span>
                            )}
                          </span>
                        </div>
                        <div className="flex items-center gap-2">
                          <span className="w-2 h-2 bg-green-500 rounded-full"></span>
                          <span className="flex items-center gap-2">
                            <strong>Contramestre:</strong>
                            {dutyOfficers.masterName ? (
                              <div className="flex items-center gap-2">
                                {(() => {
                                  const converted = convertToDisplayFormat(dutyOfficers.masterName, 'master');
                                  return <span>{converted.displayName}</span>;
                                })()}
                              </div>
                            ) : (
                              <span>Não definido</span>
                            )}
                          </span>
                        </div>
                      </div>
                    </div>

                    <div className="p-3 bg-blue-50 rounded-lg">
                      <p className="text-sm text-blue-700">
                        💡 <strong>Dica:</strong> As informações dos militares são exibidas no cabeçalho 
                        da tela principal e são atualizadas automaticamente em tempo real.
                      </p>
                    </div>

                    <div className="p-3 bg-amber-50 rounded-lg">
                      <p className="text-sm text-amber-700">
                        ⚠️ <strong>Importante:</strong> Certifique-se de que os nomes estão corretos 
                        antes de salvar, pois eles serão exibidos publicamente.
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>


            </div>
          </TabsContent>

          {/* Aba de Sistema */}
          <TabsContent value="sistema">
            <Tabs defaultValue="sistema" className="w-full">
              <TabsList className="w-full mb-4">
                <TabsTrigger value="sistema" className="flex-1">⚙️ Sistema</TabsTrigger>
                <TabsTrigger value="militares" className="flex-1">👥 Militares</TabsTrigger>
              </TabsList>
              
              {/* Sub-aba Sistema */}
              <TabsContent value="sistema">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                  {/* Card de Configurações do Sistema */}
                  <Card>
                    <CardHeader>
                      <CardTitle>⚙️ Configurações do Sistema</CardTitle>
                      <CardDescription>
                        Ajuste os parâmetros de funcionamento do sistema de visualização
                      </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                      <div className="space-y-2">
                        <Label htmlFor="scrollSpeed">
                          🏃‍♂️ Velocidade de Rolagem do PLASA
                        </Label>
                        <div className="flex items-center space-x-2">
                          <Select value={scrollSpeed} onValueChange={handleScrollSpeedChange}>
                            <SelectTrigger className="w-32">
                              <SelectValue placeholder="Velocidade" />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem value="slow">🐌 Lenta</SelectItem>
                              <SelectItem value="normal">🚶‍♂️ Normal</SelectItem>
                              <SelectItem value="fast">🏃‍♂️ Rápida</SelectItem>
                            </SelectContent>
                          </Select>
                          <span className="text-sm text-muted-foreground">velocidade de scroll</span>
                        </div>
                        <p className="text-xs text-muted-foreground">
                          Define a velocidade com que o PLASA rola automaticamente pela tela.
                        </p>
                      </div>
                      
                      <div className="space-y-4">
                        <div className="space-y-2">
                          <div className="flex items-center">
                            <Label htmlFor="escalaInterval">
                              ⚖️ Intervalo das Escalas (segundos)
                            </Label>
                            <HoverCard>
                              <HoverCardTrigger asChild>
                                <span className="ml-2 text-blue-500 cursor-help text-sm">[?]</span>
                              </HoverCardTrigger>
                              <HoverCardContent className="w-80">
                                <p className="text-sm">
                                  Define quanto tempo cada escala (Oficiais/Praças) permanece na tela
                                  antes de alternar para a próxima. Utilize valores menores para ciclos
                                  rápidos ou maiores para leitura detalhada.
                                </p>
                              </HoverCardContent>
                            </HoverCard>
                          </div>

                          <div className="flex items-center space-x-2">
                            <Input
                              id="escalaInterval"
                              type="number"
                              min="5"
                              max="60"
                              className="w-24"
                              value={Math.floor(escalaAlternateInterval / 1000)}
                              onChange={e => handleEscalaIntervalChange(e, 5, 60)}
                            />
                            <span className="text-sm text-muted-foreground">segundos</span>
                          </div>
                          <p className="text-xs text-muted-foreground">
                            Aplica-se às escalas de Oficiais e Praças exibidas na área principal.
                          </p>
                        </div>

                        <div className="space-y-2">
                          <div className="flex items-center">
                            <Label htmlFor="cardapioInterval">
                              🍽️ Intervalo dos Cardápios (segundos)
                            </Label>
                            <HoverCard>
                              <HoverCardTrigger asChild>
                                <span className="ml-2 text-blue-500 cursor-help text-sm">[?]</span>
                              </HoverCardTrigger>
                              <HoverCardContent className="w-80">
                                <p className="text-sm">
                                  Controla o tempo em que cada cardápio semanal permanece visível
                                  antes de alternar para o próximo (EAGM ou 1DN). Permite ajustar o
                                  ritmo de leitura independentemente das escalas.
                                </p>
                              </HoverCardContent>
                            </HoverCard>
                          </div>

                          <div className="flex items-center space-x-2">
                            <Input
                              id="cardapioInterval"
                              type="number"
                              min="5"
                              max="60"
                              className="w-24"
                              value={Math.floor(cardapioAlternateInterval / 1000)}
                              onChange={e => handleCardapioIntervalChange(e, 5, 60)}
                            />
                            <span className="text-sm text-muted-foreground">segundos</span>
                          </div>
                          <p className="text-xs text-muted-foreground">
                            Afeta apenas os cardápios ativos (EAGM e 1DN) exibidos no rodapé direito.
                          </p>
                        </div>

                        <div className="p-3 bg-orange-50 rounded-lg border-l-4 border-orange-400">
                          <p className="text-sm text-orange-800">
                            <strong>ℹ️ Nota:</strong> Agora é possível definir intervalos independentes
                            para escalas e cardápios, permitindo personalizar o ritmo de leitura de cada área.
                          </p>
                        </div>
                      </div>
                   
                      <div className="space-y-2">
                        <Label htmlFor="autoRestart">
                          🔄 Reinício Automático do PLASA
                        </Label>
                        <div className="flex items-center space-x-2">
                          <Input 
                            id="autoRestart" 
                            type="number" 
                            min="2" 
                            max="10" 
                            className="w-24"
                            value={autoRestartDelay}
                            onChange={handleAutoRestartChange}
                          />
                          <span className="text-sm text-muted-foreground">segundos no final</span>
                        </div>
                        <p className="text-xs text-muted-foreground">
                          Tempo de pausa no final do PLASA antes de reiniciar do topo.
                        </p>
                      </div>
                    </CardContent>
                  </Card>

                  {/* Card de Debug do Sistema */}
                  <Card>
                    <CardHeader>
                      <CardTitle>🔍 Informações do Sistema</CardTitle>
                      <CardDescription>
                        Status e informações técnicas do sistema
                      </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                      <div className="p-4 bg-blue-50 rounded-lg">
                        <h4 className="font-medium mb-2 text-blue-800">📊 Status do Servidor</h4>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                          <div>
                            <p><strong>Conectado:</strong> {serverStatus.connected ? '✅ Sim' : '❌ Não'}</p>
                            <p><strong>Última resposta:</strong> {serverStatus.lastResponse || 'N/A'}</p>
                            <p><strong>Documentos:</strong> {serverStatus.documents}</p>
                          </div>
                          <div>
                            <p><strong>Última verificação:</strong> {serverStatus.lastCheck ? serverStatus.lastCheck.toLocaleTimeString('pt-BR') : 'Nunca'}</p>
                          </div>
                        </div>
                      </div>

                      <div className="space-y-2">
                        <Button 
                          onClick={checkServerStatus}
                          disabled={isLoading}
                          className="w-full"
                        >
                          {isLoading ? 'Verificando...' : '🔄 Verificar Status do Servidor'}
                        </Button>
                        

                      </div>

                      <div className="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <h4 className="font-medium mb-2 text-green-800">💡 Dicas de Debug</h4>
                        <ul className="list-disc pl-5 space-y-1 text-sm text-green-700">
                          <li>Verifique o console do navegador (F12) para logs detalhados</li>
                          <li>O botão "Listar Documentos" mostra todos os PDFs no servidor</li>
                          <li>Status do servidor é atualizado automaticamente</li>
                          <li>Documentos são processados em background</li>
                        </ul>
                      </div>
                    </CardContent>
                  </Card>
                  
                  {/* Card de Logs do Sistema */}
                  <Card>
                    <CardHeader>
                      <CardTitle>📋 Logs do Sistema</CardTitle>
                      <CardDescription>
                        Informações técnicas e logs de funcionamento
                      </CardDescription>
                    </CardHeader>
                    <CardContent>
                      <div className="space-y-2 max-h-64 overflow-y-auto text-sm font-mono bg-gray-100 p-3 rounded">
                        <div>✅ Sistema iniciado com sucesso</div>
                        <div>📡 Backend conectado: {getBackendUrl('/api/status')}</div>
                        <div>🔄 Auto-refresh ativo a cada 30 segundos</div>
                        <div>📱 Interface responsiva carregada</div>
                        <div>🎯 Componentes Radix UI inicializados</div>
                      </div>
                    </CardContent>
                  </Card>

             

                  {/* Card de Manutenção do Sistema */}
                  <Card className="lg:col-span-2">
                    <CardHeader className="bg-orange-50">
                      <CardTitle className="flex items-center gap-2">
                        🔧 Manutenção do Sistema
                      </CardTitle>
                      <CardDescription>
                        Ferramentas de manutenção e limpeza do sistema
                      </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6 pt-6">
                      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {/* Limpeza de Cache */}
                        <div className="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                          <div className="flex items-center gap-2 mb-3">
                            <span className="text-lg">🧹</span>
                            <h4 className="font-medium text-yellow-800">Limpeza de Cache</h4>
                          </div>
                          <p className="text-sm text-yellow-700 mb-3">
                            Limpa cache de PDFs e páginas processadas no servidor.
                          </p>
                          <Button
                            onClick={async () => {
                              try {
                                const response = await fetchBackend('/api/clear-cache', { method: 'POST' });
                                if (response.ok) {
                                  // Limpar também cache do localStorage
                                  localStorage.removeItem('documentContext');
                                  localStorage.removeItem('noticeContext');
                                  localStorage.removeItem('lastDisplayState');
                                  
                                  toast({
                                    title: "Cache limpo",
                                    description: "Cache do servidor e navegador foi limpo com sucesso"
                                  });
                                } else {
                                  throw new Error('Falha na requisição');
                                }
                              } catch (error) {
                                toast({
                                  title: "Erro na limpeza",
                                  description: "Não foi possível limpar o cache do servidor",
                                  variant: "destructive"
                                });
                              }
                            }}
                            variant="outline"
                            size="sm"
                            className="w-full border-yellow-300 text-yellow-700 hover:bg-yellow-100"
                          >
                            Limpar Cache
                          </Button>
                        </div>

                        {/* Limpar Cache do Navegador */}
                        <div className="p-4 bg-purple-50 rounded-lg border border-purple-200">
                          <div className="flex items-center gap-2 mb-3">
                            <span className="text-lg">💻</span>
                            <h4 className="font-medium text-purple-800">Cache do Navegador</h4>
                          </div>
                          <p className="text-sm text-purple-700 mb-3">
                            Limpa dados salvos localmente no navegador.
                          </p>
                          <Button
                            onClick={() => {
                              // Limpar localStorage
                              localStorage.clear();
                              
                              // Limpar sessionStorage
                              sessionStorage.clear();
                              
                              // Forçar reload da página
                              toast({
                                title: "Cache limpo",
                                description: "Recarregando página..."
                              });
                              
                              setTimeout(() => {
                                window.location.reload();
                              }, 1000);
                            }}
                            variant="outline"
                            size="sm"
                            className="w-full border-purple-300 text-purple-700 hover:bg-purple-100"
                          >
                            Limpar e Recarregar
                          </Button>
                        </div>

                        {/* Recarregar Dados */}
                        <div className="p-4 bg-blue-50 rounded-lg border border-blue-200">
                          <div className="flex items-center gap-2 mb-3">
                            <span className="text-lg">🔄</span>
                            <h4 className="font-medium text-blue-800">Recarregar Dados</h4>
                          </div>
                          <p className="text-sm text-blue-700 mb-3">
                            Força recarga dos dados do servidor para sincronização.
                          </p>
                          <Button
                            onClick={() => {
                              // Força recarregamento da página para sincronizar dados
                              window.location.reload();
                            }}
                            variant="outline"
                            size="sm"
                            className="w-full border-blue-300 text-blue-700 hover:bg-blue-100"
                          >
                            Recarregar
                          </Button>
                        </div>

                        {/* Informações do Sistema */}
                        <div className="p-4 bg-green-50 rounded-lg border border-green-200">
                          <div className="flex items-center gap-2 mb-3">
                            <span className="text-lg">📋</span>
                            <h4 className="font-medium text-green-800">Info Sistema</h4>
                          </div>
                          <p className="text-sm text-green-700 mb-3">
                            Ver informações detalhadas sobre arquivos e uso do sistema.
                          </p>
                          <Button
                            onClick={async () => {
                              try {
                                const response = await fetchBackend('/api/list-pdfs');
                                const data = await response.json();
                                console.log('📊 Informações do sistema:', data);
                                alert(`Sistema Status:
• Documentos: ${data.files ? data.files.length : 0}
• Backend: Online
• Storage: Operacional
• Última verificação: ${new Date().toLocaleString('pt-BR')}`);
                              } catch (error) {
                                console.error('Erro ao obter informações:', error);
                                alert('Erro ao acessar informações do sistema');
                              }
                            }}
                            variant="outline"
                            size="sm"
                            className="w-full border-green-300 text-green-700 hover:bg-green-100"
                          >
                            Ver Info
                          </Button>
                        </div>

                      </div>
                    </CardContent>
                  </Card>
              </div>
            </TabsContent>
            
            {/* Sub-aba Automação */}
            <TabsContent value="automacao">
              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Card de Automação PLASA */}
              <Card>
                <CardHeader>
                  <CardTitle>⚙️ Configurações do Sistema</CardTitle>
                  <CardDescription>
                    Ajuste os parâmetros de funcionamento do sistema de visualização
                  </CardDescription>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div className="space-y-2">
                    <Label htmlFor="scrollSpeed">
                      🏃‍♂️ Velocidade de Rolagem do PLASA
                    </Label>
                    <div className="flex items-center space-x-2">
                      <Select value={scrollSpeed} onValueChange={handleScrollSpeedChange}>
                        <SelectTrigger className="w-32">
                          <SelectValue placeholder="Velocidade" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="slow">🐌 Lenta</SelectItem>
                          <SelectItem value="normal">🚶‍♂️ Normal</SelectItem>
                          <SelectItem value="fast">🏃‍♂️ Rápida</SelectItem>
                        </SelectContent>
                      </Select>
                      <span className="text-sm text-muted-foreground">velocidade de scroll</span>
                    </div>
                    <p className="text-xs text-muted-foreground">
                      Define a velocidade com que o PLASA rola automaticamente pela tela.
                    </p>
                  </div>
                  
                  <div className="space-y-6">
                    <div className="space-y-2">
                      <div className="flex items-center">
                        <Label htmlFor="escalaIntervalAdvanced">
                          ⏱️ Intervalo de Alternância entre Escalas (segundos)
                        </Label>
                        <HoverCard>
                          <HoverCardTrigger asChild>
                            <span className="ml-2 text-blue-500 cursor-help text-sm">[?]</span>
                          </HoverCardTrigger>
                          <HoverCardContent className="w-80">
                            <p className="text-sm">
                              Define quanto tempo cada escala (Oficiais/Praças) será exibida antes de
                              alternar para a próxima. Recomenda-se utilizar valores maiores quando as
                              escalas possuem muitas informações.
                            </p>
                          </HoverCardContent>
                        </HoverCard>
                      </div>
                      <div className="flex items-center space-x-2">
                        <Input
                          id="escalaIntervalAdvanced"
                          type="number"
                          min="10"
                          max="300"
                          className="w-24"
                          value={escalaAlternateInterval / 1000}
                          onChange={e => handleEscalaIntervalChange(e, 10, 300)}
                        />
                        <span className="text-sm text-muted-foreground">segundos</span>
                      </div>
                      <p className="text-xs text-muted-foreground">
                        Recomendado: tempo suficiente para visualizar cada escala completamente.
                      </p>
                    </div>

                    <div className="space-y-2">
                      <div className="flex items-center">
                        <Label htmlFor="cardapioIntervalAdvanced">
                          🍽️ Intervalo de Alternância entre Cardápios (segundos)
                        </Label>
                        <HoverCard>
                          <HoverCardTrigger asChild>
                            <span className="ml-2 text-blue-500 cursor-help text-sm">[?]</span>
                          </HoverCardTrigger>
                          <HoverCardContent className="w-80">
                            <p className="text-sm">
                              Ajusta o tempo de exibição dos cardápios semanais (EAGM/1DN). Utilize
                              valores diferentes das escalas para destacar melhor as informações
                              nutricionais.
                            </p>
                          </HoverCardContent>
                        </HoverCard>
                      </div>
                      <div className="flex items-center space-x-2">
                        <Input
                          id="cardapioIntervalAdvanced"
                          type="number"
                          min="10"
                          max="300"
                          className="w-24"
                          value={cardapioAlternateInterval / 1000}
                          onChange={e => handleCardapioIntervalChange(e, 10, 300)}
                        />
                        <span className="text-sm text-muted-foreground">segundos</span>
                      </div>
                      <p className="text-xs text-muted-foreground">
                        Recomendado: valores menores quando houver vários cardápios ativos.
                      </p>
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="autoRestart">
                      🔄 Reinício Automático do PLASA
                    </Label>
                    <div className="flex items-center space-x-2">
                      <Input 
                        id="autoRestart" 
                        type="number" 
                        min="2" 
                        max="10" 
                        className="w-24"
                        value={autoRestartDelay}
                        onChange={handleAutoRestartChange}
                      />
                      <span className="text-sm text-muted-foreground">segundos no final</span>
                    </div>
                    <p className="text-xs text-muted-foreground">
                      Tempo de pausa no final do PLASA antes de reiniciar do topo.
                    </p>
                  </div>
                </CardContent>
              </Card>

              {/* Seção de Gerenciamento de Militares */}
              <Card className="lg:col-span-2 border-green-200">
                <CardHeader className="bg-green-50">
                  <div className="flex justify-between items-center">
                    <div>
                      <CardTitle className="flex items-center gap-2">
                        <span>🎖️</span> Gerenciar Militares
                      </CardTitle>
                      <CardDescription>
                        Lista completa de militares cadastrados com opções de edição
                      </CardDescription>
                    </div>
                    <Button 
                      onClick={() => {
                        setEditingMilitary(null);
                        setMilitaryEditorOpen(true);
                      }}
                      className="bg-green-600 hover:bg-green-700"
                    >
                      ➕ Novo Militar
                    </Button>
                  </div>
                </CardHeader>
                <CardContent className="pt-6">
                  {loadingMilitary ? (
                    <div className="text-center py-8">
                      <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                      <p className="mt-2 text-muted-foreground">Carregando militares...</p>
                    </div>
                  ) : (
                    <div className="space-y-6">
                      {militaryPersonnel.length === 0 ? (
                        <div className="text-center py-8 text-muted-foreground">
                          <p>Nenhum militar cadastrado</p>
                        </div>
                      ) : (
                        <>
                          {/* Seção de Oficiais */}
                          {militaryPersonnel.filter(m => m.type === 'officer').length > 0 && (
                            <div className="space-y-4">
                              <div className="flex items-center gap-2 pb-2 border-b">
                                <h3 className="text-lg font-semibold text-navy">👮 Oficiais</h3>
                                <span className="text-sm text-muted-foreground">
                                  ({militaryPersonnel.filter(m => m.type === 'officer').length})
                                </span>
                              </div>
                              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                {militaryPersonnel.filter(m => m.type === 'officer').map((military) => (
                                  <div 
                                    key={military.id} 
                                    className="p-3 border rounded-lg hover:shadow-md transition-shadow bg-blue-50 border-blue-200"
                                  >
                                    <div className="flex justify-between items-start mb-2">
                                      <div className="flex-1">
                                        <div className="font-semibold text-sm text-blue-800">
                                          {`${formatRankWithSpecialty(military.rank, military.specialty)}${military.specialty ? '' : ' (S/E)'}`}
                                        </div>
                                        {resolveFullRankName(military) && (
                                          <div className="text-xs text-blue-700/80">
                                            {resolveFullRankName(military)}
                                          </div>
                                        )}
                                        <div className="text-base font-bold text-navy">
                                          {military.name.toUpperCase()}
                                        </div>
                                      </div>
                                      <div className="flex gap-1">
                                        <Button
                                          variant="outline"
                                          size="sm"
                                          onClick={() => handleEditMilitary(military)}
                                          className="text-blue-600 border-blue-200 hover:bg-blue-100 h-7 w-7 p-0"
                                        >
                                          ✏️
                                        </Button>
                                        <Button
                                          variant="outline"
                                          size="sm"
                                          onClick={() => handleDeleteMilitary(military.id)}
                                          className="text-red-600 border-red-200 hover:bg-red-50 h-7 w-7 p-0"
                                        >
                                          🗑️
                                        </Button>
                                      </div>
                                    </div>
                                  </div>
                                ))}
                              </div>
                            </div>
                          )}

                          {/* Seção de Praças */}
                          {militaryPersonnel.filter(m => m.type === 'master').length > 0 && (
                            <div className="space-y-4">
                              <div className="flex items-center gap-2 pb-2 border-b">
                                <h3 className="text-lg font-semibold text-green-700">🎖️ Praças</h3>
                                <span className="text-sm text-muted-foreground">
                                  ({militaryPersonnel.filter(m => m.type === 'master').length})
                                </span>
                              </div>
                              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                {militaryPersonnel.filter(m => m.type === 'master').map((military) => (
                                  <div 
                                    key={military.id} 
                                    className="p-3 border rounded-lg hover:shadow-md transition-shadow bg-green-50 border-green-200"
                                  >
                                    <div className="flex justify-between items-start mb-2">
                                      <div className="flex-1">
                                        <div className="font-semibold text-sm text-green-800">
                                          {`${formatRankWithSpecialty(military.rank, military.specialty)}${military.specialty ? '' : ' (S/E)'}`}
                                        </div>
                                        {resolveFullRankName(military) && (
                                          <div className="text-xs text-green-700/80">
                                            {resolveFullRankName(military)}
                                          </div>
                                        )}
                                        <div className="text-base font-bold text-navy">
                                          {military.name.toUpperCase()}
                                        </div>
                                      </div>
                                      <div className="flex gap-1">
                                        <Button
                                          variant="outline"
                                          size="sm"
                                          onClick={() => handleEditMilitary(military)}
                                          className="text-green-600 border-green-200 hover:bg-green-100 h-7 w-7 p-0"
                                        >
                                          ✏️
                                        </Button>
                                        <Button
                                          variant="outline"
                                          size="sm"
                                          onClick={() => handleDeleteMilitary(military.id)}
                                          className="text-red-600 border-red-200 hover:bg-red-50 h-7 w-7 p-0"
                                        >
                                          🗑️
                                        </Button>
                                      </div>
                                    </div>
                                  </div>
                                ))}
                              </div>
                            </div>
                          )}

                          {/* Estatísticas */}
                          <div className="border-t pt-4 text-sm text-muted-foreground bg-gray-50 p-3 rounded">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                              <div className="text-center">
                                <div className="text-2xl font-bold text-navy">{militaryPersonnel.length}</div>
                                <div>Total de Militares</div>
                              </div>
                              <div className="text-center">
                                <div className="text-2xl font-bold text-blue-600">{militaryPersonnel.filter(m => m.type === 'officer').length}</div>
                                <div>Oficiais</div>
                              </div>
                              <div className="text-center">
                                <div className="text-2xl font-bold text-green-600">{militaryPersonnel.filter(m => m.type === 'master').length}</div>
                                <div>Praças</div>
                              </div>
                            </div>
                          </div>
                        </>
                      )}
                    </div>
                  )}
                </CardContent>
              </Card>


                </div>
              </TabsContent>
              
              {/* Sub-aba Militares */}
              <TabsContent value="militares">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                  
                  {/* Seção de Gerenciamento de Militares Completo */}
                  <Card className="lg:col-span-2 border-green-200">
                    <CardHeader className="bg-green-50">
                      <div className="flex justify-between items-center">
                        <div>
                          <CardTitle className="flex items-center gap-2">
                            <span>🎖️</span> Gerenciar Militares
                          </CardTitle>
                          <CardDescription>
                            Lista completa de militares cadastrados com opções de edição
                          </CardDescription>
                        </div>
                        <Button 
                          onClick={() => {
                            setEditingMilitary(null);
                            setMilitaryEditorOpen(true);
                          }}
                          className="bg-green-600 hover:bg-green-700"
                        >
                          ➕ Novo Militar
                        </Button>
                      </div>
                    </CardHeader>
                    <CardContent className="pt-6">
                      {loadingMilitary ? (
                        <div className="text-center py-8">
                          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto"></div>
                          <p className="mt-2 text-muted-foreground">Carregando militares...</p>
                        </div>
                      ) : (
                        <div className="space-y-6">
                          {militaryPersonnel.length === 0 ? (
                            <div className="text-center py-8 text-muted-foreground">
                              <p>Nenhum militar cadastrado</p>
                            </div>
                          ) : (
                            <>
                              {/* Seção de Oficiais */}
                              {militaryPersonnel.filter(m => m.type === 'officer').length > 0 && (
                                <div className="space-y-4">
                                  <div className="flex items-center gap-2 pb-2 border-b">
                                    <h3 className="text-lg font-semibold text-navy">👮 Oficiais</h3>
                                    <span className="text-sm text-muted-foreground">
                                      ({militaryPersonnel.filter(m => m.type === 'officer').length})
                                    </span>
                                  </div>
                                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    {militaryPersonnel.filter(m => m.type === 'officer').map((military) => (
                                      <div 
                                        key={military.id} 
                                        className="p-3 border rounded-lg hover:shadow-md transition-shadow bg-blue-50 border-blue-200"
                                      >
                                        <div className="flex justify-between items-start mb-2">
                                          <div className="flex-1">
                                            <div className="font-semibold text-sm text-blue-800">
                                              {`${formatRankWithSpecialty(military.rank, military.specialty)}${military.specialty ? '' : ' (S/E)'}`}
                                            </div>
                                            {resolveFullRankName(military) && (
                                              <div className="text-xs text-blue-700/80">
                                                {resolveFullRankName(military)}
                                              </div>
                                            )}
                                            <div className="text-base font-bold text-navy">
                                              {military.name.toUpperCase()}
                                            </div>
                                          </div>
                                          <div className="flex gap-1">
                                            <Button
                                              variant="outline"
                                              size="sm"
                                              onClick={() => handleEditMilitary(military)}
                                              className="text-blue-600 border-blue-200 hover:bg-blue-100 h-7 w-7 p-0"
                                            >
                                              ✏️
                                            </Button>
                                            <Button
                                              variant="outline"
                                              size="sm"
                                              onClick={() => handleDeleteMilitary(military.id)}
                                              className="text-red-600 border-red-200 hover:bg-red-50 h-7 w-7 p-0"
                                            >
                                              🗑️
                                            </Button>
                                          </div>
                                        </div>
                                      </div>
                                    ))}
                                  </div>
                                </div>
                              )}

                              {/* Seção de Praças */}
                              {militaryPersonnel.filter(m => m.type === 'master').length > 0 && (
                                <div className="space-y-4">
                                  <div className="flex items-center gap-2 pb-2 border-b">
                                    <h3 className="text-lg font-semibold text-green-700">🎖️ Praças</h3>
                                    <span className="text-sm text-muted-foreground">
                                      ({militaryPersonnel.filter(m => m.type === 'master').length})
                                    </span>
                                  </div>
                                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    {militaryPersonnel.filter(m => m.type === 'master').map((military) => (
                                      <div 
                                        key={military.id} 
                                        className="p-3 border rounded-lg hover:shadow-md transition-shadow bg-green-50 border-green-200"
                                      >
                                        <div className="flex justify-between items-start mb-2">
                                          <div className="flex-1">
                                            <div className="font-semibold text-sm text-green-800">
                                              {`${formatRankWithSpecialty(military.rank, military.specialty)}${military.specialty ? '' : ' (S/E)'}`}
                                            </div>
                                            {resolveFullRankName(military) && (
                                              <div className="text-xs text-green-700/80">
                                                {resolveFullRankName(military)}
                                              </div>
                                            )}
                                            <div className="text-base font-bold text-navy">
                                              {military.name.toUpperCase()}
                                            </div>
                                          </div>
                                          <div className="flex gap-1">
                                            <Button
                                              variant="outline"
                                              size="sm"
                                              onClick={() => handleEditMilitary(military)}
                                              className="text-green-600 border-green-200 hover:bg-green-100 h-7 w-7 p-0"
                                            >
                                              ✏️
                                            </Button>
                                            <Button
                                              variant="outline"
                                              size="sm"
                                              onClick={() => handleDeleteMilitary(military.id)}
                                              className="text-red-600 border-red-200 hover:bg-red-50 h-7 w-7 p-0"
                                            >
                                              🗑️
                                            </Button>
                                          </div>
                                        </div>
                                      </div>
                                    ))}
                                  </div>
                                </div>
                              )}

                              {/* Estatísticas */}
                              <div className="border-t pt-4 text-sm text-muted-foreground bg-gray-50 p-3 rounded">
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                  <div className="text-center">
                                    <div className="text-2xl font-bold text-navy">{militaryPersonnel.length}</div>
                                    <div>Total de Militares</div>
                                  </div>
                                  <div className="text-center">
                                    <div className="text-2xl font-bold text-blue-600">{militaryPersonnel.filter(m => m.type === 'officer').length}</div>
                                    <div>Oficiais</div>
                                  </div>
                                  <div className="text-center">
                                    <div className="text-2xl font-bold text-green-600">{militaryPersonnel.filter(m => m.type === 'master').length}</div>
                                    <div>Praças</div>
                                  </div>
                                </div>
                              </div>
                            </>
                          )}
                        </div>
                      )}
                    </CardContent>
                  </Card>
                  
                </div>
              </TabsContent>
            </Tabs>
          </TabsContent>

          {isAdminUser && (
            <TabsContent value="usuarios">
              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <Card className="border-navy">
                  <CardHeader className="bg-navy text-white">
                    <CardTitle>👤 Cadastro de Usuários</CardTitle>
                    <CardDescription className="text-gray-100">
                      Crie novos acessos ao painel administrativo. Apenas o usuário "admin" tem acesso a esta aba.
                    </CardDescription>
                  </CardHeader>
                  <form onSubmit={handleUserCreation}>
                    <CardContent className="space-y-4 pt-6">
                      <div className="space-y-2">
                        <Label htmlFor="novoUsuario">Nome de usuário</Label>
                        <Input
                          id="novoUsuario"
                          placeholder="ex: operador01"
                          value={userForm.username}
                          onChange={(event) => setUserForm((current) => ({ ...current, username: event.target.value }))}
                        />
                        <p className="text-xs text-muted-foreground">
                          Utilize apenas letras e números. Evite reutilizar o usuário "admin" para maior segurança.
                        </p>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <Label htmlFor="novaSenha">Senha</Label>
                          <Input
                            id="novaSenha"
                            type="password"
                            placeholder="Mínimo de 6 caracteres"
                            value={userForm.password}
                            onChange={(event) => setUserForm((current) => ({ ...current, password: event.target.value }))}
                          />
                        </div>
                        <div className="space-y-2">
                          <Label htmlFor="confirmaSenha">Confirme a senha</Label>
                          <Input
                            id="confirmaSenha"
                            type="password"
                            placeholder="Repita a senha"
                            value={userForm.confirmPassword}
                            onChange={(event) => setUserForm((current) => ({ ...current, confirmPassword: event.target.value }))}
                          />
                        </div>
                      </div>

                      {userCreationError && (
                        <div className="p-3 rounded bg-red-50 text-red-700 text-sm">
                          {userCreationError}
                        </div>
                      )}

                      {userCreationSuccess && (
                        <div className="p-3 rounded bg-green-50 text-green-700 text-sm">
                          {userCreationSuccess}
                        </div>
                      )}
                    </CardContent>
                    <CardFooter className="flex flex-col gap-3">
                      <Button type="submit" className="w-full" disabled={isCreatingUser}>
                        {isCreatingUser ? (
                          <span className="flex items-center gap-2">
                            <Loader2 className="h-4 w-4 animate-spin" />
                            Criando usuário...
                          </span>
                        ) : (
                          'Cadastrar usuário'
                        )}
                      </Button>
                      <p className="text-xs text-muted-foreground text-center">
                        As credenciais criadas permitem acesso ao painel administrativo usando o formulário de login padrão.
                      </p>
                    </CardFooter>
                  </form>
                </Card>

                <div className="space-y-4">
                  <Card className="border-navy/60">
                    <CardHeader className="bg-slate-50">
                      <CardTitle>✏️ Usuários existentes</CardTitle>
                      <CardDescription>
                        Edite nomes de usuário ou redefina senhas. Apenas o admin tem permissão.
                      </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                      {userListError && (
                        <div className="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                          {userListError}
                        </div>
                      )}

                      {isLoadingUsers ? (
                        <div className="flex items-center gap-2 text-muted-foreground">
                          <Loader2 className="h-4 w-4 animate-spin" />
                          Carregando usuários...
                        </div>
                      ) : (
                        <div className="space-y-3">
                          {userList.length === 0 ? (
                            <p className="text-sm text-muted-foreground">Nenhum usuário cadastrado até o momento.</p>
                          ) : (
                            userList.map((user) => {
                              const editState = userEdits[user.id] ?? { username: user.username, password: '', confirmPassword: '' };
                              const isUpdating = Boolean(updatingUserIds[user.id]);

                              return (
                                <div key={user.id} className="rounded border border-slate-200 bg-white p-3 shadow-sm">
                                  <div className="flex flex-col gap-3">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                      <div className="space-y-1">
                                        <Label>Usuário</Label>
                                        <Input
                                          value={editState.username}
                                          disabled={user.username === 'admin'}
                                          onChange={(event) => updateUserEdits(user.id, { username: event.target.value })}
                                        />
                                        {user.username === 'admin' && (
                                          <p className="text-xs text-muted-foreground">O nome do usuário admin é fixo para manter o acesso.</p>
                                        )}
                                      </div>

                                      <div className="space-y-1">
                                        <Label>Nova senha</Label>
                                        <Input
                                          type="password"
                                          placeholder="Opcional"
                                          value={editState.password}
                                          onChange={(event) => updateUserEdits(user.id, { password: event.target.value })}
                                        />
                                      </div>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                      <div className="space-y-1">
                                        <Label>Confirme a nova senha</Label>
                                        <Input
                                          type="password"
                                          placeholder="Repita a senha"
                                          value={editState.confirmPassword}
                                          onChange={(event) => updateUserEdits(user.id, { confirmPassword: event.target.value })}
                                        />
                                      </div>
                                      <div className="flex items-end justify-end gap-2">
                                        <Button
                                          type="button"
                                          variant="outline"
                                          onClick={() => updateUserEdits(user.id, { username: user.username, password: '', confirmPassword: '' })}
                                          disabled={isUpdating}
                                        >
                                          Restaurar
                                        </Button>
                                        <Button
                                          type="button"
                                          onClick={() => void handleUserUpdate(user.id)}
                                          disabled={isUpdating}
                                        >
                                          {isUpdating ? (
                                            <span className="flex items-center gap-2">
                                              <Loader2 className="h-4 w-4 animate-spin" /> Salvando...
                                            </span>
                                          ) : (
                                            'Salvar alterações'
                                          )}
                                        </Button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              );
                            })
                          )}
                        </div>
                      )}
                    </CardContent>
                  </Card>

                  <Card>
                    <CardHeader>
                      <CardTitle>🔐 Boas práticas de acesso</CardTitle>
                      <CardDescription>
                        Dicas rápidas para proteger o painel administrativo
                      </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-2 text-sm text-muted-foreground">
                      <ul className="list-disc pl-5 space-y-2">
                        <li>Crie um usuário para cada operador em vez de compartilhar senhas.</li>
                        <li>Use senhas com letras, números e símbolos para aumentar a segurança.</li>
                        <li>Altere a senha do usuário "admin" após o primeiro acesso.</li>
                        <li>Desative sessões antigas usando o botão "Sair" no cabeçalho.</li>
                      </ul>
                    </CardContent>
                  </Card>
                </div>
              </div>
            </TabsContent>
          )}

          {/* Dialog do Editor de Militares */}
          <MilitaryEditor 
            isOpen={militaryEditorOpen}
            onClose={() => {
              setMilitaryEditorOpen(false);
              setEditingMilitary(null);
            }}
            military={editingMilitary as any}
            onSave={handleSaveMilitary}
          />
        </Tabs>
      </div>
    </div>
  );
}

export default Admin;
