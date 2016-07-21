<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test {

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

define('WPINC', true);

$GLOBALS['shortcode_tags'] = [
    'if' => 'callback',
];

$markup = <<<MARKUP

    First line.

    <pre>
        Preformatted text.
    </pre>

    ```
    Fenced code block in markdown.
    ```

    ```php
    Fenced code block in markdown.
    ```

    `inline code`

    ![](/an/image/path.png)

    [click](/a/link)

    [if]![](/an/image/path.png)[/if]

    <samp>
        [if]![](/an/image/path.png)[/if]
    </samp>

    <a href="#">click</a>

    <p>A paragraph.</p>

MARKUP;

c::dump(c::isWordPress());
$Tokenizer = c::tokenize(
    $markup,
    [
        'shortcodes',
        'pre',
        'code',
        'samp',
        'md-fences',
        'md-links'
    ],
    [
        'shortcode_unautop_compat' => true,
    ]
);
echo $Tokenizer->getString()."\n\n";
echo '-------------------------------------------'."\n\n";
echo $Tokenizer->restoreGetString();

/* ------------------------------------------------------------------------------------------------------------------ */

}

namespace {
    function get_shortcode_regex($tagnames = null)
    {
        global $shortcode_tags;

        if (empty($tagnames)) {
            $tagnames = array_keys($shortcode_tags);
        }
        $tagregexp = implode('|', array_map('preg_quote', $tagnames));

        // WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
        // Also, see shortcode_unautop() and shortcode.js.
        return
              '\\['                              // Opening bracket
                .'(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
                ."($tagregexp)"                     // 2: Shortcode name
                .'(?![\\w-])'                       // Not followed by word character or hyphen
                .'('                                // 3: Unroll the loop: Inside the opening shortcode tag
                .'[^\\]\\/]*'                   // Not a closing bracket or forward slash
                .'(?:'
                                    .'\\/(?!\\])'               // A forward slash not followed by a closing bracket
                .'[^\\]\\/]*'               // Not a closing bracket or forward slash
                .')*?'
                                    .')'
                                    .'(?:'
                                    .'(\\/)'                        // 4: Self closing tag ...
                .'\\]'                          // ... and closing bracket
                .'|'
                                    .'\\]'                          // Closing bracket
                .'(?:'
                                    .'('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
                .'[^\\[]*+'             // Not an opening bracket
                .'(?:'
                                    .'\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
                .'[^\\[]*+'         // Not an opening bracket
                .')*+'
                                    .')'
                                    .'\\[\\/\\2\\]'             // Closing shortcode tag
                .')?'
            .')'
            .'(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }
}
