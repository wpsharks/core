<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Classes\Exception;

/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'Exception' => null,

    'http/html/header.php' => [
        'head' => [
            'title' => $this->__('Internal Server Error'),
        ],
    ],
];
extract($this->setVars($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
<?= $this->get('http/html/header.php') ?>

<img src="<?= $this->escUrl($this->Utils->Url->toAppCore('/client-s/http/status/500.png')) ?>" />

<?= $this->get('http/html/footer.php') ?>
