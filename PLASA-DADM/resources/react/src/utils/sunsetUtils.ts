import SunCalc from 'suncalc';

// Coordenadas do Rio de Janeiro (sede do PAPEM)
const LAT = -22.9068;
const LON = -43.1729;

function getSunsetTime(date: Date = new Date()): string {
  const times = SunCalc.getTimes(date, LAT, LON);
  const h = times.sunset.getHours().toString().padStart(2, '0');
  const m = times.sunset.getMinutes().toString().padStart(2, '0');
  return `${h}:${m}`;
}

export async function getTodaySunset(): Promise<string> {
  return getSunsetTime();
}

export async function getSunsetWithLabel(): Promise<string> {
  return `Pôr do sol: ${getSunsetTime()}`;
}

export async function forceUpdateSunset(): Promise<string> {
  return getSunsetTime();
}

export function clearSunsetCache(): void {
  // SunCalc calcula em tempo real, sem cache necessário
}

export function getSunsetForDate(dateString: string): string {
  return getSunsetTime(new Date(dateString));
}

export function getCHMSunsetTime(): string {
  return getSunsetTime();
}
