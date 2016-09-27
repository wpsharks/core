<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

?>
<div class="ui center aligned container app-main-footer">
    <div class="ui vertical footer segment">

        <div class="ui section divider"></div>

        <div class="ui stackable divided grid">

            <div class="eight wide column -left-side">
                <p class="-copyright">
                    © <?= date('Y'); ?> <?= $this->c::escHtml($body['brand']['name']); ?> <?= __('"All Rights Reserved"'); ?>
                </p>
            </div>

            <div class="eight wide large screen only column -right-side">
                &nbsp;
            </div>

        </div>

    </div>
</div>
