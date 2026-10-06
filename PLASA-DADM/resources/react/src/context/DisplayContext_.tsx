import React, { createContext, useContext, useState, ReactNode, useEffect, useRef, useCallback } from "react";
import { resolveBackendUrl } from "@/utils/backend";

export interface Notice {
  id: string;
  title: string;
  content: string;
  priority: "high" | "medium" | "low";
  startDate: Date;
  endDate: Date;
  active: boolean;
  createdAt?: Date;
  updatedAt?: Date;
}

export interface PDFDocument {
  id: string;
  title: string;
  url: string;
  type: "plasa" | "escala" | "cardapio";
  category?: "oficial" | "praca";
  unit?: "EAGM" | "1DN";
  tags?: string[]; // 🏷️ Tags de classificação automática
  active: boolean;
  uploadDate: Date;
}

export interface DocumentViewState {
  zoom?: number;
  scrollTop?: number;
  scrollLeft?: number;
  updatedAt?: string;
}

type DocumentViewStateMap = Record<string, DocumentViewState>;

const clampZoomValue = (value: unknown): number | undefined => {
  const numeric = Number(value);
  if (!Number.isFinite(numeric)) {
    return undefined;
  }

  const clamped = Math.min(Math.max(numeric, 0.5), 3);
  return clamped;
};

const clampScrollValue = (value: unknown): number | undefined => {
  const numeric = Number(value);
  if (!Number.isFinite(numeric)) {
    return undefined;
  }

  return numeric >= 0 ? numeric : 0;
};

const sanitizeDocumentViewStateInput = (value: unknown): DocumentViewState | null => {
  if (!value || typeof value !== "object") {
    return null;
  }

  const record = value as Record<string, unknown>;
  const sanitized: DocumentViewState = {};

  const zoom = clampZoomValue(record.zoom);
  if (typeof zoom === "number") {
    sanitized.zoom = zoom;
  }

  const scrollTop = clampScrollValue(record.scrollTop);
  if (typeof scrollTop === "number") {
    sanitized.scrollTop = scrollTop;
  }

  const scrollLeft = clampScrollValue(record.scrollLeft);
  if (typeof scrollLeft === "number") {
    sanitized.scrollLeft = scrollLeft;
  }

  if (typeof record.updatedAt === "string" && record.updatedAt.trim()) {
    sanitized.updatedAt = record.updatedAt;
  }

  return Object.keys(sanitized).length > 0 ? sanitized : null;
};

const parseDocumentViewStatesPayload = (input: unknown): DocumentViewStateMap => {
  if (!input || typeof input !== "object") {
    return {};
  }

  const entries = Object.entries(input as Record<string, unknown>);
  const result: DocumentViewStateMap = {};

  for (const [rawId, rawValue] of entries) {
    if (typeof rawId !== "string" || !rawId.trim()) {
      continue;
    }

    const sanitizedState = sanitizeDocumentViewStateInput(rawValue);
    if (sanitizedState) {
      result[rawId] = sanitizedState;
    }
  }

  return result;
};

type AddDocumentOptions = {
  persist?: boolean;
};
interface DisplayContextType {
  notices: Notice[];
  plasaDocuments: PDFDocument[];
  escalaDocuments: PDFDocument[];
  cardapioDocuments: PDFDocument[];
  activePlasaDoc: PDFDocument | null;
  activeEscalaDoc: PDFDocument | null;
  activeCardapioDoc: PDFDocument | null;
  currentEscalaIndex: number;
  currentCardapioIndex: number; // ✅ Adicionar
  escalaAlternateInterval: number;
  cardapioAlternateInterval: number;
  scrollSpeed: "slow" | "normal" | "fast";
  autoRestartDelay: number;
  isLoading: boolean;
  isEscalaEditMode: boolean; // ✏️ Modo editor de escala
  isCardapioEditMode: boolean; // ✏️ Modo editor de cardápio
  addNotice: (notice: Omit<Notice, "id" | "createdAt" | "updatedAt">) => Promise<boolean>;
  updateNotice: (notice: Notice) => Promise<boolean>;
  deleteNotice: (id: string) => Promise<boolean>;
  addDocument: (document: Omit<PDFDocument, "id" | "uploadDate">, options?: AddDocumentOptions) => void;
  updateDocument: (document: PDFDocument) => void;
  deleteDocument: (id: string) => void;
  setEscalaAlternateInterval: (interval: number) => void;
  setCardapioAlternateInterval: (interval: number) => void;
  setScrollSpeed: (speed: "slow" | "normal" | "fast") => void;
  setAutoRestartDelay: (delay: number) => void;
  setIsEscalaEditMode: (isEditMode: boolean) => void; // ✏️ Alternar modo editor de escala
  setIsCardapioEditMode: (isEditMode: boolean) => void; // ✏️ Alternar modo editor de cardápio
  refreshNotices: () => Promise<void>;
  refreshDocuments: () => Promise<void>; // 🔄 Atualizar documentos manualmente
  documentViewStates: DocumentViewStateMap;
  updateDocumentViewState: (documentId: string, state: DocumentViewState | null) => void;
  refreshDocumentViewStates: () => Promise<void>;
  handleScrollComplete: () => void;
}

const DisplayContext = createContext<DisplayContextType | undefined>(undefined);

export const useDisplay = () => {
  const context = useContext(DisplayContext);
  if (!context) {
    throw new Error("useDisplay must be used within a DisplayProvider");
  }
  return context;
};

interface DisplayProviderProps {
  children: ReactNode;
}

export const DisplayProvider: React.FC<DisplayProviderProps> = ({ children }) => {
  // Estados
  const [notices, setNotices] = useState<Notice[]>([]);
  const [plasaDocuments, setPlasaDocuments] = useState<PDFDocument[]>([]);
  const [escalaDocuments, setEscalaDocuments] = useState<PDFDocument[]>([]);
  const [cardapioDocuments, setCardapioDocuments] = useState<PDFDocument[]>([]);
  const [currentEscalaIndex, setCurrentEscalaIndex] = useState(0);
  const [currentCardapioIndex, setCurrentCardapioIndex] = useState(0); // ✅ ADICIONAR
  const cardapioTimerRef = useRef<NodeJS.Timeout | null>(null); // ✅ ADICIONAR
  const [escalaAlternateInterval, setEscalaAlternateInterval] = useState(30000);
  const [cardapioAlternateInterval, setCardapioAlternateInterval] = useState(30000);
  const [scrollSpeed, setScrollSpeed] = useState<"slow" | "normal" | "fast">("normal");
  const [autoRestartDelay, setAutoRestartDelay] = useState(3);
  const [isLoading, setIsLoading] = useState(false);
  const [isEscalaEditMode, setIsEscalaEditMode] = useState(false); // ✏️ Modo editor de escala
  const [isCardapioEditMode, setIsCardapioEditMode] = useState(false); // ✏️ Modo editor de cardápio
  const [documentViewStates, setDocumentViewStates] = useState<DocumentViewStateMap>({});

  // Ref para o timer de alternância
  const escalaTimerRef = useRef<NodeJS.Timeout | null>(null);
  const mainDocTimerRef = useRef<NodeJS.Timeout | null>(null);
  const isInitializingRef = useRef(true);
  const [isInitialized, setIsInitialized] = useState(false);
  const refreshDocumentsRef = useRef<(() => Promise<void>) | null>(null);
  
  // Callback para após completar scroll (apenas PLASA agora)
  const handleScrollComplete = () => {
    // Função simplificada - apenas PLASA
    console.log("✅ Scroll completo");
  };

  // CORREÇÃO: Função para obter URL completa do backend - DETECTAR AMBIENTE
  const getBackendUrl = useCallback((path: string): string => resolveBackendUrl(path), []);

  // Função utilitária para tratar tags dos documentos
  const normalizeDocumentTags = (input: {
    tags?: string[];
    type: PDFDocument["type"];
    category?: PDFDocument["category"];
    unit?: PDFDocument["unit"];
  }): string[] => {
    const baseTags = Array.isArray(input.tags)
      ? input.tags
          .map(tag => (typeof tag === "string" ? tag.trim() : ""))
          .filter(tag => tag.length > 0)
      : [];

    const ensureTag = (tag: string) => {
      const normalized = tag.trim();
      if (!normalized) return;
      const alreadyExists = baseTags.some(
        existing => existing.toUpperCase() === normalized.toUpperCase()
      );
      if (!alreadyExists) {
        baseTags.push(normalized);
      }
    };

    if (input.type === "plasa") {
      ensureTag("PLASA");
    }

    if (input.type === "escala") {
      ensureTag("ESCALA");
      if (input.category === "oficial") {
        ensureTag("OFICIAIS");
      } else if (input.category === "praca") {
        ensureTag("PRAÇAS");
      }
    }

    if (input.type === "cardapio") {
      ensureTag("CARDÁPIO");
      if (input.unit === "EAGM") {
        ensureTag("EAGM");
      } else if (input.unit === "1DN") {
        ensureTag("1DN");
      }
    }

    return baseTags;
  };

  const applyDocumentViewStates = useCallback(
    (incoming: DocumentViewStateMap, options?: { replace?: boolean }) => {
      setDocumentViewStates(prev => {
        const shouldReplace = options?.replace ?? false;
        const base: DocumentViewStateMap = shouldReplace ? {} : { ...prev };
        const keys = Object.keys(incoming);

        if (keys.length === 0) {
          return shouldReplace ? base : prev;
        }

        keys.forEach(id => {
          const sanitized = sanitizeDocumentViewStateInput(incoming[id]);
          if (!sanitized) {
            return;
          }

          base[id] = {
            ...(base[id] ?? {}),
            ...sanitized,
          };
        });

        return base;
      });
    },
    []
  );

  const updateDocumentViewState = useCallback(
    (documentId: string, state: DocumentViewState | null) => {
      const normalizedId = typeof documentId === "string" ? documentId.trim() : "";
      if (!normalizedId) {
        return;
      }

      if (!state) {
        setDocumentViewStates(prev => {
          if (!(normalizedId in prev)) {
            return prev;
          }
          const { [normalizedId]: _removed, ...rest } = prev;
          return rest;
        });
        return;
      }

      const sanitized = sanitizeDocumentViewStateInput(state);
      if (!sanitized) {
        setDocumentViewStates(prev => {
          if (!(normalizedId in prev)) {
            return prev;
          }
          const { [normalizedId]: _removed, ...rest } = prev;
          return rest;
        });
        return;
      }

      applyDocumentViewStates({ [normalizedId]: sanitized });
    },
    [applyDocumentViewStates]
  );

  const refreshDocumentViewStates = useCallback(async () => {
    try {
      const response = await fetch(getBackendUrl('/api/documents/view-state'));
      if (!response.ok) {
        const errorText = await response.text().catch(() => '');
        console.warn(`⚠️ Falha ao carregar estado de visualização: ${response.status} ${errorText}`);
        return;
      }

      const payload = await response.json();
      const rawStates = payload && typeof payload === 'object'
        ? (payload.viewStates ?? payload.states ?? payload.data ?? null)
        : null;
      const parsedStates = parseDocumentViewStatesPayload(rawStates);
      applyDocumentViewStates(parsedStates, { replace: true });
    } catch (error) {
      console.warn('⚠️ Erro ao sincronizar estado de visualização dos documentos:', error);
    }
  }, [applyDocumentViewStates, getBackendUrl]);

  const persistDocumentMetadata = async (
    document: Omit<PDFDocument, "id" | "uploadDate"> & { url: string }
  ) => {
    const resolveServerPath = (url: string): string | null => {
      if (!url) return null;
      if (url.startsWith('/uploads/')) return url;
      if (url.startsWith('uploads/')) return `/${url}`;
      if (url.startsWith('http')) {
        try {
          const parsed = new URL(url);
          return parsed.pathname.startsWith('/uploads/')
            ? `${parsed.pathname}${parsed.search}`
            : null;
        } catch (error) {
          console.warn('⚠️ URL inválida ao persistir documento:', error);
          return null;
        }
      }
      return null;
    };

    const serverPath = resolveServerPath(document.url);
    if (!serverPath) {
      return;
    }

    const payload: Record<string, unknown> = {
      title: document.title,
      url: serverPath,
      type: document.type,
      category: document.category ?? null,
      active: document.active,
      tags: normalizeDocumentTags(document),
    };

    if (document.unit) {
      payload.unit = document.unit;
    }

    try {
      const response = await fetch(getBackendUrl('/api/documents'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        console.warn('⚠️ Falha ao persistir metadados do documento:', await response.text().catch(() => response.statusText));
      }
    } catch (error) {
      console.warn('⚠️ Não foi possível persistir documento no servidor:', error);
    }
  };

  const getComparableUrl = (url: string): string => {
    if (!url) return "";

    try {
      const isAbsolute = /^https?:/i.test(url);
      if (isAbsolute) {
        const parsed = new URL(url);
        return `${parsed.pathname}${parsed.search}`;
      }
    } catch {
      // Ignorar erros de URL inválida e continuar normalização
    }

    const normalized = url.startsWith("/") ? url : `/${url}`;
    return normalized.replace(/\\+/g, "/");
  };

  const generateDocumentIdFromUrl = (url: string): string => {
    const comparable = getComparableUrl(url).toLowerCase();
    if (!comparable) {
      return `${Date.now()}-${Math.random().toString(36).slice(2, 11)}`;
    }

    let hash = 0;
    for (let i = 0; i < comparable.length; i++) {
      hash = (hash * 31 + comparable.charCodeAt(i)) >>> 0;
    }

    return `doc-${hash.toString(16)}`;
  };

  const generateUniqueId = (): string => {
    return `${Date.now()}-${Math.random().toString(36).slice(2, 11)}`;
  };

  // Função para normalizar URLs existentes para o ambiente atual
  const normalizeDocumentUrl = (url: string): string => {
    if (!url) return url;
    
    // Se é uma URL local incorreta (localhost), corrigir para o ambiente atual
    if (url.includes('localhost:')) {
      const pathMatch = url.match(/\/uploads\/.*$/);
      if (pathMatch) {
        return getBackendUrl(pathMatch[0]);
      }
    }
    
    return url;
  };

  // CORREÇÃO: Conversão de aviso do servidor para local
  const convertServerNoticeToLocal = (serverNotice: any): Notice => {
    return {
      id: String(serverNotice.id), 
      title: serverNotice.title,
      content: serverNotice.content,
      priority: serverNotice.priority,
      startDate: new Date(serverNotice.startDate),
      endDate: new Date(serverNotice.endDate),
      active: serverNotice.active,
      createdAt: serverNotice.createdAt ? new Date(serverNotice.createdAt) : undefined,
      updatedAt: serverNotice.updatedAt ? new Date(serverNotice.updatedAt) : undefined
    };
  };

// ✅ DEPOIS (completo):
const convertLocalNoticeToServer = (localNotice: Omit<Notice, "id" | "createdAt" | "updatedAt">): any => {

  
  return {
    title: localNotice.title.trim(),
    content: localNotice.content.trim(),
    priority: localNotice.priority,
    startDate: localNotice.startDate.toISOString(),
    endDate: localNotice.endDate.toISOString(),
    active: localNotice.active !== false // Garantir que seja boolean
  };
};



  // CORREÇÃO: Carregar avisos do servidor com melhor tratamento de erro
  const loadNoticesFromServer = async (): Promise<void> => {
    try {

      setIsLoading(true);
      
      const backendUrl = getBackendUrl('/api/notices');

      
      const response = await fetch(backendUrl, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });
      
      if (response.ok) {
        const result = await response.json();

        if (result.success && Array.isArray(result.notices)) {
          const serverNotices = result.notices.map(convertServerNoticeToLocal);
          console.log(`📢 ${serverNotices.length} avisos carregados do servidor`);
          setNotices(serverNotices);
        } else {
          console.warn("⚠️ Resposta inválida do servidor:", result);
          setNotices([]);
        }
      } else {
        const errorText = await response.text();
        console.error(`❌ Erro ao carregar avisos: ${response.status} - ${errorText}`);
        // Manter avisos locais se servidor falhar
      }
    } catch (error) {
      console.warn("⚠️ Falha ao conectar ao servidor de avisos, usando dados locais:", error);
      // Manter avisos locais se servidor falhar
    } finally {
      setIsLoading(false);
    }
  };

  // Refresh avisos (função pública)
  const refreshNotices = async (): Promise<void> => {
    await loadNoticesFromServer();
  };

  // 🔄 Refresh documentos (função pública)
  const refreshDocuments = async (): Promise<void> => {
    console.log('🔄 Atualizando documentos do servidor...');
    await loadDocumentsFromServer();
  };

  useEffect(() => {
    refreshDocumentsRef.current = refreshDocuments;
  }, [refreshDocuments]);

  // CORREÇÃO: Criar aviso no servidor com melhor tratamento de erro
  const addNotice = async (noticeData: Omit<Notice, "id" | "createdAt" | "updatedAt">): Promise<boolean> => {
    try {
      setIsLoading(true);

      const serverData = convertLocalNoticeToServer(noticeData);
      const backendUrl = getBackendUrl('/api/notices');

      const response = await fetch(backendUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(serverData)
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => ({ error: 'Erro desconhecido' }));
        console.error("❌ Erro HTTP ao criar aviso:", response.status, errorData);
        throw new Error(errorData.error || `Erro HTTP: ${response.status}`);
      }

      const result = await response.json();

      if (result.success && result.notice) {
        const newNotice = convertServerNoticeToLocal(result.notice);
        setNotices(prev => [...prev, newNotice]);
        console.log(`✅ Aviso criado no servidor: ${newNotice.id}`);
        return true;
      } else {
        throw new Error(result.error || 'Resposta inválida do servidor');
      }
    } catch (error) {
      console.error("❌ Erro ao criar aviso:", error);
      
      // Fallback: adicionar localmente se servidor falhar
      const localNotice: Notice = {
        ...noticeData,
        id: `local-${generateUniqueId()}`,
        createdAt: new Date(),
        updatedAt: new Date()
      };
      
      setNotices(prev => [...prev, localNotice]);
      console.log("⚠️ Aviso adicionado apenas localmente devido a erro no servidor");
      
      return false;
    } finally {
      setIsLoading(false);
    }
  };

  // CORREÇÃO: Atualizar aviso no servidor
 // CORREÇÃO: Atualizar aviso no servidor
const updateNotice = async (updatedNotice: Notice): Promise<boolean> => {
  try {
    console.log("📝 Atualizando aviso no servidor:", updatedNotice.id);
    setIsLoading(true);
    
    // 🔥 CORREÇÃO: Garantir que ID seja string antes de usar .startsWith()
    const stringId = String(updatedNotice.id);
    
    // Se é um aviso local, não tentar atualizar no servidor
    if (stringId.startsWith('local-')) {
      setNotices(prev => prev.map(notice => 
        notice.id === updatedNotice.id ? updatedNotice : notice
      ));
      console.log("📝 Aviso local atualizado");
      return true;
    }
    
    const serverData = convertLocalNoticeToServer(updatedNotice);
    
    const response = await fetch(getBackendUrl(`/api/notices/${updatedNotice.id}`), {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(serverData)
    });
    
    if (response.ok) {
      const result = await response.json();
      
      if (result.success && result.notice) {
        const updated = convertServerNoticeToLocal(result.notice);
        setNotices(prev => prev.map(notice => 
          notice.id === updated.id ? updated : notice
        ));
        
        console.log(`✅ Aviso atualizado no servidor: ${updated.id}`);
        return true;
      } else {
        throw new Error(result.error || 'Resposta inválida do servidor');
      }
    } else {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.error || `Erro HTTP: ${response.status}`);
    }
  } catch (error) {
    console.error("❌ Erro ao atualizar aviso:", error);
    
    // Fallback: atualizar localmente se servidor falhar
    setNotices(prev => prev.map(notice => 
      notice.id === updatedNotice.id ? updatedNotice : notice
    ));
    console.log("⚠️ Aviso atualizado apenas localmente devido a erro no servidor");
    
    return false;
  } finally {
    setIsLoading(false);
  }
};

  // CORREÇÃO: Deletar aviso do servidor
 // CORREÇÃO: Deletar aviso do servidor
const deleteNotice = async (id: string): Promise<boolean> => {
  try {
    console.log("🗑️ Deletando aviso do servidor:", id);
    setIsLoading(true);
    
    // 🔥 CORREÇÃO: Garantir que ID seja string antes de usar .startsWith()
    const stringId = String(id);
    
    // Se é um aviso local, apenas remover localmente
    if (stringId.startsWith('local-')) {
      setNotices(prev => prev.filter(notice => String(notice.id) !== stringId));
      console.log("🗑️ Aviso local removido");
      return true;
    }
    
    const response = await fetch(getBackendUrl(`/api/notices/${id}`), {
      method: 'DELETE'
    });
    
    if (response.ok) {
      const result = await response.json();
      
      if (result.success) {
        setNotices(prev => prev.filter(notice => String(notice.id) !== stringId));
        
        console.log(`✅ Aviso deletado do servidor: ${id}`);
        return true;
      } else {
        throw new Error(result.error || 'Resposta inválida do servidor');
      }
    } else {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(errorData.error || `Erro HTTP: ${response.status}`);
    }
  } catch (error) {
    console.error("❌ Erro ao deletar aviso:", error);
    
    // Fallback: remover localmente se servidor falhar
    const stringId = String(id);
    setNotices(prev => prev.filter(notice => String(notice.id) !== stringId));
    console.log("⚠️ Aviso removido apenas localmente devido a erro no servidor");
    
    return false;
  } finally {
    setIsLoading(false);
  }
};

  // Função addDocument com persistência opcional
  const addDocument = (
    docData: Omit<PDFDocument, "id" | "uploadDate">,
    options: AddDocumentOptions = {}
  ) => {
    const serverUrl = docData.url;
    const normalizedDocData = {
      ...docData,
      tags: normalizeDocumentTags(docData)
    };

    const comparableUrl = getComparableUrl(serverUrl);

    const upsertDocument = (prev: PDFDocument[], typeLabel: string): PDFDocument[] => {
      const existingIndex = prev.findIndex(doc => getComparableUrl(doc.url) === comparableUrl);
      const existingDoc = existingIndex >= 0 ? prev[existingIndex] : undefined;

      const nextDoc: PDFDocument = {
        ...normalizedDocData,
        id: existingDoc?.id ?? generateDocumentIdFromUrl(comparableUrl || serverUrl),
        url: getBackendUrl(serverUrl),
        uploadDate: new Date()
      };

      console.log(`📄 Atualizando lista ${typeLabel}:`, {
        id: nextDoc.id,
        title: nextDoc.title,
        url: nextDoc.url
      });

      if (existingIndex >= 0) {
        const updated = [...prev];
        updated[existingIndex] = nextDoc;
        return updated;
      }

      return [...prev, nextDoc];
    };

    if (docData.type === "plasa") {
      setPlasaDocuments(prev => upsertDocument(prev, "PLASA"));
    } else if (docData.type === "cardapio") {
      setCardapioDocuments(prev => upsertDocument(prev, "CARDÁPIO"));
    } else {
      setEscalaDocuments(prev => {
        const updated = upsertDocument(prev, "ESCALA");
        const activeEscalas = updated.filter(doc => doc.active);
        if (activeEscalas.length === 1) {
          setCurrentEscalaIndex(0);
        } else if (activeEscalas.length > 0 && currentEscalaIndex >= activeEscalas.length) {
          setCurrentEscalaIndex(0);
        }
        return updated;
      });
    }

    if (options.persist ?? true) {
      void persistDocumentMetadata({ ...normalizedDocData, url: serverUrl });
    }
  };

  const updateDocument = (updatedDoc: PDFDocument) => {
    console.log("📝 Atualizando documento:", updatedDoc.title);
    const normalizedDoc = {
      ...updatedDoc,
      tags: normalizeDocumentTags(updatedDoc)
    };

    if (updatedDoc.type === "plasa") {
      setPlasaDocuments(prev => prev.map(doc =>
        doc.id === updatedDoc.id ? normalizedDoc : doc
      ));
    } else if (updatedDoc.type === "escala") {
      setEscalaDocuments(prev => prev.map(doc =>
        doc.id === updatedDoc.id ? normalizedDoc : doc
      ));
    } else if (updatedDoc.type === "cardapio") {
      setCardapioDocuments(prev => prev.map(doc =>
        doc.id === updatedDoc.id ? normalizedDoc : doc
      ));
    }
  };

const deleteDocument = async (id: string) => {
    console.log("🗑️ Removendo documento:", id);
    
    // Encontrar o documento para obter o filename
    const allDocs = [...plasaDocuments, ...escalaDocuments, ...cardapioDocuments];
    const docToDelete = allDocs.find(doc => doc.id === id);
    
    if (docToDelete && docToDelete.url.includes('/uploads/')) {
      try {
        // Extrair o caminho completo após /uploads/ (inclui subpasta + arquivo)
        let filepath = '';
        
        // Tratar diferentes formatos de URL
        if (docToDelete.url.includes('/uploads/')) {
          const parts = docToDelete.url.split('/uploads/');
          filepath = parts[1]; // Pega tudo após /uploads/, ex: "plasa/documento.pdf"
        }
        
        // Remover query strings se houver
        filepath = filepath.split('?')[0];
        
        console.log("🗑️ Deletando arquivo do servidor:", filepath);
        console.log("🗑️ URL original:", docToDelete.url);
        
        // Enviar o caminho completo para o backend
        const response = await fetch(getBackendUrl(`/api/delete-pdf/${encodeURIComponent(filepath)}`), {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
          },
        });
        
        const result = await response.json();
        
        if (result.success) {
          console.log("✅ Arquivo deletado do servidor com sucesso");
        } else {
          console.warn("⚠️ Falha ao deletar arquivo do servidor:", result.error);
        }
      } catch (error) {
        console.error("❌ Erro ao deletar arquivo do servidor:", error);
      }
    }
    
    // Remover da lista local independentemente do resultado do servidor
    setPlasaDocuments(prev => prev.filter(doc => doc.id !== id));
    setCardapioDocuments(prev => prev.filter(doc => doc.id !== id));
    setEscalaDocuments(prev => {
      const newList = prev.filter(doc => doc.id !== id);
      const activeEscalas = newList.filter(doc => doc.active);
      if (activeEscalas.length === 0) {
        setCurrentEscalaIndex(0);
      } else if (currentEscalaIndex >= activeEscalas.length) {
        setCurrentEscalaIndex(0);
      }
      return newList;
    });
  };

  // Computed values com alternância automática para escalas  
  const activePlasaDoc = plasaDocuments.find(doc => doc.active) || null;

  
  const activeEscalaDocuments = escalaDocuments.filter(doc => doc.active);
  const activeEscalaDoc = activeEscalaDocuments.length > 0 
    ? activeEscalaDocuments[currentEscalaIndex % activeEscalaDocuments.length] 
    : null;

const totalCardapioDocs = cardapioDocuments.length;
const activeCardapioDoc = totalCardapioDocs > 0
  ? cardapioDocuments[currentCardapioIndex % totalCardapioDocs]
  : null;
  

  // Effect para alternar escalas automaticamente
  useEffect(() => {
    if (escalaTimerRef.current) {
      clearInterval(escalaTimerRef.current);
      escalaTimerRef.current = null;
    }

    // ✏️ NOVO: Não iniciar timer se estiver em modo editor
    if (activeEscalaDocuments.length > 1 && !isEscalaEditMode) {


      escalaTimerRef.current = setInterval(() => {
        setCurrentEscalaIndex(prevIndex => {
          const nextIndex = (prevIndex + 1) % activeEscalaDocuments.length;

          return nextIndex;
        });
      }, escalaAlternateInterval);
    } else if (activeEscalaDocuments.length === 1) {
      setCurrentEscalaIndex(0);

    } else {

    }

    return () => {
      if (escalaTimerRef.current) {
        clearInterval(escalaTimerRef.current);
        escalaTimerRef.current = null;
      }
    };
  }, [activeEscalaDocuments.length, escalaAlternateInterval, isEscalaEditMode]);
// ✅ ADICIONAR: Effect para alternar cardápios automaticamente
useEffect(() => {
  if (cardapioTimerRef.current) {
    clearInterval(cardapioTimerRef.current);
    cardapioTimerRef.current = null;
  }

  // ✏️ NOVO: Não iniciar timer se estiver em modo editor
  if (cardapioDocuments.length > 1 && !isCardapioEditMode) {
    console.log(`🍽️ Iniciando alternância de ${cardapioDocuments.length} cardápios`);

    cardapioTimerRef.current = setInterval(() => {
      setCurrentCardapioIndex(prevIndex => {
        const nextIndex = (prevIndex + 1) % cardapioDocuments.length;
        const nextCardapio = cardapioDocuments[nextIndex];
        console.log(`🍽️ Alternando para cardápio ${nextIndex + 1}/${cardapioDocuments.length}: ${nextCardapio?.unit || 'N/A'}`);
        return nextIndex;
      });
    }, cardapioAlternateInterval);
  } else if (cardapioDocuments.length === 1) {
    console.log(`🍽️ Apenas 1 cardápio ativo, mantendo fixo`);
    setCurrentCardapioIndex(0);
  } else {
    console.log(`⚠️ Nenhum cardápio ativo`);
  }

  return () => {
    if (cardapioTimerRef.current) {
      clearInterval(cardapioTimerRef.current);
      cardapioTimerRef.current = null;
    }
  };
}, [cardapioDocuments, cardapioAlternateInterval, isCardapioEditMode]);

// ✅ ADICIONAR: Effect para resetar índice de cardápios
useEffect(() => {
  if (cardapioDocuments.length === 0) {
    setCurrentCardapioIndex(0);
  } else if (currentCardapioIndex >= cardapioDocuments.length) {
    setCurrentCardapioIndex(0);
  }
}, [cardapioDocuments.length, currentCardapioIndex]);
  // Log das mudanças importantes
  useEffect(() => {
    if (!isInitializingRef.current) {

    }
  }, [plasaDocuments, escalaDocuments, activePlasaDoc, activeEscalaDoc, currentEscalaIndex, notices]);

  // CORREÇÃO: Persistir apenas documentos no localStorage (não avisos)
  useEffect(() => {
    if (isInitializingRef.current) {
      return;
    }

    try {
      const contextData = {
        plasaDocuments: plasaDocuments.map(doc => ({
          ...doc,
          tags: normalizeDocumentTags(doc),
          uploadDate: doc.uploadDate.toISOString()
        })),
        escalaDocuments: escalaDocuments.map(doc => ({
          ...doc,
          tags: normalizeDocumentTags(doc),
          uploadDate: doc.uploadDate.toISOString()
        })),
        cardapioDocuments: cardapioDocuments.map(doc => ({
          ...doc,
          tags: normalizeDocumentTags(doc),
          uploadDate: doc.uploadDate.toISOString()
        })),
        currentEscalaIndex,
        currentCardapioIndex,
        escalaAlternateInterval,
        cardapioAlternateInterval,
        documentAlternateInterval: escalaAlternateInterval, // 🔙 Compatibilidade com versões anteriores
        scrollSpeed,
        autoRestartDelay,
        lastUpdate: new Date().toISOString(),
        version: '3.3' // Removido polling (usando apenas SSE)
      };

      localStorage.setItem('display-context', JSON.stringify(contextData, null, 2));


      
    } catch (error) {
      console.error("❌ Erro ao salvar contexto:", error);
    }
  }, [
    plasaDocuments,
    escalaDocuments,
    cardapioDocuments,
    currentEscalaIndex,
    currentCardapioIndex,
    escalaAlternateInterval,
    cardapioAlternateInterval,
    scrollSpeed,
    autoRestartDelay
  ]);

  // Função auxiliar para determinar categoria
  const determineCategory = (filename: string): "oficial" | "praca" | undefined => {
    const lowerFilename = filename.toLowerCase();
    const normalizedFilename = lowerFilename
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

    if (normalizedFilename.includes('oficial')) return 'oficial';
    if (normalizedFilename.includes('praca')) return 'praca';
    return undefined;
  };

  const normalizeUnitValue = (value: unknown): PDFDocument["unit"] | undefined => {
    if (typeof value !== "string") {
      return undefined;
    }

    const normalized = value.trim().toUpperCase();
    if (!normalized) {
      return undefined;
    }

    if (normalized === "EAGM") {
      return "EAGM";
    }

    if (normalized === "1DN") {
      return "1DN";
    }

    const compact = normalized.replace(/[^A-Z0-9]/g, "");
    if (compact === "EAGM") {
      return "EAGM";
    }

    if (compact === "1DN" || compact === "DN1") {
      return "1DN";
    }

    return undefined;
  };

  const detectUnitFromText = (text?: string | null): PDFDocument["unit"] | undefined => {
    if (!text) {
      return undefined;
    }

    const normalized = text
      .toUpperCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

    const compact = normalized.replace(/[^A-Z0-9]/g, "");

    if (compact.includes("EAGM")) {
      return "EAGM";
    }

    if (compact.includes("1DN") || compact.includes("DN1")) {
      return "1DN";
    }

    return undefined;
  };

  const deriveUnitFromTags = (tags: unknown): PDFDocument["unit"] | undefined => {
    if (!Array.isArray(tags)) {
      return undefined;
    }

    for (const rawTag of tags) {
      const tagUnit = normalizeUnitValue(rawTag);
      if (tagUnit) {
        return tagUnit;
      }
    }

    return undefined;
  };

  // Função para carregar documentos do servidor
  const loadDocumentsFromServer = async () => {
    const existingDocsMap = new Map<string, PDFDocument>();
    [...plasaDocuments, ...escalaDocuments, ...cardapioDocuments].forEach(doc => {
      existingDocsMap.set(getComparableUrl(doc.url), doc);
    });

    const sortDocuments = (docs: PDFDocument[]) =>
      [...docs].sort((a, b) => b.uploadDate.getTime() - a.uploadDate.getTime());

    const dedupeDocuments = (docs: PDFDocument[]) => {
      const deduped = new Map<string, PDFDocument>();

      docs.forEach(doc => {
        const key = getComparableUrl(doc.url);
        if (!key) {
          return;
        }

        const existing = deduped.get(key);
        if (!existing || doc.uploadDate.getTime() >= existing.uploadDate.getTime()) {
          deduped.set(key, doc);
        }
      });

      return Array.from(deduped.values());
    };

    const applyDocumentsFromSource = (docs: PDFDocument[], source: 'database' | 'fallback') => {
      const dedupedDocs = dedupeDocuments(docs);

      const plasa = dedupedDocs.filter(doc => doc.type === 'plasa');
      const escala = dedupedDocs.filter(doc => doc.type === 'escala');
      const cardapio = dedupedDocs.filter(doc => doc.type === 'cardapio');

      setPlasaDocuments(sortDocuments(plasa));
      setEscalaDocuments(sortDocuments(escala));
      setCardapioDocuments(sortDocuments(cardapio));

      console.info(
        `📥 Carregados ${dedupedDocs.length} documentos (${plasa.length} PLASA, ${escala.length} Escala, ${cardapio.length} Cardápio) via ${source === 'database' ? '/api/documents' : '/api/list-pdfs'}`
      );

      return dedupedDocs.length > 0;
    };

    const processServerDocument = (serverDoc: any): PDFDocument | null => {
      if (!serverDoc) return null;

      const rawUrl = typeof serverDoc.url === 'string' ? serverDoc.url : '';
      if (!rawUrl) return null;

      const fullUrl = getBackendUrl(rawUrl);
      const comparableUrl = getComparableUrl(rawUrl) || getComparableUrl(fullUrl);
      if (!comparableUrl) return null;

      const allowedTypes: PDFDocument["type"][] = ['plasa', 'escala', 'cardapio'];
      const docType = typeof serverDoc.type === 'string' ? serverDoc.type.toLowerCase() : 'escala';
      const safeType = allowedTypes.includes(docType as PDFDocument["type"])
        ? (docType as PDFDocument["type"])
        : 'escala';

      const existingDoc = existingDocsMap.get(comparableUrl);
      const normalizedCategory = typeof serverDoc.category === 'string'
        ? determineCategory(serverDoc.category)
        : undefined;

      const rawCategory = normalizedCategory
        ?? (safeType === 'escala'
          ? determineCategory(String(serverDoc.filename || serverDoc.title || ''))
          : undefined)
        ?? (existingDoc?.category as PDFDocument['category'] | undefined);

      let unit: PDFDocument["unit"] | undefined;
      if (safeType === 'cardapio') {
        const classificationUnit = serverDoc.classification && typeof serverDoc.classification === 'object'
          ? normalizeUnitValue((serverDoc.classification as Record<string, unknown>)['unit'])
          : undefined;

        const tagsUnit = deriveUnitFromTags(serverDoc.tags)
          ?? (serverDoc.classification && typeof serverDoc.classification === 'object'
            ? deriveUnitFromTags((serverDoc.classification as Record<string, unknown>)['tags'])
            : undefined);

        const textUnit = detectUnitFromText(typeof serverDoc.title === 'string' ? serverDoc.title : undefined)
          ?? detectUnitFromText(typeof serverDoc.filename === 'string' ? serverDoc.filename : undefined)
          ?? detectUnitFromText(typeof serverDoc.originalname === 'string' ? serverDoc.originalname : undefined)
          ?? detectUnitFromText(rawUrl)
          ?? detectUnitFromText(comparableUrl);

        unit = normalizeUnitValue(serverDoc.unit)
          ?? classificationUnit
          ?? tagsUnit
          ?? textUnit
          ?? (existingDoc?.unit);
      }

      const docTags = normalizeDocumentTags({
        tags: Array.isArray(serverDoc.tags)
          ? [...serverDoc.tags]
          : serverDoc.tags
            ? [String(serverDoc.tags)]
            : [],
        type: safeType,
        category: rawCategory as PDFDocument['category'],
        unit
      });

      const timestampSource = serverDoc.uploadDate || serverDoc.created || serverDoc.createdAt;
      const parsedDate = timestampSource ? new Date(timestampSource) : new Date();
      const typeNames = {
        plasa: 'PLASA',
        escala: 'Escala',
        cardapio: 'Cardápio'
      } satisfies Record<PDFDocument['type'], string>;

      const generatedTitle = `${typeNames[safeType]} - ${parsedDate.toLocaleDateString('pt-BR')}`;
      const finalTitle = typeof serverDoc.title === 'string' && serverDoc.title.trim().length > 0
        ? serverDoc.title
        : generatedTitle;

      const activeState = typeof serverDoc.active === 'boolean'
        ? serverDoc.active
        : existingDoc?.active ?? true;

      const doc: PDFDocument = {
        id: existingDoc?.id ?? generateDocumentIdFromUrl(comparableUrl),
        title: finalTitle,
        url: fullUrl,
        type: safeType,
        category: safeType === 'escala' ? (rawCategory as PDFDocument['category']) : undefined,
        tags: docTags,
        unit,
        active: activeState,
        uploadDate: parsedDate
      };

      return doc;
    };

    try {
      const response = await fetch(getBackendUrl('/api/documents'));
      if (response.ok) {
        const documents = await response.json();
        if (Array.isArray(documents)) {
          const parsedDocs = documents
            .map(processServerDocument)
            .filter((doc): doc is PDFDocument => doc !== null);

          if (applyDocumentsFromSource(parsedDocs, 'database')) {
            await refreshDocumentViewStates();
            return;
          }

          console.info('ℹ️ Nenhum documento válido retornado do banco, utilizando fallback /api/list-pdfs');
        }
      }
    } catch (error) {
      console.warn('⚠️ Falha ao carregar documentos do banco:', error);
    }

    try {
      const response = await fetch(getBackendUrl('/api/list-pdfs'));
      if (response.ok) {
        const result = await response.json();
        if (result.documents && Array.isArray(result.documents)) {
          const parsedDocs = result.documents
            .map(processServerDocument)
            .filter((doc: PDFDocument | null): doc is PDFDocument => doc !== null);

          if (!applyDocumentsFromSource(parsedDocs, 'fallback')) {
            setPlasaDocuments([]);
            setEscalaDocuments([]);
            setCardapioDocuments([]);
          }
        } else {
          setPlasaDocuments([]);
          setEscalaDocuments([]);
          setCardapioDocuments([]);
        }
      }
    } catch (error) {
      console.warn("⚠️ Falha ao carregar documentos do servidor, usando dados locais:", error);
    }

    await refreshDocumentViewStates();
  };

  // CORREÇÃO: Inicialização robusta com fallback para erros de documento
  useEffect(() => {
    const initializeContext = async () => {

      
      try {
        // Carregar configurações do localStorage
        const saved = localStorage.getItem('display-context');
        if (saved) {
          try {
            const data = JSON.parse(saved);


            // Carregar documentos PLASA com correção de URL
            if (data.plasaDocuments && Array.isArray(data.plasaDocuments)) {
              const validPlasaDocs = data.plasaDocuments
                .filter((doc: any) => doc && doc.id && doc.title && doc.url)
                .map((doc: any) => {
                  return {
                    ...doc,
                    url: normalizeDocumentUrl(doc.url),
                    tags: normalizeDocumentTags(doc),
                    uploadDate: new Date(doc.uploadDate),
                    active: doc.active !== false
                  };
                });

              if (validPlasaDocs.length > 0) {
                setPlasaDocuments(validPlasaDocs);

              }
            }


            // Carregar documentos Escala com correção de URL
            if (data.escalaDocuments && Array.isArray(data.escalaDocuments)) {
              const validEscalaDocs = data.escalaDocuments
                .filter((doc: any) => doc && doc.id && doc.title && doc.url)
                .map((doc: any) => {
                  return {
                    ...doc,
                    url: normalizeDocumentUrl(doc.url),
                    tags: normalizeDocumentTags(doc),
                    uploadDate: new Date(doc.uploadDate),
                    active: doc.active !== false
                  };
                });

              if (validEscalaDocs.length > 0) {
                setEscalaDocuments(validEscalaDocs);

              }
            }

            // Carregar documentos de Cardápio com correção de URL
            if (data.cardapioDocuments && Array.isArray(data.cardapioDocuments)) {
              const validCardapioDocs = data.cardapioDocuments
                .filter((doc: any) => doc && doc.id && doc.title && doc.url)
                .map((doc: any) => {
                  return {
                    ...doc,
                    url: normalizeDocumentUrl(doc.url),
                    tags: normalizeDocumentTags(doc),
                    uploadDate: new Date(doc.uploadDate),
                    active: doc.active !== false
                  };
                });

              if (validCardapioDocs.length > 0) {
                setCardapioDocuments(validCardapioDocs);

              }
            }

            // Carregar configurações
            if (typeof data.currentEscalaIndex === 'number') {
              setCurrentEscalaIndex(data.currentEscalaIndex);
            }
            if (typeof data.currentCardapioIndex === 'number') {
              setCurrentCardapioIndex(data.currentCardapioIndex);
            }
            if (data.escalaAlternateInterval) {
              setEscalaAlternateInterval(data.escalaAlternateInterval);
            }
            if (data.cardapioAlternateInterval) {
              setCardapioAlternateInterval(data.cardapioAlternateInterval);
            }
            if (data.documentAlternateInterval && !data.escalaAlternateInterval && !data.cardapioAlternateInterval) {
              setEscalaAlternateInterval(data.documentAlternateInterval);
              setCardapioAlternateInterval(data.documentAlternateInterval);
            }
            if (data.scrollSpeed) setScrollSpeed(data.scrollSpeed);
            if (data.autoRestartDelay) setAutoRestartDelay(data.autoRestartDelay);
          } catch (parseError) {
            console.warn("⚠️ Erro ao processar localStorage:", parseError);
            localStorage.removeItem('display-context');
          }
        }

        // Carregar avisos do servidor
        try {
          await loadNoticesFromServer();
        } catch (noticeError) {
          console.warn("⚠️ Falha ao carregar avisos do servidor:", noticeError);
        }
        
        // Carregar documentos do servidor (não bloqueante)
        setTimeout(() => {
          loadDocumentsFromServer().catch((error) => {
            console.warn("⚠️ Falha ao carregar documentos:", error);
          });
        }, 1000);
        
      } catch (error) {
        console.error("❌ Erro na inicialização:", error);
        
        // Fallback: tentar carregar apenas avisos
        try {
          await loadNoticesFromServer();
        } catch (fallbackError) {
          console.error("❌ Falha total na inicialização:", fallbackError);
        }
      } finally {
        setTimeout(() => {
          isInitializingRef.current = false;
          setIsInitialized(true);
        }, 2000);
      }
    };
    
    initializeContext();
  }, []);

  // Effect para resetar índice quando não há escalas ativas
  useEffect(() => {
    if (activeEscalaDocuments.length === 0) {
      setCurrentEscalaIndex(0);
    } else if (currentEscalaIndex >= activeEscalaDocuments.length) {
      setCurrentEscalaIndex(0);
    }
  }, [activeEscalaDocuments.length, currentEscalaIndex]);

  // 📡 Effect para SSE (Server-Sent Events) de documentos em tempo real
  // Substitui o polling periódico por atualizações em tempo real mais eficientes
  useEffect(() => {
    if (!isInitialized) {
      return;
    }

    let eventSource: EventSource | null = null;
    let reconnectTimer: NodeJS.Timeout | null = null;
    const RECONNECT_DELAY = 5000; // 5 segundos

    const connectSSE = () => {
      try {
        const sseUrl = getBackendUrl('/api/documents/stream');
        console.log('📡 Conectando ao SSE de documentos:', sseUrl);

        eventSource = new EventSource(sseUrl);

        eventSource.onopen = () => {
          console.log('✅ Conexão SSE de documentos estabelecida');
        };

        eventSource.onmessage = (event) => {
          try {
            const data = JSON.parse(event.data);
            console.log('📡 Evento SSE de documentos recebido:', data.type);

            if (data.type === 'snapshot') {
              console.log('📊 SSE Snapshot - viewStates brutos recebidos:', data.viewStates);
              console.log('📊 SSE Snapshot - tipo de viewStates:', typeof data.viewStates);
              console.log('📊 SSE Snapshot - keys de viewStates:', data.viewStates ? Object.keys(data.viewStates) : 'null/undefined');

              const parsedStates = parseDocumentViewStatesPayload(data.viewStates ?? {});
              console.log('📊 SSE Snapshot - viewStates parseados:', parsedStates);
              console.log('📊 SSE Snapshot - total de estados:', Object.keys(parsedStates).length);

              applyDocumentViewStates(parsedStates, { replace: true });
              console.log('✅ SSE Snapshot - viewStates aplicados ao contexto');

              const refreshPromise = refreshDocumentsRef.current?.();
              if (refreshPromise) {
                refreshPromise.catch(err => {
                  console.warn('⚠️ Erro ao atualizar documentos via SSE:', err);
                });
              }
              return;
            }

            if (data.viewStates) {
              const parsedStates = parseDocumentViewStatesPayload(data.viewStates);
              if (Object.keys(parsedStates).length > 0) {
                applyDocumentViewStates(parsedStates);
              }
            }

            if (data.type === 'update') {
              const refreshPromise = refreshDocumentsRef.current?.();
              if (refreshPromise) {
                refreshPromise.catch(err => {
                  console.warn('⚠️ Erro ao atualizar documentos via SSE:', err);
                });
              }
            }
          } catch (error) {
            console.error('❌ Erro ao processar evento SSE de documentos:', error);
          }
        };

        eventSource.onerror = (error) => {
          console.warn('⚠️ Erro na conexão SSE de documentos, tentando reconectar...', error);
          eventSource?.close();
          eventSource = null;

          // Agendar reconexão
          if (!reconnectTimer) {
            reconnectTimer = setTimeout(() => {
              reconnectTimer = null;
              connectSSE();
            }, RECONNECT_DELAY);
          }
        };
      } catch (error) {
        console.error('❌ Erro ao conectar SSE de documentos:', error);
      }
    };

    // Iniciar conexão SSE
    connectSSE();

    // Cleanup
    return () => {
      if (reconnectTimer) {
        clearTimeout(reconnectTimer);
        reconnectTimer = null;
      }
      if (eventSource) {
        eventSource.close();
        eventSource = null;
      }
    };
  }, [applyDocumentViewStates, getBackendUrl, isInitialized]);


const value: DisplayContextType = {
  notices,
  plasaDocuments,
  escalaDocuments,
  cardapioDocuments,
  activePlasaDoc,
  activeEscalaDoc,
  activeCardapioDoc, // ✅ Agora está definido acima
  currentEscalaIndex,
  currentCardapioIndex, // ✅ Adicionar esta propriedade
  escalaAlternateInterval,
  cardapioAlternateInterval,
  scrollSpeed,
  autoRestartDelay,
  isLoading,
  isEscalaEditMode, // ✏️ Modo editor de escala
  isCardapioEditMode, // ✏️ Modo editor de cardápio
  addNotice,
  updateNotice,
  deleteNotice,
  addDocument,
  updateDocument,
  deleteDocument,
  setEscalaAlternateInterval,
  setCardapioAlternateInterval,
  setScrollSpeed,
  setAutoRestartDelay,
  setIsEscalaEditMode, // ✏️ Alternar modo editor de escala
  setIsCardapioEditMode, // ✏️ Alternar modo editor de cardápio
  refreshNotices,
  refreshDocuments, // 🔄 Atualizar documentos manualmente
  documentViewStates,
  updateDocumentViewState,
  refreshDocumentViewStates,
  handleScrollComplete,
};

  

  return (
    <DisplayContext.Provider value={value}>
      {children}
    </DisplayContext.Provider>
  );
};