/*
 * Sistema de Alertas Meteorológicos do Rio de Janeiro
 * Monitora condições climáticas severas com previsão de chuva e alertas de segurança
 */

import React, { useState, useEffect } from "react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";

// Interface para dados meteorológicos
interface WeatherData {
  temperature: number;
  humidity: number;
  windSpeed: number;
  windDirection: string;
  condition: string;
  rainProbability: number;
  rainIntensity: string;
  pressure: number;
  visibility: number;
  uvIndex: number;
  alerts: WeatherAlert[];
  forecast: HourlyForecast[];
}

interface WeatherAlert {
  type: 'rain' | 'storm' | 'wind' | 'heat' | 'cold' | 'fog';
  severity: 'baixo' | 'moderado' | 'alto' | 'extremo';
  title: string;
  description: string;
  startTime?: string;
  endTime?: string;
  safetyTips: string[];
}

interface HourlyForecast {
  time: string;
  temperature: number;
  rainProbability: number;
  rainIntensity: string;
  condition: string;
}

export const WeatherAlerts = () => {
  const [weatherData, setWeatherData] = useState<WeatherData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [lastUpdated, setLastUpdated] = useState<Date | null>(null);

  // Tradução de condições meteorológicas
  const translateCondition = (condition: string): string => {
    const translations: Record<string, string> = {
      'clear': 'céu limpo',
      'sunny': 'ensolarado',
      'partly cloudy': 'parcialmente nublado',
      'cloudy': 'nublado',
      'overcast': 'encoberto',
      'mist': 'névoa',
      'fog': 'neblina',
      'light rain': 'chuva leve',
      'moderate rain': 'chuva moderada',
      'heavy rain': 'chuva forte',
      'thunderstorm': 'tempestade',
      'drizzle': 'garoa',
      'shower': 'pancada de chuva'
    };
    return translations[condition.toLowerCase()] || condition;
  };

  // Classificar intensidade da chuva
  const getRainIntensity = (rainChance: number): string => {
    if (rainChance >= 80) return 'chuva forte';
    if (rainChance >= 60) return 'chuva moderada';
    if (rainChance >= 30) return 'chuva leve';
    return 'sem chuva';
  };

  // Gerar alertas baseados nas condições atuais
  const generateWeatherAlerts = (current: any, today: any): WeatherAlert[] => {
    const alerts: WeatherAlert[] = [];
    const temp = parseInt(current.temp_C);
    const humidity = parseInt(current.humidity);
    const windSpeed = parseInt(current.windspeedKmph);
    const rainChance = parseInt(today.hourly[0].chanceofrain);

    // Alerta de chuva
    if (rainChance > 70) {
      alerts.push({
        type: 'rain',
        severity: rainChance > 90 ? 'alto' : 'moderado',
        title: 'Alerta de Chuva',
        description: `${rainChance}% de chance de chuva nas próximas horas`,
        safetyTips: [
          'Evite áreas alagáveis',
          'Mantenha guarda-chuva ou capa de chuva',
          'Dirija com cuidado em caso de chuva forte'
        ]
      });
    }

    // Alerta de vento forte
    if (windSpeed > 40) {
      alerts.push({
        type: 'wind',
        severity: windSpeed > 60 ? 'alto' : 'moderado',
        title: 'Vento Forte',
        description: `Ventos de ${windSpeed} km/h`,
        safetyTips: [
          'Evite áreas com árvores altas',
          'Cuidado com objetos soltos',
          'Não use guarda-chuva em vento muito forte'
        ]
      });
    }

    // Alerta de temperatura alta
    if (temp > 35) {
      alerts.push({
        type: 'heat',
        severity: temp > 40 ? 'alto' : 'moderado',
        title: 'Calor Intenso',
        description: `Temperatura de ${temp}°C`,
        safetyTips: [
          'Mantenha-se hidratado',
          'Evite exposição solar entre 10h e 16h',
          'Use protetor solar e roupas leves'
        ]
      });
    }

    // Alerta de umidade alta
    if (humidity > 85 && temp > 25) {
      alerts.push({
        type: 'heat',
        severity: 'moderado',
        title: 'Sensação Térmica Elevada',
        description: `Umidade de ${humidity}% com ${temp}°C`,
        safetyTips: [
          'Ambientes com ar condicionado recomendados',
          'Hidrate-se frequentemente',
          'Evite atividades físicas intensas'
        ]
      });
    }

    return alerts;
  };

  // Gerar previsão horária
  const generateHourlyForecast = (weather: any[]): HourlyForecast[] => {
    const forecast: HourlyForecast[] = [];
    const today = weather[0];
    
    today.hourly.slice(0, 6).forEach((hour: any, index: number) => {
      const currentHour = new Date().getHours() + index;
      forecast.push({
        time: `${String(currentHour % 24).padStart(2, '0')}:00`,
        temperature: parseInt(hour.tempC),
        rainProbability: parseInt(hour.chanceofrain),
        rainIntensity: getRainIntensity(parseInt(hour.chanceofrain)),
        condition: translateCondition(hour.weatherDesc[0].value)
      });
    });

    return forecast;
  };

  // Função para buscar dados meteorológicos do Rio de Janeiro
  const fetchWeatherData = async () => {
    try {
      setLoading(true);
      setError(null);

      // Usando wttr.in - API meteorológica gratuita em português
      const response = await fetch('https://wttr.in/Rio+de+Janeiro?format=j1&lang=pt');
      
      if (!response.ok) {
        throw new Error(`Erro HTTP: ${response.status}`);
      }

      const data = await response.json();
      const current = data.current_condition[0];
      const today = data.weather[0];
      
      // Processar dados para alertas
      const alerts = generateWeatherAlerts(current, today);
      const forecast = generateHourlyForecast(data.weather);

      const weatherInfo: WeatherData = {
        temperature: parseInt(current.temp_C),
        humidity: parseInt(current.humidity),
        windSpeed: parseInt(current.windspeedKmph),
        windDirection: current.winddir16Point,
        condition: translateCondition(current.weatherDesc[0].value),
        rainProbability: parseInt(today.hourly[0].chanceofrain),
        rainIntensity: getRainIntensity(parseInt(today.hourly[0].chanceofrain)),
        pressure: parseInt(current.pressure),
        visibility: parseInt(current.visibility),
        uvIndex: parseInt(current.uvIndex || '0'),
        alerts,
        forecast
      };

      setWeatherData(weatherInfo);
      setLastUpdated(new Date());
    } catch (err) {
      console.error('Erro ao buscar dados meteorológicos:', err);
      setError('Serviços meteorológicos indisponíveis temporariamente');
    } finally {
      setLoading(false);
    }
  };

  // Buscar dados ao carregar o componente
  useEffect(() => {
    fetchWeatherData();
    
    // Atualizar a cada 10 minutos
    const interval = setInterval(fetchWeatherData, 10 * 60 * 1000);
    
    return () => clearInterval(interval);
  }, []);

  // Determinar cor do alerta baseado na severidade
  const getAlertColor = (severity: string) => {
    switch (severity) {
      case 'extremo': return 'border-red-500 bg-red-50 text-red-800';
      case 'alto': return 'border-orange-500 bg-orange-50 text-orange-800';
      case 'moderado': return 'border-yellow-500 bg-yellow-50 text-yellow-800';
      default: return 'border-blue-500 bg-blue-50 text-blue-800';
    }
  };

  // Ícone baseado no tipo de alerta
  const getAlertIcon = (type: string) => {
    switch (type) {
      case 'rain': return '🌧️';
      case 'storm': return '⛈️';
      case 'wind': return '💨';
      case 'heat': return '🌡️';
      case 'cold': return '🥶';
      case 'fog': return '🌫️';
      default: return '⚠️';
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          🌤️ Condições Meteorológicas - Rio de Janeiro
        </CardTitle>
        <CardDescription>
          Monitoramento em tempo real com alertas de segurança
          {lastUpdated && (
            <span className="text-xs block mt-1">
              Última atualização: {lastUpdated.toLocaleTimeString('pt-BR')}
            </span>
          )}
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-4">
        {loading && (
          <Alert className="border-blue-200 bg-blue-50">
            <AlertTitle className="text-blue-800">
              Carregando dados meteorológicos...
            </AlertTitle>
          </Alert>
        )}

        {error && (
          <Alert className="border-red-200 bg-red-50">
            <AlertTitle className="text-red-800">
              ⚠️ Erro de Conexão
            </AlertTitle>
            <AlertDescription className="text-red-700">
              {error}
            </AlertDescription>
          </Alert>
        )}

        {weatherData && (
          <>
            {/* Condições Atuais */}
            <Alert className="border-green-200 bg-green-50">
              <AlertTitle className="text-green-800 flex items-center gap-2">
                🌤️ Condições Atuais
              </AlertTitle>
              <AlertDescription className="text-green-700">
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                  <div className="text-center">
                    <div className="text-2xl font-bold">{weatherData.temperature}°C</div>
                    <div className="text-xs">{weatherData.condition}</div>
                  </div>
                  <div className="text-center">
                    <div className="text-lg font-bold">{weatherData.rainProbability}%</div>
                    <div className="text-xs">Chuva</div>
                  </div>
                  <div className="text-center">
                    <div className="text-lg font-bold">{weatherData.windSpeed} km/h</div>
                    <div className="text-xs">Vento</div>
                  </div>
                  <div className="text-center">
                    <div className="text-lg font-bold">{weatherData.humidity}%</div>
                    <div className="text-xs">Umidade</div>
                  </div>
                </div>
              </AlertDescription>
            </Alert>

            {/* Alertas Ativos */}
            {weatherData.alerts.length > 0 && (
              <div className="space-y-3">
                <h3 className="font-medium text-gray-800">🚨 Alertas Ativos:</h3>
                {weatherData.alerts.map((alert, index) => (
                  <Alert key={index} className={getAlertColor(alert.severity)}>
                    <AlertTitle className="flex items-center gap-2">
                      {getAlertIcon(alert.type)} {alert.title}
                      <Badge variant="outline" className="ml-auto">
                        {alert.severity.toUpperCase()}
                      </Badge>
                    </AlertTitle>
                    <AlertDescription>
                      <div className="space-y-2">
                        <p>{alert.description}</p>
                        <div className="bg-white/50 p-2 rounded text-sm">
                          <strong>Dicas de Segurança:</strong>
                          <ul className="list-disc list-inside mt-1 space-y-1">
                            {alert.safetyTips.map((tip, tipIndex) => (
                              <li key={tipIndex}>{tip}</li>
                            ))}
                          </ul>
                        </div>
                      </div>
                    </AlertDescription>
                  </Alert>
                ))}
              </div>
            )}

            {/* Previsão das Próximas Horas */}
            {weatherData.forecast.length > 0 && (
              <div className="space-y-3">
                <h3 className="font-medium text-gray-800">⏰ Próximas Horas:</h3>
                <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                  {weatherData.forecast.slice(0, 6).map((hour, index) => (
                    <div key={index} className="bg-gray-50 p-3 rounded-lg text-center">
                      <div className="font-bold text-lg">{hour.time}</div>
                      <div className="text-sm">{hour.temperature}°C</div>
                      <div className="text-xs text-blue-600">
                        {hour.rainProbability}% chuva
                      </div>
                      <div className="text-xs text-gray-600 mt-1">
                        {hour.condition}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Status sem alertas */}
            {weatherData.alerts.length === 0 && (
              <Alert className="border-green-200 bg-green-50">
                <AlertTitle className="text-green-800 flex items-center gap-2">
                  ✅ Condições Normais
                </AlertTitle>
                <AlertDescription className="text-green-700">
                  Não há alertas meteorológicos ativos para a região do Rio de Janeiro.
                  Condições climáticas dentro da normalidade.
                </AlertDescription>
              </Alert>
            )}
          </>
        )}

        {/* Botão de atualização manual */}
        <div className="pt-2 border-t">
          <button
            onClick={fetchWeatherData}
            disabled={loading}
            className="text-sm text-blue-600 hover:text-blue-800 disabled:text-gray-400"
          >
            {loading ? 'Atualizando...' : '🔄 Atualizar dados meteorológicos'}
          </button>
        </div>
      </CardContent>
    </Card>
  );
};