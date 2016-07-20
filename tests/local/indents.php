<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$markdown = <<<MARKDOWN

    ## Heading One

    This is some test.

        <script>
            Block of code.
        </script>

    ## Another Heading

    Some more text.

MARKDOWN;

echo $markdown."\n\n";
echo '-------------------'."\n\n";
echo c::stripLeadingIndents($markdown);
