<?php
/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'body' => [
        'scripts' => [
            'v' => '',
        ],
        'extras' => '',
    ],
    'html' => [
        'extras' => '',
    ],
];
extract(array_replace_recursive($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->UrlScheme->set('//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js', 'current')) ?>"></script>
        <script type="text/javascript" src="<?= $this->escUrl($this->Utils->Url->toCur('/client-s/semantic/semantic.min.js?v='.urlencode($body['scripts']['v']))) ?>"></script>

        <?= $body['extras'] ?>
    </body>
    <?= $html['extras'] ?>
</html>
