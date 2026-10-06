import React, { useEffect, useState, useMemo } from "react";
import { useDisplay } from "@/context/DisplayContext";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { resolveBackendUrl } from "@/utils/backend";

const MenuDisplay: React.FC = () => {
  const { escalaDocuments } = useDisplay();
  const [currentMenuIndex, setCurrentMenuIndex] = useState(0);

  // Memoizar a lista de cardápios ativos para evitar recalcular a cada render
  const activeMenus = useMemo(() => {
    return escalaDocuments.filter(doc => {
      if (!doc.active) return false;

      // Detectar se é cardápio baseado no título ou URL
      const isMenu = doc.title.toLowerCase().includes('cardápio') ||
                    doc.title.toLowerCase().includes('cardapio') ||
                    doc.url.toLowerCase().includes('cardápio') ||
                    doc.url.toLowerCase().includes('cardapio') ||
                    doc.title.toLowerCase().includes('menu');

      console.log(`📋 MenuDisplay: Verificando documento "${doc.title}":`, {
        active: doc.active,
        isMenu: isMenu,
        category: doc.category
      });

      return isMenu;
    });
  }, [escalaDocuments]);

  // Log para debug
  useEffect(() => {
    console.log("🍽️ MenuDisplay - Estado dos cardápios:", {
      totalEscalaDocuments: escalaDocuments.length,
      activeMenus: activeMenus.length,
      currentMenuIndex: currentMenuIndex,
      menus: activeMenus.map(m => ({
        title: m.title,
        url: m.url,
        category: m.category
      }))
    });
  }, [escalaDocuments, activeMenus.length, currentMenuIndex]);

  // Alternar entre cardápios automaticamente
  useEffect(() => {
    if (activeMenus.length > 1) {
      const interval = setInterval(() => {
        setCurrentMenuIndex(prevIndex => 
          (prevIndex + 1) % activeMenus.length
        );
      }, 15000); // 15 segundos entre cardápios

      return () => clearInterval(interval);
    } else {
      setCurrentMenuIndex(0);
    }
  }, [activeMenus.length]);

  // Resetar índice se necessário
  useEffect(() => {
    if (currentMenuIndex >= activeMenus.length) {
      setCurrentMenuIndex(0);
    }
  }, [activeMenus.length, currentMenuIndex]);

  const getBackendUrl = (path: string): string => resolveBackendUrl(path);

  if (activeMenus.length === 0) {
    return (
      <Card className="h-full border-orange-400">
        <CardHeader className="bg-gradient-to-r from-orange-600 to-amber-600 text-white py-1.5">
          <CardTitle className="text-center text-sm font-medium flex items-center justify-center gap-2">
            🍽️ Cardápio Semanal
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full p-4">
          <div className="text-center text-gray-500">
            <div className="text-4xl mb-2">🍽️</div>
            <div className="text-sm">Nenhum cardápio disponível</div>
            <div className="text-xs mt-1 text-gray-400">
              Faça upload de um cardápio no painel administrativo
            </div>
          </div>
        </CardContent>
      </Card>
    );
  }

  const currentMenu = activeMenus[currentMenuIndex];

  // Resetar índice se o cardápio atual não existe (movido para fora do render)
  useEffect(() => {
    if (!currentMenu && currentMenuIndex !== 0 && activeMenus.length > 0) {
      console.warn("🍽️ Cardápio atual não encontrado, resetando índice");
      setCurrentMenuIndex(0);
    }
  }, [currentMenu, currentMenuIndex, activeMenus.length]);

  if (!currentMenu) {
    return (
      <Card className="h-full border-orange-400">
        <CardHeader className="bg-gradient-to-r from-orange-600 to-amber-600 text-white py-1.5">
          <CardTitle className="text-center text-sm font-medium">
            🍽️ Cardápio Semanal
          </CardTitle>
        </CardHeader>
        <CardContent className="flex items-center justify-center h-full p-4">
          <div className="text-center text-gray-500">
            <div className="text-4xl mb-2">⚠️</div>
            <div className="text-sm">Erro ao carregar cardápio</div>
          </div>
        </CardContent>
      </Card>
    );
  }

  const menuUrl = getBackendUrl(currentMenu.url);

  return (
    <Card className="h-full border-orange-400 overflow-hidden">
      <CardHeader className="bg-gradient-to-r from-orange-600 to-amber-600 text-white py-1.5">
        <CardTitle className="text-center flex items-center justify-between text-sm">
          <span className="font-medium flex items-center gap-2">
            🍽️ Cardápio Semanal
          </span>
          <div className="flex items-center gap-2 text-xs">
            {activeMenus.length > 1 && (
              <span className="opacity-75">
                {currentMenuIndex + 1}/{activeMenus.length}
              </span>
            )}
            <span className="opacity-75">📅</span>
          </div>
        </CardTitle>
      </CardHeader>
      
      <CardContent className="p-0 h-[calc(100%-2.5rem)]">
        <div className="h-full flex flex-col">
          {/* Barra de título do cardápio */}
          <div className="bg-gradient-to-r from-orange-50 to-amber-50 px-3 py-2 border-b border-orange-200">
            <div className="text-sm font-semibold text-orange-800 truncate">
              {currentMenu.title}
            </div>
            {currentMenu.category && (
              <div className="text-xs text-orange-600 capitalize">
                {currentMenu.category === "oficial" ? "Oficiais" : 
                 currentMenu.category === "praca" ? "Praças" : 
                 currentMenu.category}
              </div>
            )}
          </div>
          
          {/* Conteúdo do cardápio */}
          <div className="flex-1 overflow-hidden bg-white">
            {menuUrl ? (
              <div className="w-full h-full flex items-center justify-center p-2">
                <img
                  src={menuUrl}
                  alt={`Cardápio - ${currentMenu.title}`}
                  className="max-w-full max-h-full object-contain shadow-sm rounded"
                  onError={(e) => {
                    console.error("❌ Erro ao carregar cardápio:", menuUrl);
                    (e.target as HTMLImageElement).src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2ZlZjJlMiIvPjx0ZXh0IHg9IjIwMCIgeT0iMTMwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiNkOTc3MDYiIHRleHQtYW5jaG9yPSJtaWRkbGUiPkVycm8gYW8gY2FycmVnYXI8L3RleHQ+PHRleHQgeD0iMjAwIiB5PSIxNTAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZmlsbD0iI2Q5NzcwNiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Y2FyZMOhcGlvPC90ZXh0Pjx0ZXh0IHg9IjIwMCIgeT0iMTgwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iNDgiIGZpbGw9IiNkOTc3MDYiIHRleHQtYW5jaG9yPSJtaWRkbGUiPjx0c3BhbiB4PSIyMDAiIHk9IjE4MCI+8J+NvTwvdHNwYW4+PC90ZXh0Pjwvc3ZnPg==';
                  }}
                  onLoad={() => {
                    console.log(`✅ Cardápio carregado com sucesso: ${currentMenu.title}`);
                  }}
                />
              </div>
            ) : (
              <div className="flex items-center justify-center h-full text-center text-gray-500">
                <div>
                  <div className="text-4xl mb-2">🍽️</div>
                  <div className="text-sm">URL do cardápio não encontrada</div>
                </div>
              </div>
            )}
          </div>
          
          {/* Indicador de múltiplos cardápios */}
          {activeMenus.length > 1 && (
            <div className="bg-gradient-to-r from-orange-50 to-amber-50 px-3 py-2 border-t border-orange-200">
              <div className="flex justify-center space-x-1">
                {activeMenus.map((_, index) => (
                  <div
                    key={index}
                    className={`w-2 h-2 rounded-full transition-colors ${
                      index === currentMenuIndex 
                        ? "bg-orange-500"
                        : "bg-orange-200"
                    }`}
                  />
                ))}
              </div>
              <div className="text-xs text-center text-orange-600 mt-1">
                Próximo cardápio em 15 segundos
              </div>
            </div>
          )}
        </div>
      </CardContent>
    </Card>
  );
};

export default MenuDisplay;