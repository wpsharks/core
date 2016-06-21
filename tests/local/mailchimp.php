<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump(c::mailchimpSubscribe('noreply@wsharks.com', [
    'merge_fields' => [
        'FNAME' => 'No',
        'LNAME' => 'Reply',
    ],
    'status'    => 'pending',
    'ip_signup' => '166.170.41.162',
]));
