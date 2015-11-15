<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Dicer\Di;
use WebSharks\Core\Classes;

error_reporting(-1);
ini_set('display_errors', 'yes');

require_once dirname(dirname(__FILE__)).'/src/includes/stub.php';

class overload extends Classes\AbsBase
{
    public function __construct($obj)
    {
        parent::__construct();

        $this->overload($obj, true);
    }
}

$obj = (object) [
    'var' => '_',
    'hi'  => 'hi!',
];
$Di       = new Di();
$overload = $Di->get(overload::class, ['obj' => $obj]);

$obj->var = '1';
echo $obj->var;
echo $overload->var."\n";

$overload->var = '2';
echo $obj->var;
echo $overload->var."\n";
