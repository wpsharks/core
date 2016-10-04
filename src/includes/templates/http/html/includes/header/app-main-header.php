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
<div class="ui container entry-header app-main-header">
    <div class="ui stackable grid">

        <div class="twelve wide column -left-side">
            <h1 class="ui header entry-title -title"><?= $body['header']['title']; ?></h1>
        </div>

        <div class="four wide large screen only right aligned column -right-side">
            &nbsp;
        </div>

    </div>
</div>
