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

## TEST FOR ESCAPE SEQUENCES

Here is a ``literal backtick` within inline code``, followed by some more `code`.

Here is a link in HTML brackets: <//example.com> and <http://another.com>.

Here is a link to [Google][google].
Here is a link to [\[Google\]][google] in brackets.
Here is an inline link to [Google](http://googlel.com).
Here is an inline link to [\[Google\]](http://googlel.com) in brackets.
Here is an inline link to [\[Google\]](http://googlel.com#\(\)) in brackets, w/ brackets in the URL also.

Here is an image ![alt text in \[brackets\]](/path/to/image.png).
Here is a clickable image [![alt text in \[brackets\]](/path/to/image.png)](http://googlel.com#\(\)).

```
Some code and a backtick `.
```

```php
Some code and a backtick `.
```

```php {.no-hljs}
Some code and a backtick `.
```

## SOME DEFINITIONS

*[ABBR]: Abbreviation definition.
[^1]: A footnote definition that is referenced above.
[google]: <http://google.com> "Link Definition"
[github]: https://github.com "GitHub Link Definition"

Most of our Products are also hosted by GitHub, so another way to obtain the latest available release of most Products is by visiting [github.com/src-works](https://github.com/src-works). There you\'ll find download links, installation instructions, and, at times, other resources.
';
$Tokenizer = c::tokenize($markdown, [
    'pre',
    'code',
    'samp',
    'anchors',
    'tags',
    'md-fences',
    'md-links',
    'shortcodes',
]);
echo $Tokenizer->getString()."\n\n";
echo '--------------------------------------'."\n\n";
echo $Tokenizer->restoreGetString();
