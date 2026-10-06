import { useState, useEffect } from 'react';
import { Thermometer, RefreshCw, AlertCircle } from 'lucide-react';
import { getCurrentTemperature, refreshTemperature, getMinutesUntilNextUpdate } from '../utils/temperatureUtils';

interface WeatherData {
  temp: number;
  description: string;
  icon: string;
  humidity: number;
  feelsLike: number;
}

export const TemperatureDisplay = () => {
  const [weather, setWeather] = useState<WeatherData | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [nextUpdate, setNextUpdate] = useState(0);

  // Carregar temperatura inicial
  useEffect(() => {
    loadTemperature();
  }, []);

  // Atualizar contador de próxima atualização
  useEffect(() => {
    const interval = setInterval(() => {
      setNextUpdate(getMinutesUntilNextUpdate());
    }, 60000); // Atualizar a cada minuto

    return () => clearInterval(interval);
  }, [weather]);

  // Auto-refresh a cada 30 minutos
  useEffect(() => {
    const interval = setInterval(() => {
      loadTemperature();
    }, 30 * 60 * 1000); // 30 minutos

    return () => clearInterval(interval);
  }, []);

  const loadTemperature = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const data = await getCurrentTemperature();
      if (data) {
        setWeather(data);
        setNextUpdate(getMinutesUntilNextUpdate());
      } else {
        setError("Dados não disponíveis");
      }
    } catch (err) {
      setError("Erro ao carregar temperatura");
      console.error("Erro na temperatura:", err);
    } finally {
      setLoading(false);
    }
  };

  const handleRefresh = async () => {
    setLoading(true);
    try {
      const data = await refreshTemperature();
      if (data) {
        setWeather(data);
        setNextUpdate(getMinutesUntilNextUpdate());
      }
    } catch (err) {
      setError("Erro ao atualizar");
    } finally {
      setLoading(false);
    }
  };

  if (error) {
    return (
      <div className="flex items-center gap-3 bg-gradient-to-r from-red-800/20 to-red-900/20 backdrop-blur-sm rounded-lg border border-red-400/20 px-4 py-2 shadow-lg">
        <div className="p-1.5 bg-red-500/20 rounded-full">
          <AlertCircle className="w-4 h-4 text-red-300" />
        </div>
        <span className="text-red-200 text-sm font-medium">Temp. indisponível</span>
        <button 
          onClick={handleRefresh}
          className="p-1.5 hover:bg-red-500/20 rounded-full transition-all duration-200 hover:scale-110"
          disabled={loading}
          title="Tentar novamente"
        >
          <RefreshCw className={`w-3 h-3 text-red-300 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>
    );
  }

  if (!weather) {
    return (
      <div className="flex items-center gap-3 bg-gradient-to-r from-slate-800/20 to-slate-900/20 backdrop-blur-sm rounded-lg border border-slate-400/20 px-4 py-2 shadow-lg">
        <div className="p-1.5 bg-slate-500/20 rounded-full">
          <Thermometer className="w-4 h-4 text-slate-300" />
        </div>
        <span className="text-slate-200 text-sm font-medium">Carregando...</span>
      </div>
    );
  }

  return (
    <div className="flex items-center gap-3 bg-gradient-to-r from-slate-800/20 to-slate-900/20 backdrop-blur-sm rounded-lg border border-slate-400/20 px-4 py-2 shadow-lg">
      {/* Ícone */}
      <div className="p-1.5 bg-slate-500/20 rounded-full">
        <Thermometer className="w-4 h-4 text-slate-300" />
      </div>
      
      {/* Informações da temperatura */}
      <div className="flex flex-col gap-1">
        <div className="flex items-center gap-2">
          <span className="text-lg font-bold text-white">
            {weather.temp}°C
          </span>
          <span className="text-xs text-slate-300">
            Sensação {weather.feelsLike}°C
          </span>
        </div>
        <div className="flex items-center gap-3 text-xs text-slate-400">
          <span className="capitalize">{weather.description}</span>
          {weather.humidity > 0 && weather.humidity <= 100 && (
            <span>💧 {weather.humidity}%</span>
          )}
        </div>
      </div>
      
      {/* Botão de refresh */}
      <button 
        onClick={handleRefresh}
        className="p-1.5 hover:bg-slate-500/20 rounded-full transition-all duration-200 hover:scale-110"
        disabled={loading}
        title="Atualizar temperatura"
      >
        <RefreshCw className={`w-3 h-3 text-slate-300 ${loading ? 'animate-spin' : ''}`} />
      </button>
    </div>
  );
};