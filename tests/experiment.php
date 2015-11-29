<?php
declare (strict_types = 1);
namespace WebSharks\Core\Test;

require_once dirname(__FILE__).'/includes/bootstrap.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$a = ['hi' => 'hi!', 'hello' => 'hello!'];
extract(array_replace_recursive(['hello' => 'world'], $a));
echo $hello;
