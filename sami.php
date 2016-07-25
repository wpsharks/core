<?php
use Sami\Sami;
use Symfony\Component\Finder\Finder;

return new Sami(Finder::create()
    ->files()->name('/\.php$/ui')
    ->notPath('/^vendor\/ui')
    ->in(__DIR__.'/src'), [
        'theme'     => 'default',
        'title'     => 'WebSharks Core',
        'build_dir' => __DIR__.'/.~build/codex',
        'cache_dir' => __DIR__.'/.~build/.~/sami/cache',
    ]);
