<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\AppFacades as c;

error_reporting(-1);
ini_set('display_errors', 'yes');
require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump($App);
