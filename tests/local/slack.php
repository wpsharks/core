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

- [ ] An unchecked list item.
- [x] A checked list `item`.
  - [ ] A nested list item.
    - [ ] A nested list item.

  * [ ] Unchecked with bullet.
  * [x] Checked with bullet.

- Normal list item.
  - Nested list item.

* Normal list item with bullet.
* Normal list item with bullet.

## BEHAVIOR THAT I EXPECTED

That it should work like this...

```php
<?php
echo \'123\';
?>
```

## BEHAVIOR THAT I OBSERVED

The clickable [`code`](http://example.com) did not work properly.
';
echo c::slackMrkdwn($markdown, [
    'current_gfm_owner' => 'owner',
    'current_gfm_repo'  => 'repo',
    'is_gfm'            => true,
]);
