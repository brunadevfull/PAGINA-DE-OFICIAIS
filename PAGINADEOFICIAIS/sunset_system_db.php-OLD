<?php
/**
 * Sistema de Pôr do Sol CHM
 *
 * Calcula o horário astronomicamente com date_sun_info() para a Ilha Fiscal
 * (diferença máxima de 1 minuto em relação à tábua do CHM). Não depende de banco.
 */

class SunsetSystemDB {
    const LAT = -22.8975;   // Ilha Fiscal (mesma coordenada do TemperatureUtils)
    const LON = -43.1641;
    const TZ  = 'America/Sao_Paulo';

    /**
     * Mantido por compatibilidade: o cálculo não usa mais banco de dados
     */
    public static function init($pdo = null) {
    }

    /**
     * Obter horário do pôr do sol para hoje
     */
    public static function getTodaysSunsetTime() {
        return self::getSunsetTimeForDate(self::today());
    }

    /**
     * Obter horário do pôr do sol para uma data específica (HH:MM)
     */
    public static function getSunsetTimeForDate($date) {
        $date = $date ? date('Y-m-d', strtotime($date)) : self::today();
        return self::calculateSunset($date);
    }

    /**
     * Verificar se já passou do pôr do sol hoje
     */
    public static function hasSunsetPassedToday() {
        $tz = new DateTimeZone(self::TZ);
        $now = new DateTime('now', $tz);

        list($hours, $minutes) = explode(':', self::getTodaysSunsetTime());
        $sunsetTime = new DateTime('now', $tz);
        $sunsetTime->setTime((int)$hours, (int)$minutes);

        return $now > $sunsetTime;
    }

    /**
     * Obter informações completas do pôr do sol para uma data
     */
    public static function getSunsetInfo($date = null) {
        $date = $date ? date('Y-m-d', strtotime($date)) : self::today();

        return [
            'date' => $date,
            'sunset_time' => self::calculateSunset($date),
            'source' => 'Calculado',
            'notes' => 'Cálculo astronômico (Ilha Fiscal)',
            'has_passed' => ($date === self::today()) ? self::hasSunsetPassedToday() : false,
            'formatted_date' => date('d/m/Y', strtotime($date))
        ];
    }

    /**
     * Obter próximos 7 dias de horários
     */
    public static function getWeekSunsetTimes() {
        $results = [];
        foreach (self::nextDates(7) as $date) {
            $info = self::getSunsetInfo($date);
            $results[] = [
                'data' => $date,
                'por_do_sol' => $info['sunset_time'],
                'fonte' => $info['source'],
                'formatted_date' => $info['formatted_date']
            ];
        }
        return $results;
    }

    /**
     * Gerar dados JSON para JavaScript (próximos 30 dias)
     */
    public static function getJavaScriptData() {
        $jsData = [];
        foreach (self::nextDates(31) as $date) {
            $jsData[$date] = self::getSunsetTimeForDate($date);
        }
        return json_encode($jsData);
    }

    /**
     * Debug: verificar se o sistema está funcionando
     */
    public static function debug() {
        $info = self::getSunsetInfo();
        return [
            'status' => 'OK',
            'today_date' => $info['date'],
            'today_sunset' => $info['sunset_time'],
            'source' => $info['source'],
            'has_passed' => $info['has_passed']
        ];
    }

    /**
     * Cálculo astronômico do pôr do sol (HH:MM, horário de Brasília)
     */
    public static function calculateSunset($date) {
        $tz = new DateTimeZone(self::TZ);
        $noon = new DateTime("$date 12:00", $tz);
        $sun = date_sun_info($noon->getTimestamp(), self::LAT, self::LON);

        $sunset = new DateTime('@' . $sun['sunset']);
        $sunset->setTimezone($tz);
        return $sunset->format('H:i');
    }

    private static function today() {
        return (new DateTime('now', new DateTimeZone(self::TZ)))->format('Y-m-d');
    }

    private static function nextDates($days) {
        $date = new DateTime('now', new DateTimeZone(self::TZ));
        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            $dates[] = $date->format('Y-m-d');
            $date->modify('+1 day');
        }
        return $dates;
    }
}

// Função global para compatibilidade
function getSunsetTime($date = null) {
    return SunsetSystemDB::getSunsetTimeForDate($date);
}
?>
