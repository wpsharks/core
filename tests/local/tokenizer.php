<?php
declare(strict_types=1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$markdown = '
## EXPLANATION OF THE ISSUE

Latest version as of today; see [hello](http://example.com)

## STEPS TO REPRODUCE THE ISSUE

- [ ] This is a list item with a <link>.
- [x] This is a list item with an issue #123 reference.
- [ ] This is a list item with *bold*, _italic_, and ~~strike~~ text.
* [x] This is a list item with an <http://example.com> URL.
* [ ] This is a list item with a [clickable](http://example.com) link.

## BEHAVIOR THAT I EXPECTED

That it should work like this...

```php
<?php
echo \'123\';
?>
```

## BEHAVIOR THAT I OBSERVED

The clickable [`code` link](http://example.com) did not work properly.
';
$Tokenizer = c::tokenize($markdown, [
    'pre',
    'code',
    'samp',
    'anchors',
    'tags',
    'md-fences',
    'md-links',
]);
echo $Tokenizer->getString()."\n\n";
echo '--------------------------------------'."\n\n";
echo $Tokenizer->restoreGetString();
