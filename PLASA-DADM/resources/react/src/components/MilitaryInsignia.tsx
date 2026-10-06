import { getMilitaryInsigniaImage, getMilitaryInsigniaDescription, hasInsignia } from '@/../../shared/insigniaData';

interface MilitaryInsigniaProps {
  rank: string;
  specialty?: string | null;
  name?: string;
  size?: 'sm' | 'md' | 'lg';
  showDescription?: boolean;
  className?: string;
}

export function MilitaryInsignia({
  rank,
  specialty,
  name,
  size = 'md',
  showDescription = false,
  className = ''
}: MilitaryInsigniaProps) {
  // 🔧 CORREÇÃO: Converter undefined para null
  const normalizedSpecialty = specialty === undefined ? null : specialty;
  
  const insigniaImage = getMilitaryInsigniaImage(rank, normalizedSpecialty);
  const description = getMilitaryInsigniaDescription(rank, normalizedSpecialty);
  
  if (!hasInsignia(rank, normalizedSpecialty)) {
    // 🔧 CORREÇÃO: Mostrar debug quando não encontrar insígnia
    console.log('🎖️ Insígnia não encontrada:', { rank, specialty: normalizedSpecialty });
    return null; // Não exibe nada se não houver insígnia
  }

  // 🔧 CORREÇÃO: Debug quando encontrar insígnia
  console.log('🎖️ Insígnia encontrada:', { 
    rank, 
    specialty: normalizedSpecialty, 
    imagePath: insigniaImage,
    description 
  });

  const sizeClasses = {
    sm: 'w-6 h-6',
    md: 'w-8 h-8',
    lg: 'w-12 h-12'
  };

  return (
    <div className={`flex items-center gap-2 ${className}`}>
      {insigniaImage && (
        <img
          src={insigniaImage}
          alt={description || `Insígnia ${rank}`}
          className={`${sizeClasses[size]} object-contain`}
          onError={(e) => {
            // 🔧 CORREÇÃO: Debug melhorado para erros de imagem
            console.error('❌ Erro ao carregar insígnia:', {
              rank,
              specialty: normalizedSpecialty,
              imagePath: insigniaImage,
              error: 'Imagem não carregou'
            });
            e.currentTarget.style.display = 'none';
          }}
          onLoad={() => {
            // 🔧 CORREÇÃO: Debug quando imagem carrega com sucesso
            console.log('✅ Insígnia carregada com sucesso:', {
              rank,
              specialty: normalizedSpecialty,
              imagePath: insigniaImage
            });
          }}
        />
      )}

      {showDescription && description && (
        <span className="text-sm text-gray-600">
          {description}
        </span>
      )}

      {name && (
        <span className="font-medium">
          {name}
        </span>
      )}
    </div>
  );
}

// Componente específico para exibir militar com insígnia
interface MilitaryWithInsigniaProps {
  military: {
    name: string;
    rank: string;
    specialty?: string | null;
    fullRankName?: string;
  };
  size?: 'sm' | 'md' | 'lg';
  showFullRank?: boolean;
  className?: string;
}

export function MilitaryWithInsignia({
  military,
  size = 'md',
  showFullRank = false,
  className = ''
}: MilitaryWithInsigniaProps) {
  const displayName = showFullRank && military.fullRankName
    ? `${military.fullRankName} ${military.name}`
    : military.name;

  // 🔧 CORREÇÃO: Normalizar specialty
  const normalizedSpecialty = military.specialty === undefined ? null : military.specialty;

  return (
    <div className={`flex items-center gap-3 ${className}`}>
      <MilitaryInsignia
        rank={military.rank}
        specialty={normalizedSpecialty}
        size={size}
      />
      <div className="flex flex-col">
        <span className="font-medium text-gray-900">
          {displayName}
        </span>
        {normalizedSpecialty && (
          <span className="text-xs text-gray-500 uppercase">
            {normalizedSpecialty}
          </span>
        )}
      </div>
    </div>
  );
}

// Hook para buscar insígnia de um militar
export function useMilitaryInsignia(rank: string, specialty?: string | null) {
  // 🔧 CORREÇÃO: Normalizar specialty no hook também
  const normalizedSpecialty = specialty === undefined ? null : specialty;
  
  return {
    hasInsignia: hasInsignia(rank, normalizedSpecialty),
    imagePath: getMilitaryInsigniaImage(rank, normalizedSpecialty),
    description: getMilitaryInsigniaDescription(rank, normalizedSpecialty)
  };
}