<?php
/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'body' => [
        'scripts' => [
            'v' => $this->App::VERSION,
        ],
        'extras' => '',
    ],
    'inc' => [],
];
extract(array_replace_recursive($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
        <?= $this->Utils->Template->get('http/html/footer.inc.php')->parse($inc) ?>

        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->UrlScheme->set('//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js')) ?>"></script>
        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->UrlScheme->set('//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.6/semantic.min.js')) ?>"></script>
        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->Url->toApp('/vendor/websharks/core/src/client-s/js/core.min.js?v='.urlencode($body['scripts']['v']))) ?>"></script>
        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->Url->toApp('/client-s/js/app.min.js?v='.urlencode($body['scripts']['v']))) ?>"></script>

        <?= $body['extras'] ?>
    </body>
</html>
