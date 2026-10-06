/*
 * Alertas Meteorológicos Compactos - Versão Header
 * Layout horizontal compacto para o header
 */

import React, { useState, useEffect } from "react";

export const WeatherAlertsActive = () => {
  const [temperature, setTemperature] = useState<number>(23);
  const [condition, setCondition] = useState<string>('nublado');
  const [rainTime, setRainTime] = useState<string | null>(null);
  const [humidity, setHumidity] = useState<number>(67);
  const [rainChance, setRainChance] = useState<number>(70);
  const [loading, setLoading] = useState(true);

  // Ícones para diferentes condições meteorológicas
  const getWeatherIcon = (condition: string, hasRain: boolean): string => {
    const cond = condition.toLowerCase();
    
    if (hasRain || cond.includes('chuva') || cond.includes('rain')) return '🌧️';
    if (cond.includes('tempestade') || cond.includes('thunder')) return '⛈️';
    if (cond.includes('sol') || cond.includes('sunny') || cond.includes('clear')) return '☀️';
    if (cond.includes('nublado') || cond.includes('cloudy')) return '☁️';
    if (cond.includes('parcialmente') || cond.includes('partly')) return '⛅';
    if (cond.includes('encoberto') || cond.includes('overcast')) return '☁️';
    
    return '⛅'; // Default
  };

  // Tradução e normalização de condições
  const translateCondition = (condition: string): string => {
    const translations: Record<string, string> = {
      'clear': 'Céu Limpo',
      'sunny': 'Ensolarado',
      'partly cloudy': 'Parcialmente Nublado',
      'cloudy': 'Nublado',
      'overcast': 'Encoberto',
      'light rain': 'Chuva Leve',
      'moderate rain': 'Chuva Moderada',
      'heavy rain': 'Chuva Forte',
      'thunderstorm': 'Tempestade',
      'mist': 'Neblina',
      'fog': 'Nevoeiro'
    };
    
    const translated = translations[condition.toLowerCase()];
    if (translated) return translated;
    
    return condition.charAt(0).toUpperCase() + condition.slice(1).toLowerCase();
  };

  // Buscar dados meteorológicos
  const fetchWeather = async () => {
    try {
      setLoading(true);
      
      let temperature = 23;
      let condition = 'Nublado';
      let humidity = 67;
      let rainChance = 70;
      
      // Fonte principal: wttr.in
      try {
        const response1 = await fetch('https://wttr.in/Rio+de+Janeiro?format=j1');
        if (response1.ok) {
          const data1 = await response1.json();
          const current1 = data1.current_condition[0];
          
          temperature = parseInt(current1.temp_C);
          condition = translateCondition(current1.weatherDesc[0].value);
          humidity = parseInt(current1.humidity);
          
          console.log('🌡️ Dados meteorológicos carregados:', {
            temp: temperature,
            condition,
            humidity
          });
        }
      } catch (e) {
        console.log('⚠️ API principal falhou, usando dados padrão');
      }

      setTemperature(temperature);
      setCondition(condition);
      setHumidity(humidity);
      setRainChance(rainChance);
      
      // Encontrar próxima chuva
      let firstRain: string | null = null;
      
      try {
        const response1 = await fetch('https://wttr.in/Rio+de+Janeiro?format=j1');
        if (response1.ok) {
          const data1 = await response1.json();
          const today = data1.weather[0];
          const now = new Date();
          const currentHour = now.getHours();
          const currentMinutes = now.getMinutes();
          
          today.hourly.slice(0, 24).forEach((hour: any, index: number) => {
            const hourTime = currentHour + index;
            const rainChanceHour = parseInt(hour.chanceofrain);
            
            let isFuture = false;
            if (index === 0) {
              isFuture = currentMinutes < 30;
            } else {
              isFuture = true;
            }
            
            if (!firstRain && rainChanceHour > 50 && isFuture) {
              firstRain = `${String(hourTime % 24).padStart(2, '0')}:00`;
            }
          });
        }
      } catch (e) {
        console.log('⚠️ Dados de chuva indisponíveis');
      }
      
      setRainTime(firstRain);
    } catch (err) {
      console.error('Erro meteorológico:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchWeather();
    const interval = setInterval(fetchWeather, 15 * 60 * 1000);
    return () => clearInterval(interval);
  }, []);

  if (loading) {
    return (
      <div className="bg-gradient-to-r from-blue-600/30 to-slate-900/60 backdrop-blur-sm rounded-lg px-3 py-2 border border-blue-400/30 shadow-lg min-w-[240px]">
        <div className="flex items-center justify-center space-x-2">
          <div className="animate-spin rounded-full h-4 w-4 border-2 border-blue-300 border-t-transparent"></div>
          <span className="text-blue-200 text-xs">Carregando...</span>
        </div>
      </div>
    );
  }

  const weatherIcon = getWeatherIcon(condition, Boolean(rainTime));

  return (
    <div className="bg-gradient-to-r from-blue-600/30 to-slate-900/60 backdrop-blur-sm rounded-lg px-3 py-2 border border-blue-400/30 shadow-lg min-w-[240px] max-w-[280px]">
      {/* Layout Horizontal Compacto */}
      <div className="flex items-center justify-between space-x-3">
        {/* Lado Esquerdo - Temperatura e Condição */}
        <div className="flex items-center space-x-2">
          <span className="text-xl">{weatherIcon}</span>
          <div>
            <div className="text-white text-base font-bold leading-tight">
              {temperature}°C
            </div>
            <div className="text-blue-200 text-xs">
              Sensação {temperature}°C
            </div>
          </div>
        </div>

        {/* Divisor Vertical */}
        <div className="w-px h-8 bg-blue-400/30"></div>

        {/* Lado Direito - Condição e Detalhes */}
        <div className="flex-1 text-right">
          <div className="text-blue-100 text-xs font-medium leading-tight">
            {condition}
          </div>
          <div className="flex justify-end items-center space-x-2 mt-1">
            <span className="text-blue-300 text-xs">💧 {humidity}%</span>
            <span className="text-blue-300 text-xs">☔ {rainChance}%</span>
          </div>
        </div>
      </div>


     
     
    </div>
  );
};