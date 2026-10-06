// src/components/WeatherDisplay.tsx
import React, { useState, useEffect } from 'react';

interface WeatherData {
  temp: number;
  description: string;
  humidity: number;
  feelsLike: number;
  windSpeed: number;
  icon: string;
}

export const WeatherDisplay: React.FC = () => {
  const [weather, setWeather] = useState<WeatherData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [lastUpdate, setLastUpdate] = useState<Date | null>(null);

  // Função para obter temperatura com proxy da Marinha
  const fetchWeather = async (): Promise<WeatherData | null> => {
    try {
      console.log('🌡️ Buscando temperatura do Rio...');
      
      const API_KEY = import.meta.env.VITE_OPENWEATHER_API_KEY;
      const PROXY_URL = import.meta.env.VITE_PROXY_URL;
      
      if (!API_KEY) {
        throw new Error('API Key não configurada');
      }

      // Coordenadas da Ilha Fiscal (PAPEM)
      const lat = -22.8975;
      const lon = -43.1641;
      
      const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric&lang=pt_br`;
      
      const response = await fetch(url, {
        headers: {
          'User-Agent': 'PAPEM-Weather-System/1.0-Marinha-Brasil'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }

      const data = await response.json();
      
      const weatherData: WeatherData = {
        temp: Math.round(data.main.temp),
        description: data.weather[0].description,
        humidity: data.main.humidity,
        feelsLike: Math.round(data.main.feels_like),
        windSpeed: Math.round(data.wind.speed * 3.6), // m/s para km/h
        icon: data.weather[0].icon
      };

      console.log(`🌡️ Temperatura obtida: ${weatherData.temp}°C - ${weatherData.description}`);
      return weatherData;

    } catch (error) {
      console.error('❌ Erro ao buscar clima:', error);
      
      // Fallback com APIs alternativas
      return await fetchWeatherAlternative();
    }
  };

  // APIs alternativas (wttr.in, Open-Meteo)
  const fetchWeatherAlternative = async (): Promise<WeatherData | null> => {
    try {
      console.log('🔄 Tentando API alternativa...');
      
      // Tentar wttr.in primeiro
      const response = await fetch('https://wttr.in/Rio+de+Janeiro?format=j1');
      
      if (response.ok) {
        const data = await response.json();
        const current = data.current_condition[0];
        
        return {
          temp: parseInt(current.temp_C),
          description: translateDescription(current.weatherDesc[0].value),
          humidity: parseInt(current.humidity),
          feelsLike: parseInt(current.FeelsLikeC),
          windSpeed: Math.round(parseInt(current.windspeedKmph)),
          icon: '01d'
        };
      }
      
      throw new Error('APIs alternativas indisponíveis');
      
    } catch (error) {
      console.error('❌ APIs alternativas falharam:', error);
      
      // Último recurso: dados de fallback
      return {
        temp: 24,
        description: 'dados indisponíveis',
        humidity: 65,
        feelsLike: 26,
        windSpeed: 15,
        icon: '01d'
      };
    }
  };

  // Traduzir descrições para português
  const translateDescription = (description: string): string => {
    const translations: { [key: string]: string } = {
      'clear': 'ensolarado',
      'sunny': 'ensolarado',
      'clear sky': 'céu limpo',
      'few clouds': 'poucas nuvens',
      'scattered clouds': 'nuvens dispersas',
      'broken clouds': 'nuvens fragmentadas',
      'overcast clouds': 'nublado',
      'overcast': 'nublado',
      'cloudy': 'nublado',
      'light rain': 'chuva fraca',
      'moderate rain': 'chuva moderada',
      'heavy rain': 'chuva forte',
      'rain': 'chuva',
      'thunderstorm': 'tempestade',
      'mist': 'neblina',
      'fog': 'nevoeiro'
    };

    const lowerDescription = description.toLowerCase().trim();
    return translations[lowerDescription] || lowerDescription;
  };

  // Obter ícone emoji baseado na condição
  const getWeatherEmoji = (icon: string, description: string): string => {
    if (description.includes('tempestade')) return '⛈️';
    if (description.includes('chuva')) return '🌧️';
    if (description.includes('nublado')) return '☁️';
    if (description.includes('nuvens')) return '⛅';
    if (description.includes('ensolarado') || description.includes('limpo')) return '☀️';
    if (description.includes('neblina') || description.includes('nevoeiro')) return '🌫️';
    return '🌤️';
  };

  // Carregar dados na inicialização
  useEffect(() => {
    const loadWeather = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const data = await fetchWeather();
        
        if (data) {
          setWeather(data);
          setLastUpdate(new Date());
        } else {
          setError('Não foi possível obter dados meteorológicos');
        }
      } catch (err) {
        setError('Erro ao carregar clima');
      } finally {
        setLoading(false);
      }
    };

    loadWeather();
    
    // Atualizar a cada 30 minutos
    const interval = setInterval(loadWeather, 30 * 60 * 1000);
    
    return () => clearInterval(interval);
  }, []);

  // Determinar alertas meteorológicos
  const getWeatherAlert = (weather: WeatherData) => {
    if (weather.description.includes('tempestade')) {
      return { severity: 'high', message: 'Tempestade', color: 'bg-red-500/30 border-red-400/50' };
    }
    if (weather.description.includes('chuva forte')) {
      return { severity: 'medium', message: 'Chuva forte', color: 'bg-orange-500/30 border-orange-400/50' };
    }
    if (weather.temp > 35) {
      return { severity: 'medium', message: 'Calor intenso', color: 'bg-red-500/30 border-red-400/50' };
    }
    if (weather.humidity > 85) {
      return { severity: 'low', message: 'Umidade alta', color: 'bg-yellow-500/30 border-yellow-400/50' };
    }
    return { severity: 'normal', message: '', color: 'bg-blue-500/20 border-blue-400/30' };
  };

  if (loading) {
    return (
      <div className="bg-blue-500/20 backdrop-blur-sm rounded-lg px-3 py-2 border border-blue-400/30">
        <div className="flex items-center space-x-2">
          <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
          <span className="text-white text-sm">Carregando clima...</span>
        </div>
      </div>
    );
  }

  if (error || !weather) {
    return (
      <div className="bg-gray-500/20 backdrop-blur-sm rounded-lg px-3 py-2 border border-gray-400/30">
        <div className="flex items-center space-x-2">
          <span className="text-lg">🌡️</span>
          <span className="text-white text-sm">Clima indisponível</span>
        </div>
      </div>
    );
  }

  const alert = getWeatherAlert(weather);
  const weatherEmoji = getWeatherEmoji(weather.icon, weather.description);

  return (
    <div className={`backdrop-blur-sm rounded-lg px-3 py-2 border ${alert.color}`}>
      <div className="flex items-center space-x-2">
        <span className="text-lg">{weatherEmoji}</span>
        <div className="text-white">
          <div className="text-sm font-medium">
            {weather.temp}°C
          </div>
          <div className="text-xs opacity-80 capitalize">
            {weather.description}
          </div>
        </div>
      </div>
      
      {alert.severity !== 'normal' && (
        <div className="mt-1 text-xs text-white/90">
          {alert.message}
        </div>
      )}
      
      {lastUpdate && (
        <div className="mt-1 text-xs text-white/60">
          {lastUpdate.toLocaleTimeString('pt-BR', { 
            hour: '2-digit', 
            minute: '2-digit' 
          })}
        </div>
      )}
    </div>
  );
};