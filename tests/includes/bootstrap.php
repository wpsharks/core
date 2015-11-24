<?php
declare (strict_types = 1);
namespace WebSharks\Core;

const DEBUG             = true;
const DEBUG_TEST_CONFIG = true;

require_once dirname(__FILE__, 3).'/src/includes/init.php';
$App = $GLOBALS[__NAMESPACE__]; // App instance.
