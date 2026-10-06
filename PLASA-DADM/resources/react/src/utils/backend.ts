const ABSOLUTE_WITH_SCHEME = /^https?:\/\//i;
const SPECIAL_PROTOCOL = /^(?:blob|data):/i;

const DEFAULT_BACKEND_PORT = (import.meta.env.VITE_BACKEND_PORT ?? '5001').trim();
const EXPLICIT_BACKEND_BASE = import.meta.env.VITE_BACKEND_URL?.trim();
const DEFAULT_BACKEND_SCHEME = 'http:';

const stripTrailingSlashes = (value: string) => value.replace(/\/+$/, '');
const ensureLeadingSlash = (value: string) => (value.startsWith('/') ? value : `/${value}`);

const joinBaseAndPath = (base: string, path: string) => {
  const normalizedBase = stripTrailingSlashes(base);
  if (!path) {
    return normalizedBase;
  }

  const normalizedPath = ensureLeadingSlash(path);
  return `${normalizedBase}${normalizedPath}`;
};

const buildBaseFromLocation = (): string | null => {
  if (typeof window === 'undefined') {
    return null;
  }

  const { hostname } = window.location;
  const isLocalHost = hostname === 'localhost' || hostname === '127.0.0.1';

  if (!isLocalHost) {
    return window.location.origin;
  }

  const port = DEFAULT_BACKEND_PORT ? `:${DEFAULT_BACKEND_PORT}` : '';
  return `${DEFAULT_BACKEND_SCHEME}//${hostname}${port}`;
};

const resolveBaseUrl = (): string | null => {
  if (EXPLICIT_BACKEND_BASE) {
    if (ABSOLUTE_WITH_SCHEME.test(EXPLICIT_BACKEND_BASE)) {
      return stripTrailingSlashes(EXPLICIT_BACKEND_BASE);
    }

    return `${DEFAULT_BACKEND_SCHEME}//${stripTrailingSlashes(EXPLICIT_BACKEND_BASE)}`;
  }

  return buildBaseFromLocation();
};

const rebuildWithBase = (base: string | null, url: URL): string => {
  if (!base) {
    return url.toString();
  }

  return joinBaseAndPath(base, `${url.pathname}${url.search}${url.hash}`);
};

export const resolveBackendUrl = (path: string): string => {
  if (!path) {
    return path;
  }

  if (SPECIAL_PROTOCOL.test(path)) {
    return path;
  }

  const baseUrl = resolveBaseUrl();

  if (ABSOLUTE_WITH_SCHEME.test(path)) {
    try {
      const url = new URL(path);

      if (url.hostname === 'localhost' || url.hostname === '127.0.0.1') {
        return rebuildWithBase(baseUrl, url);
      }

      return url.toString();
    } catch {
      return path;
    }
  }

  if (!baseUrl) {
    return path;
  }

  return joinBaseAndPath(baseUrl, path);
};

export const getBackendBaseUrl = (): string | null => resolveBaseUrl();
