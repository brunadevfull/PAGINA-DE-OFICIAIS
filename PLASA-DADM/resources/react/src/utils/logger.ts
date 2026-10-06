/**
 * Logger utility with environment-based log levels
 * Production logs are minimal and structured
 */

type LogLevel = 'debug' | 'info' | 'warn' | 'error';

const isDevelopment = import.meta.env.DEV || process.env.NODE_ENV === 'development';

class Logger {
  private shouldLog(level: LogLevel): boolean {
    if (!isDevelopment && level === 'debug') {
      return false;
    }
    return true;
  }

  /**
   * Debug logs - only in development
   * Use for verbose logging during development
   */
  debug(message: string, ...args: any[]): void {
    if (this.shouldLog('debug')) {
      console.log(`[DEBUG] ${message}`, ...args);
    }
  }

  /**
   * Info logs - development and production
   * Use for important state changes
   */
  info(message: string, ...args: any[]): void {
    if (this.shouldLog('info')) {
      console.log(`[INFO] ${message}`, ...args);
    }
  }

  /**
   * Warning logs - always shown
   * Use for recoverable errors
   */
  warn(message: string, ...args: any[]): void {
    if (this.shouldLog('warn')) {
      console.warn(`[WARN] ${message}`, ...args);
    }
  }

  /**
   * Error logs - always shown
   * Use for critical errors
   */
  error(message: string, error?: Error | unknown, ...args: any[]): void {
    if (this.shouldLog('error')) {
      if (error instanceof Error) {
        console.error(`[ERROR] ${message}`, {
          message: error.message,
          stack: error.stack,
          ...args
        });
      } else {
        console.error(`[ERROR] ${message}`, error, ...args);
      }
    }
  }

  /**
   * Group start - only in development
   */
  group(label: string): void {
    if (isDevelopment) {
      console.group(label);
    }
  }

  /**
   * Group end - only in development
   */
  groupEnd(): void {
    if (isDevelopment) {
      console.groupEnd();
    }
  }
}

export const logger = new Logger();
export default logger;
