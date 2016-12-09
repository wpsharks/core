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

Stripe Pro Form add Tax code

```text
CH=8.0%
US=1.0%
```

## BEHAVIOR THAT I EXPECTED

When I select the Country US or CH the tax should be calculated.
On checkout tax calculation is not happening

## BEHAVIOR THAT I OBSERVED

I set state (CH) zip code (8000) and country (CH)
The calculated Tax and price is always 0

Same happens also for US

No error or issues in the logs however I see an not on stripe metadata `tax_info
{"tax":"4.68","tax_per":"1%"}`
';
echo c::slackMrkdwn($markdown, [
    'current_gfm_owner' => 'owner',
    'current_gfm_repo'  => 'repo',
    'is_gfm'            => true,
]);
