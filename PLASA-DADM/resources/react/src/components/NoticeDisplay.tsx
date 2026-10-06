import React, { useState, useEffect } from "react";
import { useDisplay, Notice } from "@/context/DisplayContext";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

const NoticeDisplay: React.FC = () => {
  const { notices } = useDisplay();
  const [currentNoticeIndex, setCurrentNoticeIndex] = useState(0);

  // CORREÇÃO: Função melhorada para filtrar avisos ativos
  const getActiveNotices = (): Notice[] => {
    const now = new Date();
    
    return notices.filter(notice => {
      try {
        // CORREÇÃO: Garantir que as datas sejam objetos Date válidos
        let startDate: Date;
        let endDate: Date;
        
        if (notice.startDate instanceof Date) {
          startDate = notice.startDate;
        } else if (typeof notice.startDate === 'string') {
          startDate = new Date(notice.startDate);
        } else {
          console.warn("📢 Aviso com startDate inválida:", notice.title);
          return false;
        }
        
        if (notice.endDate instanceof Date) {
          endDate = notice.endDate;
        } else if (typeof notice.endDate === 'string') {
          endDate = new Date(notice.endDate);
        } else {
          console.warn("📢 Aviso com endDate inválida:", notice.title);
          return false;
        }
        
        // CORREÇÃO: Verificar se as datas são válidas
        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
          console.warn("📢 Aviso com datas inválidas:", notice.title, {
            startDate: notice.startDate,
            endDate: notice.endDate
          });
          return false;
        }
        
        // Verificar se está ativo e dentro do período
        const isActive = notice.active === true;
        const isInPeriod = now >= startDate && now <= endDate;
        
        console.log(`📢 Verificando aviso "${notice.title}":`, {
          active: isActive,
          inPeriod: isInPeriod,
          startDate: startDate.toISOString(),
          endDate: endDate.toISOString(),
          now: now.toISOString()
        });
        
        return isActive && isInPeriod;
        
      } catch (error) {
        console.error("❌ Erro ao processar aviso:", notice.title, error);
        return false;
      }
    });
  };

  const activeNotices = getActiveNotices();

  // CORREÇÃO: Log melhorado para debug
  useEffect(() => {
    console.log("📢 NoticeDisplay - Estado dos avisos:", {
      totalNotices: notices.length,
      activeNotices: activeNotices.length,
      currentIndex: currentNoticeIndex,
      avisos: notices.map(n => ({
        title: n.title,
        active: n.active,
        startDate: n.startDate,
        endDate: n.endDate,
        isStartDateValid: n.startDate instanceof Date || typeof n.startDate === 'string',
        isEndDateValid: n.endDate instanceof Date || typeof n.endDate === 'string'
      }))
    });
  }, [notices, activeNotices.length, currentNoticeIndex]);

  // Alternar entre avisos automaticamente
  useEffect(() => {
    if (activeNotices.length > 1) {
      const interval = setInterval(() => {
        setCurrentNoticeIndex(prevIndex => 
          (prevIndex + 1) % activeNotices.length
        );
      }, 10000); // 10 segundos

      return () => clearInterval(interval);
    } else {
      // Se há apenas um aviso ou nenhum, resetar índice
      setCurrentNoticeIndex(0);
    }
  }, [activeNotices.length]);

  // CORREÇÃO: Resetar índice se necessário
  useEffect(() => {
    if (currentNoticeIndex >= activeNotices.length) {
      setCurrentNoticeIndex(0);
    }
  }, [activeNotices.length, currentNoticeIndex]);

  const getPriorityColor = (priority: "high" | "medium" | "low") => {
    switch (priority) {
      case "high":
        return "bg-red-500 border-red-600";
      case "medium":
        return "bg-yellow-500 border-yellow-600";
      case "low":
        return "bg-green-500 border-green-600";
      default:
        return "bg-blue-500 border-blue-600";
    }
  };

  const getPriorityIcon = (priority: "high" | "medium" | "low") => {
    switch (priority) {
      case "high":
        return "🔴";
      case "medium":
        return "🟡";
      case "low":
        return "🟢";
      default:
        return "🔵";
    }
  };

  if (activeNotices.length === 0) {
    return (
      <Card className="h-full border-navy">
        <CardHeader className="bg-navy text-white py-1.5">
          <CardTitle className="text-center text-sm font-medium">
            📢 Avisos Importantes
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full p-4">
          <div className="text-center text-gray-500">
            <div className="text-4xl mb-2">📢</div>
            <div className="text-sm">Nenhum aviso ativo</div>
            <div className="text-xs mt-1 text-gray-400">
              Total de avisos: {notices.length}
            </div>
          </div>
        </CardContent>
      </Card>
    );
  }

  const currentNotice = activeNotices[currentNoticeIndex];
  
  // CORREÇÃO: Verificação adicional de segurança
  if (!currentNotice) {
    console.warn("📢 Aviso atual não encontrado, resetando índice");
    if (currentNoticeIndex !== 0) {
      setCurrentNoticeIndex(0);
    }
    return (
      <Card className="h-full border-navy">
        <CardHeader className="bg-navy text-white py-1.5">
          <CardTitle className="text-center text-sm font-medium">
            📢 Avisos Importantes
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full p-4">
          <div className="text-center text-gray-500">
            <div className="text-4xl mb-2">⚠️</div>
            <div className="text-sm">Erro ao carregar aviso</div>
          </div>
        </CardContent>
      </Card>
    );
  }

  return (
    <Card className="h-full border-navy overflow-hidden">
      <CardHeader className="bg-navy text-white py-1.5">
        <CardTitle className="text-center flex items-center justify-between text-sm">
          <span className="font-medium">📢 Avisos Importantes</span>
          <div className="flex items-center gap-2 text-xs">
            {activeNotices.length > 1 && (
              <span className="opacity-75">
                {currentNoticeIndex + 1}/{activeNotices.length}
              </span>
            )}
            <span className="opacity-75">
              {getPriorityIcon(currentNotice.priority)}
            </span>
          </div>
        </CardTitle>
      </CardHeader>
      
      <CardContent className="p-0 h-[calc(100%-2.5rem)]">
        <div className="h-full flex flex-col">
          {/* Barra de prioridade */}
          <div 
            className={`h-1 ${getPriorityColor(currentNotice.priority)}`}
          />
          
          {/* Conteúdo do aviso */}
          <div className="flex-1 p-3 overflow-y-auto">
            <div className="mb-2">
              <h3 className="font-bold text-sm text-navy mb-1 flex items-center gap-2">
                {getPriorityIcon(currentNotice.priority)}
                {currentNotice.title}
              </h3>
              <div className="text-xs text-gray-500 mb-2">
                Prioridade: {
                  currentNotice.priority === "high" ? "🔴 Alta" :
                  currentNotice.priority === "medium" ? "🟡 Média" : "🟢 Baixa"
                }
              </div>
            </div>
            
            <div className="text-sm text-gray-700 leading-relaxed">
              {currentNotice.content}
            </div>
            
     
          </div>
          
          {/* Indicador de múltiplos avisos */}
          {activeNotices.length > 1 && (
            <div className="bg-gray-50 px-3 py-2 border-t">
              <div className="flex justify-center space-x-1">
                {activeNotices.map((_, index) => (
                  <div
                    key={index}
                    className={`w-2 h-2 rounded-full transition-colors ${
                      index === currentNoticeIndex 
                        ? getPriorityColor(activeNotices[index].priority).split(' ')[0]
                        : "bg-gray-300"
                    }`}
                  />
                ))}
              </div>
            </div>
          )}
        </div>
      </CardContent>
    </Card>
  );
};

export default NoticeDisplay;