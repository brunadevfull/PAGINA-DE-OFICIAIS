import { GlobalWorkerOptions } from 'pdfjs-dist/build/pdf';
import pdfWorker from 'pdfjs-dist/build/pdf.worker?url';

// Configurar o worker do PDF.js usando o arquivo empacotado localmente
const workerSrc = new URL(pdfWorker, import.meta.url).toString();

GlobalWorkerOptions.workerSrc = workerSrc;

export default GlobalWorkerOptions;
