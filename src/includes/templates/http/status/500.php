<?php
/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'header' => [
        'head' => [
            'title' => $this->__('Internal Server Error'),
        ],
    ],
    'footer' => [],
];
extract(array_replace_recursive($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
<?= $this->Utils->Template->get('header.php')->parse($header) ?>

<img src="<?= $this->escUrl($this->Utils->Url->toCur('/vendor/websharks/core/src/client-s/http/status/500.png')) ?>" alt="" />

Unexpected error. Please contact us if you need help.

<?= $this->Utils->Template->get('footer.php')->parse($footer) ?>
