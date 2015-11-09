<?php
namespace WebSharks\Core;

use WebSharks\Core\Classes as Classes;
use WebSharks\Dicer\Core as Dicer;

ini_set('error_reporting', -1);
ini_set('display_errors', true);

require_once dirname(dirname(__FILE__)).'/src/includes/stub.php';

class overload extends Classes\AbsBase
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        
        $this->data = (object) [
            'a' => 'a!',
        ];
        $this->public_overload($this->data);
    }

    protected function public_overload($properties)
    {
        if (!is_array($properties) && !is_object($properties)) {
            throw new Exception(sprintf('Invalid type: `%1$s`.', gettype($properties)));
        }
        foreach ($properties as $_key_prop => &$_val_prop) {

            // Treat string keys as named property/value pairs.
            // Only nonexistent properties can be overloaded in this way.

            if (is_string($_key_prop)) {
                if (isset($_key_prop[0]) && !property_exists($this, $_key_prop)) {
                    $this->{$_key_prop} = &$_val_prop;
                }
            }
        } // unset($_key, $_value, $_property); // Housekeeping.
    }
}

$Di          = new Dicer();
$overload    = $Di->get(overload::class);
$overload->a = 'Abc';
echo $overload->a."\n";
echo $overload->getA()."\n";
