<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes as Classes;
use WebSharks\Dicer\Core as Di;

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
echo $overload->var;
echo $overload->overload->var."\n";

$overload->overload->var = '2';
echo $obj->var;
echo $overload->var;
echo $overload->overload->var."\n";

$overload->overload->var = '3';
echo $obj->var;
echo $overload->var;
echo $overload->overload->var."\n";
