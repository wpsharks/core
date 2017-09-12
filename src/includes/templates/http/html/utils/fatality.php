<?php
/**
 * Template.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

extract($this->setVars([
    'message' => __('Unknown error, please try again.'),
]));
?>
<!DOCTYPE html>
<html>
<head>
    <title>!</title>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,nofollow" />

    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/css/hash/default-app.min.css')); ?>" />
    <script defer src="<?= $this->c::escUrl($this->c::appWsCoreUrl('/client-s/js/hash/default-app.min.js')); ?>"></script>
</head>
<body>
    <a-theme>
        <a-foundation>
            <a-slab>
                <a-structure>
                    <structure-body>
                        <a-flex class="~ai:center ~vai:middle #stretch:vh">
                            <a-tile class="b#red">
                                <?= $message; ?>
                            </a-tile>
                        </a-flex>
                    </structure-body>
                </a-structure>
            </a-slab>
        </a-foundation>
    </a-theme>
</body>
</html>
