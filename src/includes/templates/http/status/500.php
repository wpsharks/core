<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;

/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'Exception' => null,

    'http/html/header.php' => [
        'head' => [
            'title' => c\__('Internal Server Error'),
        ],
    ],
];
extract($this->setVars($¤defaults, $¤vars));
/*
 * Output template contents. @TODO Improve and test this.
 */ ?>
<?= $this->get('http/html/header.php') ?>

<p>
    <?= c\__('Internal server error. Please contact us for assistance.'); ?>
</p>

<?= $this->get('http/html/footer.php') ?>
