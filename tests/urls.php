<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

echo $App->Utils->Url->toApp('/client-s/test.js?v=34#core');
