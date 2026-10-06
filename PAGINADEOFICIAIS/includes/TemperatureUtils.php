<?php
/**
 * Utilitários para obter temperatura do Rio de Janeiro
 * Adaptado para PHP baseado em código TypeScript fornecido
 */

class TemperatureUtils {
    
    const CACHE_DURATION = 30 * 60; // 30 minutos em segundos
    const RIO_COORDS = ['lat' => -22.8975, 'lon' => -43.1641]; // Ilha Fiscal
    const CACHE_FILE = __DIR__ . '/../cache/temperature_cache.json';
    
    /**
     * Traduz descrições do clima do inglês para português
     */
    public static function translateWeatherDescription($description) {
        $translations = [
            // Condições básicas
            'clear' => 'ensolarado',
            'sunny' => 'ensolarado', 
            'clear sky' => 'céu limpo',
            'few clouds' => 'poucas nuvens',
            'scattered clouds' => 'nuvens dispersas',
            'broken clouds' => 'nuvens fragmentadas',
            'overcast clouds' => 'nublado',
            'overcast' => 'nublado',
            'cloudy' => 'nublado',
            'partly cloudy' => 'parcialmente nublado',
            
            // Chuva
            'light rain' => 'chuva fraca',
            'moderate rain' => 'chuva moderada',
            'heavy rain' => 'chuva forte',
            'shower rain' => 'chuva rápida',
            'rain' => 'chuva',
            'drizzle' => 'garoa',
            'light intensity drizzle' => 'garoa fraca',
            'heavy intensity drizzle' => 'garoa forte',
            
            // Tempestades
            'thunderstorm' => 'tempestade',
            'thunderstorm with light rain' => 'tempestade com chuva fraca',
            'thunderstorm with rain' => 'tempestade com chuva',
            'thunderstorm with heavy rain' => 'tempestade com chuva forte',
            
            // Neve (raro no Rio, mas pode aparecer)
            'snow' => 'neve',
            'light snow' => 'neve fraca',
            
            // Outras condições  
            'mist' => 'neblina',
            'fog' => 'nevoeiro',
            'haze' => 'névoa seca',
            'dust' => 'poeira',
            'smoke' => 'fumaça',
            'mostly cloudy' => 'muito nublado',
            
            // Fallbacks comuns da API
            'temperature not available' => 'temperatura não disponível',
            'weather data unavailable' => 'dados meteorológicos indisponíveis'
        ];
        
        $lowerDescription = strtolower(trim($description));
        return $translations[$lowerDescription] ?? $lowerDescription;
    }
    
    /**
     * Verifica se o cache é válido
     */
    private static function isCacheValid() {
        if (!file_exists(self::CACHE_FILE)) {
            return false;
        }
        
        $cacheData = json_decode(file_get_contents(self::CACHE_FILE), true);
        if (!$cacheData || !isset($cacheData['timestamp'])) {
            return false;
        }
        
        return (time() - $cacheData['timestamp']) < self::CACHE_DURATION;
    }
    
    /**
     * Lê dados do cache
     */
    private static function readCache() {
        if (!file_exists(self::CACHE_FILE)) {
            return null;
        }
        
        $cacheData = json_decode(file_get_contents(self::CACHE_FILE), true);
        return $cacheData['data'] ?? null;
    }
    
    /**
     * Salva dados no cache
     */
    private static function saveCache($data) {
        $cacheDir = dirname(self::CACHE_FILE);
        if (!is_dir($cacheDir) && !@mkdir($cacheDir, 0755, true)) {
            error_log("⚠️ Não foi possível criar a pasta de cache: {$cacheDir}");
            return;
        }
        
        $cacheData = [
            'data' => $data,
            'timestamp' => time()
        ];
        
        @file_put_contents(self::CACHE_FILE, json_encode($cacheData));
    }
    
    /**
     * Obtém temperatura atual do Rio de Janeiro
     * Retorna dados do cache se ainda válidos (menos de 30 min)
     */
    public static function getCurrentTemperature() {
        // Verificar se cache ainda é válido
        if (self::isCacheValid()) {
            error_log("🌡️ Usando temperatura do cache");
            return self::readCache();
        }
        
        error_log("🌡️ Buscando temperatura atualizada...");
        
        // Tentar múltiplas APIs em sequência
        $apis = [
            'getTemperatureFromWttr',
            'getTemperatureFromOpenMeteo'
        ];
        
        foreach ($apis as $apiMethod) {
            try {
                $result = self::$apiMethod();
                if ($result) {
                    self::saveCache($result);
                    return $result;
                }
            } catch (Exception $e) {
                error_log("🌡️ Tentando próxima API... Erro: " . $e->getMessage());
            }
        }
        
        // Todas as APIs falharam: usa o último dado em cache (mesmo vencido) ou null.
        // Não grava valor fictício no cache.
        error_log("⚠️ Todas as APIs falharam");
        return self::readCache();
    }
    
    /**
     * GET HTTP via cURL (IPv4, timeouts curtos). file_get_contents falhava
     * no handshake SSL neste servidor, então cURL é preferido.
     */
    private static function httpGet($url) {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'PAPEM Weather Client 1.0',
            ]);
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ($response !== false && $status === 200) ? $response : false;
        }

        $context = stream_context_create(['http' => [
            'timeout' => 10,
            'user_agent' => 'PAPEM Weather Client 1.0'
        ]]);
        return @file_get_contents($url, false, $context);
    }

    /**
     * wttr.in - API internacional gratuita
     */
    private static function getTemperatureFromWttr() {
        error_log("🌡️ Tentando wttr.in...");
        
        $response = self::httpGet('https://wttr.in/Rio+de+Janeiro?format=j1');
        
        if ($response === false) {
            throw new Exception("Falha na requisição wttr.in");
        }
        
        $data = json_decode($response, true);
        if (!$data || !isset($data['current_condition'][0])) {
            throw new Exception("Dados inválidos da wttr.in");
        }
        
        $current = $data['current_condition'][0];
        
        $weatherData = [
            'temp' => (int)$current['temp_C'],
            'description' => self::translateWeatherDescription($current['weatherDesc'][0]['value']),
            'icon' => '01d',
            'humidity' => (int)$current['humidity'],
            'feelsLike' => (int)$current['FeelsLikeC']
        ];
        
        error_log("🌡️ Temperatura obtida via wttr.in: {$weatherData['temp']}°C");
        return $weatherData;
    }
    
    /**
     * Open-Meteo - API europeia gratuita sem chave
     */
    private static function getTemperatureFromOpenMeteo() {
        error_log("🌡️ Tentando Open-Meteo...");
        
        $lat = self::RIO_COORDS['lat'];
        $lon = self::RIO_COORDS['lon'];
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current_weather=true&hourly=relativehumidity_2m&timezone=America/Sao_Paulo";
        
        $response = self::httpGet($url);
        
        if ($response === false) {
            throw new Exception("Falha na requisição Open-Meteo");
        }
        
        $data = json_decode($response, true);
        if (!$data || !isset($data['current_weather'])) {
            throw new Exception("Dados inválidos da Open-Meteo");
        }
        
        $current = $data['current_weather'];
        
        // Determinar descrição baseada no código do tempo
        $weatherCode = $current['weathercode'];
        $description = 'condições variáveis';
        if ($weatherCode === 0) $description = 'céu limpo';
        elseif ($weatherCode <= 2) $description = 'parcialmente nublado';
        elseif ($weatherCode === 3) $description = 'nublado';
        elseif ($weatherCode <= 67) $description = 'chuva';
        elseif ($weatherCode <= 77) $description = 'neve';
        elseif ($weatherCode <= 82) $description = 'chuva';
        elseif ($weatherCode <= 99) $description = 'tempestade';
        
        $weatherData = [
            'temp' => round($current['temperature']),
            'description' => $description,
            'icon' => '01d',
            'humidity' => round($data['hourly']['relativehumidity_2m'][0] ?? 65),
            'feelsLike' => round($current['temperature'])
        ];
        
        error_log("🌡️ Temperatura obtida via Open-Meteo: {$weatherData['temp']}°C");
        return $weatherData;
    }
    
    /**
     * Força atualização da temperatura (ignora cache)
     */
    public static function refreshTemperature() {
        if (file_exists(self::CACHE_FILE)) {
            unlink(self::CACHE_FILE);
        }
        return self::getCurrentTemperature();
    }
    
    /**
     * Verifica se os dados de temperatura estão atualizados
     */
    public static function isTemperatureCacheValid() {
        return self::isCacheValid();
    }
}
