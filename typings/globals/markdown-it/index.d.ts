interface MarkdownIt {
  render(md: string, env?: any): string;
}
interface MarkdownItOptions {
  html?: boolean;
  xhtmlOut?: boolean;
  breaks?: boolean;
  langPrefix?: string;
  linkify?: boolean;
  typographer?: boolean;
  quotes?: string;
  highlight?: (code: string, lang: string) => string;
}
interface MarkdownItStatic {
  (options: MarkdownItOptions): MarkdownIt;
}
declare var markdownit: MarkdownItStatic;
