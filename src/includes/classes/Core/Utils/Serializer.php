<?php
/**
 * Serializer.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use SuperClosure\Serializer as ClosureSerializer;
use SuperClosure\Analyzer\AstAnalyzer as ClosureAstAnalyzer;
use SuperClosure\Analyzer\TokenAnalyzer as ClosureTokenAnalyzer;

/**
 * Serializer.
 *
 * @since 150424 Serializer.
 */
class Serializer extends Classes\Core\Base\Core
{
    /**
     * Closure serializer.
     *
     * @since 160712
     *
     * @var ClosureSerializer
     */
    protected $ClosureAstSerializer;

    /**
     * Closure serializer (faster).
     *
     * @since 160712
     *
     * @var ClosureSerializer
     */
    protected $ClosureTokenSerializer;

    /**
     * Marker (for closures).
     *
     * @since 160712 Serializer.
     *
     * @var string `⌗🆂🅲⫶` Four UTF-8 chars.
     */
    const CLOSURE = "\u{2317}\u{1F182}\u{1F172}\u{2AF6}";

    /**
     * Class constructor.
     *
     * @since 160712 Serializer.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->ClosureAstSerializer   = new ClosureSerializer(new ClosureAstAnalyzer());
        $this->ClosureTokenSerializer = new ClosureSerializer(new ClosureTokenAnalyzer());
        // See: <https://github.com/jeremeamia/super_closure>
    }

    /**
     * Serialize value.
     *
     * @since 160712 Serializer.
     *
     * @param mixed $value Value to serialize.
     *
     * @return string A string (serialized).
     */
    public function __invoke($value): string
    {
        if ($value instanceof \Closure) {
            $string = $this::CLOSURE;
            return $string .= $this->serializeClosure($value);
        } elseif (!is_resource($value)) {
            return $string = serialize($value);
        } else { // Cannot serialize.
            throw $this->c::issue('Cannot serialize.');
        }
    }

    /**
     * Serialize closure.
     *
     * @since 160712 Closure utils.
     *
     * @param \Closure $Closure A closure.
     * @param bool     $faster  Use faster serializer?
     *
     * @return string Serialized closure.
     */
    public function serializeClosure(\Closure $Closure, bool $faster = false): string
    {
        // NOTE: No marker; i.e., so this can be called stand-alone if necessary.
        return $this->{$faster ? 'ClosureTokenSerializer' : 'ClosureAstSerializer'}->serialize($Closure);
    }

    /**
     * Unserialize closure.
     *
     * @since 160712 Closure utils.
     *
     * @param string $string A serialized closure.
     * @param bool   $faster Used faster serializer?
     *
     * @return \Closure Unserialized closure.
     */
    public function unserializeClosure(string $string, bool $faster = false): \Closure
    {
        if (mb_strpos($string, $this::CLOSURE) === 0) {
            $string = mb_substr($string, mb_strlen($this::CLOSURE));
        }
        return $this->{$faster ? 'ClosureTokenSerializer' : 'ClosureAstSerializer'}->unserialize($string);
    }

    /**
     * Maybe serialize value.
     *
     * @since 150424 Serializer.
     *
     * @param mixed $value Value to serialize.
     *
     * @return string A string (possibly serialized).
     */
    public function maybeSerialize($value): string
    {
        if (is_string($value)) {
            return $string = $value;
        } elseif (is_bool($value)) {
            return $string = (string) (int) $value;
        } elseif (is_int($value) || is_float($value)) {
            return $string = (string) $value;
        } else { // Serialize.
            return $string = $this->__invoke($value);
        }
    }

    /**
     * Maybe unserialize value.
     *
     * @since 150424 Serializer.
     *
     * @param mixed $value Value.
     *
     * @return mixed The unserialized value.
     */
    public function maybeUnserialize($value)
    {
        if ($value && $this->isSerialized($value)) {
            if (mb_strpos($value, $this::CLOSURE) === 0) {
                return $this->unserializeClosure($value);
            } else {
                return unserialize($value);
            }
        } else {
            return $value; // Not applicable.
        }
    }

    /**
     * Is serialized?
     *
     * @since 160712 Serializer.
     *
     * @param mixed $value Value.
     *
     * @return bool True if serialized.
     */
    public function isSerialized($value): bool
    {
        if (!$value) {
            return false;
        } elseif (!is_string($value)) {
            return false;
        }
        $string = $value; // It is a string.

        if ('N;' === $string) {
            return true; // `null`.
        } elseif ('b:0;' === $string) {
            return true; // (bool)`false`.
        } elseif ('b:1;' === $string) {
            return true; // (bool)`true`.
        }
        if (mb_strpos($string, $this::CLOSURE) === 0) {
            $string = mb_substr($string, mb_strlen($this::CLOSURE));
        }
        if (!isset($string[3])) {
            return false;
        } elseif ($string[1] !== ':') {
            return false;
        } elseif (!in_array($string[0], [/* 'N', 'b', */ 'i', 'd', 's', 'a', 'O', 'C'], true)) {
            return false; // NOTE: Null and bool already tested above.
        } elseif (!in_array(mb_substr($string, -1), [';', '}'], true)) {
            return false;
        }
        switch ($string[0]) {
            case 'N': // Null.
            case 'b': // Boolean.
                return false; // For clarity.
                // NOTE: Null and bool already tested above.

            case 'i': // Integer.
                return (bool) preg_match('/^i\:\-?[0-9]+;$/u', $string);

            case 'd': // Float (decimal).
                return (bool) preg_match('/^d\:\-?[0-9.Ee+\-]+;$/u', $string);

            case 's': // String.
                return (bool) preg_match('/^s\:[0-9]+\:".*?";$/us', $string);

            case 'a': // An array.
                return (bool) preg_match('/^a\:[0-9]+\:\{.*?\}$/us', $string);

            case 'O': // An object.
            case 'C': // A serializable class.
                return (bool) preg_match('/^[OC]\:[0-9]+\:".*?"\:[0-9]+\:\{.*?\}$/us', $string);

            default: // Default case handler.
                return false; // Nope.
        }
    }

    /**
     * Check/set expected type.
     *
     * @since 150424 Serializer.
     *
     * @param mixed  $value         Value.
     * @param string $expected_type Data type.
     *
     * @return mixed|null The typified value; else `null`.
     */
    public function checkSetType($value, string $expected_type)
    {
        switch ($expected_type) {
            case 'bool':
            case 'boolean':
                $expected_type = 'bool';
                if ($value === '0' || $value === '1') {
                    $value = (bool) $value;
                }
                break; // Stop here.

            case 'int':
            case 'integer':
            case 'long':
                $expected_type = 'int';
                if (is_numeric($value) && mb_strpos($value, '.') === false) {
                    $value = (int) $value;
                }
                break; // Stop here.

            case 'float':
            case 'double':
            case 'real':
                $expected_type = 'float';
                if (is_numeric($value)) {
                    $value = (float) $value;
                }
                break; // Stop here.

            case 'string':
            case 'array':
            case 'object':
            case 'null':
                break; // Stop here.

            default: // Catch invalid type checks here.
                throw $this->c::issue(sprintf('Unexpected type: `%1$s`.', $expected_type));
        }
        $is = 'is_'.$expected_type; // See: <http://php.net/manual/en/function.gettype.php>

        return $is($value) ? $value : null;
    }
}
