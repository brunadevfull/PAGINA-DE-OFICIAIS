declare module 'pdfjs-dist/build/pdf' {
  export const GlobalWorkerOptions: any;
  export const version: string;
  export function getDocument(...args: any[]): any;
  export type PDFDocumentProxy = any;
  export type PDFPageProxy = any;
  const pdfjs: any;
  export default pdfjs;
}

declare module 'pdfjs-dist/build/pdf.worker' {
  const worker: any;
  export default worker;
}
