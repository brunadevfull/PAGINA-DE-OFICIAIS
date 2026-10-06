/**
 * Script para atualizar badge de temperatura
 * Mantém o design existente, apenas atualiza o valor
 */

(function() {
  'use strict';

  const RIO_COORDS = { lat: -22.8975, lon: -43.1641 };
  const CACHE_DURATION = 30 * 60 * 1000; // 30 minutos
  const SCRIPT_SRC = document.currentScript ? document.currentScript.src : null;
  
  let temperatureCache = {
    temp: null,
    timestamp: 0
  };

  /**
   * Traduz descrições do clima
   */
  function translateWeatherDescription(description) {
    const translations = {
      'clear': 'ensolarado',
      'clear sky': 'céu limpo',
      'few clouds': 'poucas nuvens',
      'scattered clouds': 'nuvens dispersas',
      'broken clouds': 'nuvens fragmentadas',
      'overcast clouds': 'nublado',
      'light rain': 'chuva fraca',
      'moderate rain': 'chuva moderada',
      'heavy rain': 'chuva forte',
      'rain': 'chuva',
      'drizzle': 'garoa',
      'thunderstorm': 'tempestade',
      'mist': 'neblina',
      'fog': 'nevoeiro'
    };
    
    const lower = description.toLowerCase().trim();
    return translations[lower] || lower;
  }

  /**
   * fetch com timeout (AbortController funciona em navegadores mais antigos)
   */
  async function fetchWithTimeout(url, ms, options) {
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), ms);
    try {
      return await fetch(url, Object.assign({}, options, { signal: controller.signal }));
    } finally {
      clearTimeout(timer);
    }
  }

  /**
   * Endpoint do próprio servidor (temperature.php) - principal.
   * O servidor consulta as APIs externas e guarda cache de 30 minutos.
   */
  async function getTemperatureFromServer() {
    console.log('🌡️ Buscando temperatura via servidor...');

    const base = SCRIPT_SRC || window.location.href;
    const url = new URL('../temperature.php', base).href;
    const response = await fetchWithTimeout(url, 12000, { cache: 'no-store' });
    if (!response.ok) {
      throw new Error('servidor falhou');
    }
    const data = await response.json();
    if (typeof data.temp !== 'number') {
      throw new Error('resposta inválida');
    }
    return {
      temp: data.temp,
      description: translateWeatherDescription(data.description || ''),
      humidity: data.humidity,
      feelsLike: data.feelsLike
    };
  }

  /**
   * API wttr.in - Backup direto do navegador
   */
  async function getTemperatureFromWttr() {
    console.log('🌡️ Buscando temperatura via wttr.in...');
    
    const response = await fetchWithTimeout('https://wttr.in/Rio+de+Janeiro?format=j1', 8000);
    
    if (!response.ok) {
      throw new Error('wttr.in falhou');
    }

    const data = await response.json();
    const current = data.current_condition[0];
    
    return {
      temp: parseInt(current.temp_C),
      description: translateWeatherDescription(current.weatherDesc[0].value),
      humidity: parseInt(current.humidity),
      feelsLike: parseInt(current.FeelsLikeC)
    };
  }

  /**
   * API Open-Meteo - Backup
   */
  async function getTemperatureFromOpenMeteo() {
    console.log('🌡️ Buscando temperatura via Open-Meteo...');
    
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${RIO_COORDS.lat}&longitude=${RIO_COORDS.lon}&current_weather=true&hourly=relativehumidity_2m&timezone=America/Sao_Paulo`;
    
    const response = await fetchWithTimeout(url, 8000);
    
    if (!response.ok) {
      throw new Error('Open-Meteo falhou');
    }

    const data = await response.json();
    const current = data.current_weather;
    
    const getWeatherDesc = (code) => {
      if (code === 0) return 'céu limpo';
      if (code <= 2) return 'parcialmente nublado';
      if (code === 3) return 'nublado';
      if (code <= 67) return 'chuva';
      return 'condições variáveis';
    };
    
    return {
      temp: Math.round(current.temperature),
      description: getWeatherDesc(current.weathercode),
      humidity: Math.round(data.hourly?.relativehumidity_2m?.[0] || 65),
      feelsLike: Math.round(current.temperature)
    };
  }

  /**
   * Dados de fallback
   */
  function getFallbackTemperature() {
    console.log('⚠️ Usando temperatura de fallback');
    return {
      temp: null,
      description: 'não disponível',
      humidity: null,
      feelsLike: null
    };
  }

  /**
   * Busca temperatura com fallback automático
   */
  async function getCurrentTemperature() {
    const now = Date.now();
    
    // Verificar cache
    if (temperatureCache.temp !== null && (now - temperatureCache.timestamp) < CACHE_DURATION) {
      console.log('🌡️ Usando temperatura do cache:', temperatureCache.temp + '°C');
      return temperatureCache;
    }

    // Tentar APIs em sequência
    const apis = [getTemperatureFromServer, getTemperatureFromWttr, getTemperatureFromOpenMeteo];
    
    for (const api of apis) {
      try {
        const data = await api();
        
        // Atualizar cache
        temperatureCache = {
          ...data,
          timestamp: now
        };
        
        console.log('✅ Temperatura obtida:', data.temp + '°C');
        return temperatureCache;
        
      } catch (error) {
        console.log('❌ API falhou, tentando próxima...');
      }
    }
    
    // Se tudo falhar: mantém o último valor obtido (se houver); senão, indisponível.
    // O fallback não é guardado em cache, para tentar de novo na próxima atualização.
    if (temperatureCache.temp !== null) {
      return temperatureCache;
    }
    return getFallbackTemperature();
  }

  /**
   * Atualiza o badge de temperatura
   */
  async function updateTemperatureBadge() {
    const badge = document.getElementById('temperature-badge');
    
    if (!badge) {
      console.warn('⚠️ Badge de temperatura não encontrado');
      return;
    }

    try {
      // Mostrar loading
      badge.textContent = 'Temp: ...';
      badge.title = 'Carregando temperatura...';
      
      // Buscar temperatura
      const weather = await getCurrentTemperature();
      
      // Sem dados: mostra --°C em vez de um número fictício
      if (weather.temp === null) {
        badge.textContent = 'Temp: --°C';
        badge.title = 'Temperatura indisponível no momento';
        return;
      }

      // Atualizar badge
      badge.textContent = `Temp: ${weather.temp}°C`;
      
      // Adicionar tooltip com mais informações
      const tooltip = `Rio de Janeiro
Temperatura: ${weather.temp}°C
Sensação: ${weather.feelsLike}°C
Umidade: ${weather.humidity}%
Condição: ${weather.description}`;
      
      badge.title = tooltip;
      
      console.log('✅ Badge atualizado com sucesso!');
      
    } catch (error) {
      console.error('❌ Erro ao atualizar badge:', error);
      badge.textContent = 'Temp: --°C';
      badge.title = 'Erro ao carregar temperatura';
    }
  }

  /**
   * Inicialização
   */
  function init() {
    console.log('🚀 Inicializando sistema de temperatura...');
    
    // Verificar se badge existe antes de iniciar
    const badge = document.getElementById('temperature-badge');
    if (!badge) {
      console.warn('⚠️ Badge de temperatura não encontrado no DOM');
      return;
    }
    
    // Buscar temperatura imediatamente
    updateTemperatureBadge();
    
    // Atualizar a cada 5 minutos (usa cache de 30 min quando há dado válido)
    setInterval(updateTemperatureBadge, 5 * 60 * 1000);
    
    console.log('✅ Sistema de temperatura iniciado');
    console.log('⏰ Próxima verificação em 5 minutos');
  }

  // Aguardar DOM carregar completamente
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    // DOM já carregado, iniciar imediatamente
    init();
  }

  // Expor função de atualização manual (opcional)
  window.refreshTemperature = updateTemperatureBadge;

})();