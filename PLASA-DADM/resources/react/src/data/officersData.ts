// Dados dos oficiais baseados no sistema da Marinha
// Extraído do quadro de oficiais da Pagadoria de Pessoal da Marinha

export interface OfficerData {
  name: string;
  rank: "1t" | "2t" | "ct" | "cc" | "cf";
  specialty?: string;
  fullRankName: string;
}

export interface MasterData {
  name: string;
  rank: "3sg" | "2sg" | "1sg";
  specialty?: string;
  fullRankName: string;
}

// Lista de oficiais disponíveis para serviço (OSE)
export const OFFICERS_LIST: OfficerData[] = [
  // Capitães-Tenentes (CT)
  { name: "YAGO", rank: "ct", specialty: "IM", fullRankName: "Capitão-Tenente" },
  { name: "MATEUS BARBOSA", rank: "ct", specialty: "IM", fullRankName: "Capitão-Tenente" },
  
  // Primeiros-Tenentes (1T)
  { name: "LARISSA CASTRO", rank: "1t", specialty: "RM2-T", fullRankName: "Primeiro-Tenente" },
  { name: "ALEXANDRIA", rank: "1t", specialty: "IM", fullRankName: "Primeiro-Tenente" },
  { name: "TAMIRES", rank: "1t", specialty: "QC-IM", fullRankName: "Primeiro-Tenente" },
  { name: "KARINE", rank: "1t", specialty: "RM2-T", fullRankName: "Primeiro-Tenente" },
  { name: "RONALD CHAVES", rank: "1t", specialty: "AA", fullRankName: "Primeiro-Tenente" },
  { name: "PINA TRIGO", rank: "1t", specialty: "RM2-T", fullRankName: "Primeiro-Tenente" },
  { name: "LEONARDO ANDRADE", rank: "1t", specialty: "IM", fullRankName: "Primeiro-Tenente" },
  { name: "ELIEZER", rank: "1t", specialty: "IM", fullRankName: "Primeiro-Tenente" },
  
  // Segundos-Tenentes (2T)
  { name: "MARCIO MARTINS", rank: "2t", specialty: "AA", fullRankName: "Segundo-Tenente" },
  { name: "MACHADO", rank: "2t", specialty: "AA", fullRankName: "Segundo-Tenente" }
];

// Lista de contramestreS disponíveis para serviço
export const MASTERS_LIST: MasterData[] = [
  // Primeiros-Sargentos (1SG)
  { name: "SALES", rank: "1sg", specialty: "PL", fullRankName: "Primeiro-Sargento" },
  { name: "LEANDRO", rank: "1sg", specialty: "EP", fullRankName: "Primeiro-Sargento" },
  { name: "ELIANE", rank: "1sg", specialty: "CL", fullRankName: "Primeiro-Sargento" },
  { name: "RAFAELA", rank: "1sg", specialty: "CL", fullRankName: "Primeiro-Sargento" },
  { name: "SILVIA HELENA", rank: "1sg", specialty: "QI", fullRankName: "Primeiro-Sargento" },
  { name: "DA SILVA", rank: "1sg", specialty: "ES", fullRankName: "Primeiro-Sargento" },
  { name: "BEIRUTH", rank: "1sg", specialty: "PD", fullRankName: "Primeiro-Sargento" },
  { name: "CARLA", rank: "1sg", specialty: "CL", fullRankName: "Primeiro-Sargento" },
  
  // Segundos-Sargentos (2SG)
  { name: "ALICE", rank: "2sg", specialty: "CL", fullRankName: "Segundo-Sargento" },
  { name: "DIEGO", rank: "2sg", specialty: "ES", fullRankName: "Segundo-Sargento" },
  { name: "CANESCHE", rank: "2sg", specialty: "EL", fullRankName: "Segundo-Sargento" },
  { name: "NIBI", rank: "2sg", specialty: "ES", fullRankName: "Segundo-Sargento" },
  { name: "MONIQUE", rank: "2sg", specialty: "PD", fullRankName: "Segundo-Sargento" },
  { name: "DAMASCENO", rank: "2sg", specialty: "CL", fullRankName: "Segundo-Sargento" },
  { name: "SOUZA LIMA", rank: "2sg", specialty: "BA", fullRankName: "Segundo-Sargento" },
  { name: "SANT'ANNA", rank: "2sg", specialty: "MO", fullRankName: "Segundo-Sargento" },
  { name: "AFONSO", rank: "2sg", specialty: "SI", fullRankName: "Segundo-Sargento" },
  { name: "MEIRELES", rank: "2sg", specialty: "MR", fullRankName: "Segundo-Sargento" },
  { name: "BRUNA ROCHA", rank: "2sg", specialty: "PD", fullRankName: "Segundo-Sargento" },
  { name: "ARIANNE", rank: "2sg", specialty: "AD", fullRankName: "Segundo-Sargento" },
  
  // Terceiros-Sargentos (3SG)
  { name: "MAYARA", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "MÁRCIA", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "JUSTINO", rank: "3sg", specialty: "OS", fullRankName: "Terceiro-Sargento" },
  { name: "JONAS", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "THAÍS SILVA", rank: "3sg", specialty: "PD", fullRankName: "Terceiro-Sargento" },
  { name: "SABRINA", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "TAINÁ NEVES", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "AMANDA PAULINO", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "ANA BEATHRIZ", rank: "3sg", specialty: "AD", fullRankName: "Terceiro-Sargento" },
  { name: "KEVIN", rank: "3sg", specialty: "MO", fullRankName: "Terceiro-Sargento" },
  { name: "JORGE", rank: "3sg", specialty: "CI", fullRankName: "Terceiro-Sargento" },
  { name: "ALAN", rank: "3sg", specialty: "BA", fullRankName: "Terceiro-Sargento" },
  { name: "HUGO", rank: "3sg", specialty: "EL", fullRankName: "Terceiro-Sargento" },
  { name: "DA SILVA", rank: "3sg", specialty: "MR", fullRankName: "Terceiro-Sargento" },
  { name: "FERNANDES", rank: "3sg", specialty: "AM", fullRankName: "Terceiro-Sargento" },
  { name: "LUCAS SANTOS", rank: "3sg", specialty: "AM", fullRankName: "Terceiro-Sargento" }
];

// Função para obter dados completos de um oficial
export function getOfficerByName(name: string): OfficerData | undefined {
  return OFFICERS_LIST.find(officer => 
    officer.name.toLowerCase().includes(name.toLowerCase()) ||
    name.toLowerCase().includes(officer.name.toLowerCase())
  );
}

// Função para obter dados completos de um contramestre
export function getMasterByName(name: string): MasterData | undefined {
  return MASTERS_LIST.find(master => 
    master.name.toLowerCase().includes(name.toLowerCase()) ||
    name.toLowerCase().includes(master.name.toLowerCase())
  );
}

// Função para obter lista de oficiais por posto
export function getOfficersByRank(rank: string): OfficerData[] {
  return OFFICERS_LIST.filter(officer => officer.rank === rank);
}

// Função para obter lista de contramesters por graduação
export function getMastersByRank(rank: string): MasterData[] {
  return MASTERS_LIST.filter(master => master.rank === rank);
}

// Mapas de tradução para exibição
export const RANK_DISPLAY_MAP = {
  "ct": "CT",
  "cc": "CC", 
  "cf": "CMG",
  "1t": "1º TEN",
  "2t": "2º TEN",
  "1sg": "1º SG",
  "2sg": "2º SG", 
  "3sg": "3º SG"
};

export const RANK_FULL_NAME_MAP = {
  "ct": "Capitão-Tenente",
  "cc": "Capitão-de-Corveta",
  "cf": "Capitão-de-Mar-e-Guerra", 
  "1t": "Primeiro-Tenente",
  "2t": "Segundo-Tenente",
  "1sg": "Primeiro-Sargento",
  "2sg": "Segundo-Sargento",
  "3sg": "Terceiro-Sargento"
};