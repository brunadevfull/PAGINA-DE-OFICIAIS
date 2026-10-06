/**
 * Utilitários para obter temperatura do Rio de Janeiro
 * Usa API gratuita do OpenWeatherMap
 */

interface WeatherData {
  temp: number;
  description: string;
  icon: string;
  humidity: number;
  feelsLike: number;
}

interface TemperatureCache {
  data: WeatherData | null;
  timestamp: number;
  error?: string;
}

const CACHE_DURATION = 30 * 60 * 1000; // 30 minutos em millisegundos
const RIO_COORDS = { lat: -22.8975, lon: -43.1641 }; // Ilha Fiscal

// Cache local para evitar muitas requisições
let temperatureCache: TemperatureCache = {
  data: null,
  timestamp: 0
};

const parseJson = (text: string) => {
  if (!text.trim()) {
    throw new Error('Resposta vazia do servidor.');
  }
  return JSON.parse(text);
};

/**
 * Traduz descrições do clima do inglês para português
 */
const translateWeatherDescription = (description: string): string => {
  const translations: { [key: string]: string } = {
    // Condições básicas
    'clear': 'ensolarado',
    'sunny': 'ensolarado', 
    'clear sky': 'céu limpo',
    'few clouds': 'poucas nuvens',
    'scattered clouds': 'nuvens dispersas',
    'broken clouds': 'nuvens fragmentadas',
    'overcast clouds': 'nublado',
    'overcast': 'nublado',
    'cloudy': 'nublado',
    'partly cloudy': 'parcialmente nublado',
    
    // Chuva
    'light rain': 'chuva fraca',
    'moderate rain': 'chuva moderada',
    'heavy rain': 'chuva forte',
    'shower rain': 'chuva rápida',
    'rain': 'chuva',
    'drizzle': 'garoa',
    'light intensity drizzle': 'garoa fraca',
    'heavy intensity drizzle': 'garoa forte',
    
    // Tempestades
    'thunderstorm': 'tempestade',
    'thunderstorm with light rain': 'tempestade com chuva fraca',
    'thunderstorm with rain': 'tempestade com chuva',
    'thunderstorm with heavy rain': 'tempestade com chuva forte',
    
    // Neve (raro no Rio, mas pode aparecer)
    'snow': 'neve',
    'light snow': 'neve fraca',
    
    // Outras condições  
    'mist': 'neblina',
    'fog': 'nevoeiro',
    'haze': 'névoa seca',
    'dust': 'poeira',
    'smoke': 'fumaça',
    'mostly cloudy': 'muito nublado',
    
    // Fallbacks comuns da API
    'temperature not available': 'temperatura não disponível',
    'weather data unavailable': 'dados meteorológicos indisponíveis'
  };

  const lowerDescription = description.toLowerCase().trim();
  return translations[lowerDescription] || lowerDescription;
};

/**
 * Obtém temperatura atual do Rio de Janeiro
 * Retorna dados do cache se ainda válidos (menos de 30 min)
 */
export const getCurrentTemperature = async (): Promise<WeatherData | null> => {
  const now = Date.now();
  
  // Verificar se cache ainda é válido
  if (temperatureCache.data && (now - temperatureCache.timestamp) < CACHE_DURATION) {
    console.log("🌡️ Usando temperatura do cache");
    return temperatureCache.data;
  }

  try {
    console.log("🌡️ Buscando temperatura atualizada...");

    const response = await fetch('/api/temperature');
    const contentType = response.headers.get('content-type') ?? '';
    const responseText = await response.text();

    if (!response.ok) {
      let errorData: unknown = {};
      if (contentType.includes('application/json')) {
        try {
          errorData = parseJson(responseText);
        } catch (parseError) {
          console.error("❌ Falha ao interpretar JSON de erro:", parseError);
          errorData = { raw: responseText.slice(0, 200) };
        }
      } else if (responseText.trim()) {
        errorData = { raw: responseText.slice(0, 200) };
      }
      console.error("❌ Erro ao buscar temperatura no servidor:", errorData);
      
      // Return cached data if available, otherwise fallback data
      if (temperatureCache.data) {
        console.log("🌡️ Retornando dados do cache devido a erro no servidor");
        return temperatureCache.data;
      }
      
      // Return fallback data instead of making external API calls
      const fallbackData: WeatherData = {
        temp: 24,
        description: "temperatura não disponível",
        icon: '01d',
        humidity: 65,
        feelsLike: 26
      };
      
      temperatureCache = {
        data: fallbackData,
        timestamp: now,
        error: "Servidor indisponível"
      };
      
      return fallbackData;
    }

    let weatherData: WeatherData;
    if (!contentType.includes('application/json')) {
      console.error(
        "❌ Resposta OK com content-type inesperado:",
        contentType || 'indefinido'
      );
      throw new Error(`Resposta inesperada do servidor (content-type: ${contentType || 'indefinido'}).`);
    }
    try {
      weatherData = parseJson(responseText) as WeatherData;
    } catch (parseError) {
      console.error("❌ Resposta OK com JSON inválido:", {
        error: (parseError as Error).message,
        raw: responseText.slice(0, 200)
      });
      throw new Error(`Falha ao interpretar resposta JSON: ${(parseError as Error).message}`);
    }

    temperatureCache = {
      data: weatherData,
      timestamp: now
    };

    console.log(`🌡️ Temperatura atualizada: ${weatherData.temp}°C`);
    return weatherData;
  } catch (error) {
    console.error("❌ Erro ao obter temperatura:", error);
    
    // Return cached data if available, otherwise fallback data
    if (temperatureCache.data) {
      console.log("🌡️ Retornando dados do cache devido a erro");
      return temperatureCache.data;
    }
    
    // Return fallback data instead of making external API calls
    const fallbackData: WeatherData = {
      temp: 24,
      description: "temperatura não disponível",
      icon: '01d',
      humidity: 65,
      feelsLike: 26
    };
    
    temperatureCache = {
      data: fallbackData,
      timestamp: now,
      error: "Erro ao conectar com servidor"
    };
    
    return fallbackData;
  }
};

/**
 * Força atualização da temperatura (ignora cache)
 */
export const refreshTemperature = async (): Promise<WeatherData | null> => {
  temperatureCache.timestamp = 0; // Invalida cache
  return await getCurrentTemperature();
};

/**
 * Verifica se os dados de temperatura estão atualizados
 */
export const isTemperatureCacheValid = (): boolean => {
  const now = Date.now();
  return temperatureCache.data !== null && (now - temperatureCache.timestamp) < CACHE_DURATION;
};

/**
 * Obtém tempo restante até próxima atualização (em minutos)
 */
export const getMinutesUntilNextUpdate = (): number => {
  if (!temperatureCache.data) return 0;
  
  const now = Date.now();
  const elapsed = now - temperatureCache.timestamp;
  const remaining = CACHE_DURATION - elapsed;
  
  return Math.max(0, Math.ceil(remaining / (60 * 1000)));
};
