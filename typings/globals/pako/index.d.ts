interface PakoStatic {
  gzip(data: Uint8Array | Array<number> | string, options?: any): string;
  deflate(data: Uint8Array | Array<number> | string, options?: any): string;
  deflateRaw(data: Uint8Array | Array<number> | string, options?: any): string;
}
declare var pako: PakoStatic;
