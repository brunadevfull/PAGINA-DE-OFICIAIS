import { useState, useEffect } from 'react';
import { Users, AlertCircle, RefreshCw, Clock } from 'lucide-react';
import { MilitaryInsignia } from './MilitaryInsignia';
import { OFFICERS_LIST, MASTERS_LIST } from '../../../shared/officersData';
import { resolveBackendUrl } from '@/utils/backend';

interface DutyOfficers {
  id: number;
  officerName: string;
  officerRank?: string | null;
  masterName: string;
  masterRank?: string | null;
  updatedAt: string;
}

export const DutyOfficersDisplay = () => {
  const [officers, setOfficers] = useState<DutyOfficers | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Função para converter nomes extensos para siglas
  const convertToAbbreviation = (fullName: string): string => {
    if (!fullName) return '';
    
    // Mapeamento de nomes extensos para siglas
    const rankMapping = {
      'Capitão-de-Mar-e-Guerra': 'CMG',
      'Capitão-de-Fragata': 'CF', 
      'Capitão-de-Corveta': 'CC',
      'Capitão-Tenente': 'CT',
      'Primeiro-Tenente': '1T',
      'Segundo-Tenente': '2T',
      'Primeiro-Sargento': '1SG',
      'Segundo-Sargento': '2SG',
      'Terceiro-Sargento': '3SG'
    };
    
    let convertedName = fullName;
    
    // Substituir nomes extensos por siglas
    for (const [fullRank, abbrev] of Object.entries(rankMapping)) {
      convertedName = convertedName.replace(fullRank, abbrev);
    }
    
    return convertedName;
  };

  // Função para extrair dados do militar do nome completo  
  const extractMilitaryData = (fullName: string, type: 'officer' | 'master') => {
    if (!fullName) return null;

    
    // Primeiro: Verificar se já está no formato correto (CT (IM) YAGO)
    const regexAlreadyFormatted = /^([A-Z0-9]+)\s*\(([^)]+)\)\s+(.+)$/;
    const matchFormatted = fullName.match(regexAlreadyFormatted);
    
    if (matchFormatted) {
      const [, rank, specialty, name] = matchFormatted;
      console.log('🎖️ Já formatado corretamente:', { rank: rank.trim(), specialty: specialty.trim(), name: name.trim() });
      return {
        rank: rank.trim(),
        specialty: specialty.trim(),
        name: name.trim(),
        fullName: fullName
      };
    }
    
    // Segundo: Converter de formato extenso e extrair nome para buscar na base
    const convertedName = convertToAbbreviation(fullName);
    
    // Extrair apenas o nome do militar (última palavra ou palavras)
    const nameParts = convertedName.trim().split(' ');
    let militaryName = '';
    
    // Se tem graduação no início, pegar o resto como nome
    if (nameParts[0].match(/^(CT|CF|CC|1T|2T|CMG|1SG|2SG|3SG)$/)) {
      militaryName = nameParts.slice(1).join(' ');
    } else {
      // Caso não tenha graduação reconhecida, usar o nome todo
      militaryName = convertedName;
    }
    
    
    // Buscar dados completos nas listas
    const militaryList = type === 'officer' ? OFFICERS_LIST : MASTERS_LIST;
    const militaryData = militaryList.find(m => 
      m.name === militaryName || 
      m.name.includes(militaryName) || 
      militaryName.includes(m.name)
    );
    
    if (militaryData) {
      const rank = militaryData.rank.toUpperCase();
      const specialty = militaryData.specialty;
      const displayName = `${rank}${specialty ? ` (${specialty})` : ''} ${militaryData.name}`;
      
      console.log('🎖️ Dados encontrados na base:', { 
        rank, 
        specialty, 
        name: militaryData.name, 
        displayName 
      });
      
      return {
        rank: rank,
        specialty: specialty || null,
        name: militaryData.name,
        fullName: displayName
      };
    }
    
    console.log('🎖️ Militar não encontrado na base, usando conversão simples');
    
    // Fallback: usar conversão simples se não encontrar na base
    const regexNoSpecialty = /^([A-Z0-9]+)\s+([A-Z\s]+)$/;
    const matchNoSpecialty = convertedName.match(regexNoSpecialty);
    
    if (matchNoSpecialty) {
      const [, rank, name] = matchNoSpecialty;
      return {
        rank: rank.trim(),
        specialty: null,
        name: name.trim(),
        fullName: convertedName
      };
    }
    
    return {
      rank: '',
      specialty: null,
      name: convertedName,
      fullName: convertedName
    };
  };

  const loadOfficers = async () => {
    setLoading(true);
    setError(null);

    try {
      const url = resolveBackendUrl('/api/duty-officers');
      console.log('👮 Carregando oficiais de serviço...', url);
      
      const response = await fetch(url);
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const data = await response.json();
      
      if (data.success) {
        setOfficers(data.officers);
        console.log('👮 Oficiais carregados:', data.officers);
      } else {
        throw new Error(data.error || 'Erro desconhecido');
      }
    } catch (err) {
      const errorMsg = err instanceof Error ? err.message : 'Erro ao carregar oficiais';
      setError(errorMsg);
      console.error('❌ Erro ao carregar oficiais:', err);
    } finally {
      setLoading(false);
    }
  };

  // Carregar dados iniciais
  useEffect(() => {
    loadOfficers();
  }, []);

  useEffect(() => {
    const url = resolveBackendUrl('/api/duty-officers/stream');
    const eventSource = new EventSource(url);

    eventSource.onmessage = (event) => {
      try {
        const payload = JSON.parse(event.data) as {
          type?: 'snapshot' | 'update';
          officers?: DutyOfficers | null;
          timestamp?: string;
        };

        if ('officers' in payload) {
          setOfficers(payload.officers ?? null);
          setLoading(false);
          setError(null);
          console.log('📡 Atualização SSE de oficiais recebida:', payload);
        }
      } catch (err) {
        console.error('❌ Erro ao processar evento SSE de oficiais:', err);
      }
    };

    eventSource.onerror = (event) => {
      console.error('⚠️ Erro no stream SSE de oficiais:', event);
      void loadOfficers();
    };

    return () => {
      eventSource.close();
    };
  }, []);

  // Auto-refresh a cada 5 minutos
  useEffect(() => {
    const interval = setInterval(() => {
      loadOfficers();
    }, 5 * 60 * 1000); // 5 minutos

    return () => clearInterval(interval);
  }, []);

  if (loading) {
    return (
      <div className="flex items-center gap-2 text-white/80">
        <Users className="w-4 h-4" />
        <span className="text-sm">Carregando oficiais...</span>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex items-center gap-2 text-white/80">
        <AlertCircle className="w-4 h-4" />
        <span className="text-sm">Oficiais indisponíveis</span>
        <button 
          onClick={loadOfficers}
          className="p-1 hover:bg-white/10 rounded"
          disabled={loading}
        >
          <RefreshCw className={`w-3 h-3 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>
    );
  }

  if (!officers || (!officers.officerName && !officers.masterName)) {
    return (
      <div className="flex items-center gap-2 text-white/80">
        <Users className="w-4 h-4" />
        <span className="text-sm">Oficiais não definidos</span>
        <button 
          onClick={loadOfficers}
          className="p-1 hover:bg-white/10 rounded"
          title="Atualizar"
        >
          <RefreshCw className="w-3 h-3" />
        </button>
      </div>
    );
  }

  return (
    <div className="flex items-center gap-3 bg-gradient-to-r from-blue-800/20 to-blue-900/20 backdrop-blur-sm rounded-lg border border-blue-400/20 px-4 py-2 shadow-lg">
      {/* Ícone */}
      <div className="p-1.5 bg-blue-500/20 rounded-full">
        <Users className="w-4 h-4 text-blue-300" />
      </div>
      
      {/* Informações dos oficiais */}
      <div className="flex flex-col gap-1">
        {officers.officerName && (
          <div className="flex items-center gap-2">
            <span className="text-xs text-blue-300 font-medium">Oficial:</span>
            {(() => {
              const officerFullName = [officers.officerRank, officers.officerName]
                .filter(Boolean)
                .join(' ')
                .trim();
              const officerLabel = officerFullName || officers.officerName;
              const officerData = extractMilitaryData(officerLabel, 'officer');
              return officerData ? (
                <div className="flex items-center gap-2">
                  <MilitaryInsignia
                    rank={officerData.rank}
                    specialty={officerData.specialty}
                    size="sm"
                  />
                  <span className="text-white font-semibold text-sm">
                    {officerData.fullName}
                  </span>
                </div>
              ) : (
                <span className="text-white font-semibold text-sm">
                  {officerLabel}
                </span>
              );
            })()}
          </div>
        )}

        {officers.masterName && (
          <div className="flex items-center gap-2">
            <span className="text-xs text-blue-300 font-medium">Contramestre:</span>
            {(() => {
              const masterFullName = [officers.masterRank, officers.masterName]
                .filter(Boolean)
                .join(' ')
                .trim();
              const masterLabel = masterFullName || officers.masterName;
              const masterData = extractMilitaryData(masterLabel, 'master');
              return masterData ? (
                <div className="flex items-center gap-2">
                  <MilitaryInsignia
                    rank={masterData.rank}
                    specialty={masterData.specialty}
                    size="sm"
                  />
                  <span className="text-white font-semibold text-sm">
                    {masterData.fullName}
                  </span>
                </div>
              ) : (
                <span className="text-white font-semibold text-sm">
                  {masterLabel}
                </span>
              );
            })()}
          </div>
        )}
      </div>
      
      {/* Botão de refresh */}
      <button 
        onClick={loadOfficers}
        className="p-1.5 hover:bg-blue-500/20 rounded-full transition-all duration-200 hover:scale-110"
        title="Atualizar oficiais de serviço"
        disabled={loading}
      >
        <RefreshCw className={`w-3 h-3 text-blue-300 ${loading ? 'animate-spin' : ''}`} />
      </button>
    </div>
  );
};