<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$markdown = <<<MARKDOWN

                <br />
            <p>&nbsp;</p>
            &nbsp;
                ## Heading One

                This is some text.

                    <script>
                        Block of code.
                    </script>

                ```
                    Testing.
                ```

                [if]
                    Testing.
                [/if]

                <pre>
                    hello
                </pre>

                ## Another Heading

                Some more text.

                <br />
            <p>&nbsp;</p>
            &nbsp;

MARKDOWN;

echo $markdown."\n\n";
echo '-------------------'."\n\n";
echo c::stripLeadingIndents($markdown);
