/// <reference path="../jquery/index.d.ts" />

interface JQueryAnimateCSSOptions {
  effect?: string;
  delay?: number;
  animationClass?: string;
  infinite?: boolean;
  callback?: Function;
  duration?: number;
  debug?: boolean;
}
interface JQuery {
  animateCSS(effect: string, options: JQueryAnimateCSSOptions): JQuery;
}
