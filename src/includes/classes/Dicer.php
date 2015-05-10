<?php
namespace dice;

class dice
{
    /**
     * Closure cache.
     *
     * @type array Cache.
     */
    protected $closures = [];

    /**
     * Instance cache.
     *
     * @type array Cache.
     */
    protected $instances = [];

    /**
     * Rules.
     *
     * @type array Rules.
     */
    protected $rules = [];

    /**
     * Rule defaults.
     *
     * @type array Defaults.
     */
    protected $rule_defaults = [
        'class_name'       => '*',
        'shared'           => false,
        'inherit'          => true,
        'construct_params' => [],
        'substitutions'    => [],
        'new_instances'    => [],
        'call'             => [],
        'instance_of'      => '',
        'share_instances'  => [],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rules['*'] = $this->rule_defaults;
    }

    /**
     * Add a new class rule.
     *
     * @param string $class_name Class name (i.e., Namespace\Class).
     * @param array  $rule       An array of rule properties.
     */
    public function addRule($class_name, array $rule)
    {
        $class_name    = (string) $class_name;
        $class_name    = ltrim($class_name, '\\');
        $class_name_lc = strtolower($class_name);

        $this->rules[$class_name_lc] = array_merge($this->rule_defaults, $rule);
        $this->rules[$class_name_lc] = array_intersect_key($this->rules[$class_name_lc], $this->rule_defaults);
        $rule                        = &$this->rules[$class_name_lc];

        $rule['class_name']       = $class_name;
        $rule['shared']           = (boolean) $rule['shared'];
        $rule['inherit']          = (boolean) $rule['inherit'];
        $rule['construct_params'] = (array) $rule['construct_params'];
        $rule['substitutions']    = (array) $rule['substitutions'];
        $rule['new_instances']    = (array) $rule['new_instances'];
        $rule['call']             = (array) $rule['call'];
        $rule['instance_of']      = ltrim((string) $rule['instance_of'], '\\');
        $rule['share_instances']  = (array) $rule['share_instances'];
    }

    /**
     * Gets a specific class rule.
     *
     * @param string $class_name Class name (i.e., Namespace\Class).
     *
     * @return array An array of rule properties.
     */
    public function getRule($class_name)
    {
        $class_name    = (string) $class_name;
        $class_name    = ltrim($class_name, '\\');
        $class_name_lc = strtolower($class_name);

        if (isset($this->rules[$class_name_lc])) {
            return $this->rules[$class_name_lc];
        }
        foreach ($this->rules as $_class_name_lc => $_rule) {
            if ($_rule['inherit'] && $_rule['class_name'] !== '*') {
                if (!$_rule['instance_of'] && is_subclass_of($class_name, $_rule['class_name'], true)) {
                    return $_rule;
                }
            }
        } // unset($_class_name_lc, $_rule); // Housekeeping.
        return $this->rules['*'];
    }

    /**
     * Get a specific class instance.
     *
     * @param string            $class_name         Class name (i.e., Namespace\Class).
     * @param array             $args               An array of arguments to the constructor.
     * @param bool              $force_new_instance Force a new instance of the class? Default is `false`.
     * @param string[]|object[] $share              Any array of any class names (or instances) to share.
     *
     * @return object An object class instance.
     */
    public function get($class_name, array $args = [], $force_new_instance = false, array $share = [])
    {
        $class_name    = (string) $class_name;
        $class_name    = ltrim($class_name, '\\');
        $class_name_lc = strtolower($class_name);

        if (!$force_new_instance && isset($this->instances[$class_name_lc])) {
            return $this->instances[$class_name_lc];
        }
        if (!isset($this->closures[$class_name_lc])) {
            $rule                           = $this->getRule($class_name);
            $this->closures[$class_name_lc] = $this->getClosure($class_name, $rule);
        }
        foreach ($share as &$_share) {
            if (is_string($_share)) {
                $_share = $this->get($_share);
            }
        } // unset($_share); // Housekeeping.
        return $this->closures[$class_name_lc]($args, $share);
    }

    /**
     * Get a specific class closure.
     *
     * @param string $class_name Class name (i.e., Namespace\Class).
     * @param array  $rule       A specific rule that goes with the class name.
     *
     * @return callable A closure that returns the class instance.
     */
    protected function getClosure($class_name, array $rule)
    {
        $class_name    = (string) $class_name;
        $class_name    = ltrim($class_name, '\\');
        $class_name_lc = strtolower($class_name);

        $class          = new \ReflectionClass($rule['instance_of'] ? $rule['instance_of'] : $class_name);
        $constructor    = $class->getConstructor(); // Returns null if class has no constructor.
        $params_closure = $constructor ? $this->getParamsClosure($constructor, $rule) : null;

        if ($rule['shared']) {
            $closure = function (array $args, array $share) use ($class_name_lc, $class, $constructor, $params_closure) {
                if ($constructor && $params_closure) {
                    $this->instances[$class_name_lc] = $class->newInstanceArgs($params_closure($args, $share));
                } else {
                    $this->instances[$class_name_lc] = $class->newInstanceWithoutConstructor();
                }
                return $this->instances[$class_name_lc];
            };
        } elseif ($constructor && $params_closure) {
            $closure = function (array $args, array $share) use ($class, $params_closure) {
                return $class->newInstanceArgs($params_closure($args, $share));
            };
        } else {
            $closure = function (array $args, array $share) use ($class) {
                return $class->newInstanceWithoutConstructor();
            };
        }
        if ($rule['call']) {
            $closure = function (array $args, array $share) use ($closure, $class, $rule) {
                $instance = $closure($args, $share);
                foreach ($rule['call'] as $_call) {
                    $_method = $class->getMethod($_call[0]);
                    $_args   = isset($_call[1]) ? $this->expandInstanceKeys($_call[1], []) : [];
                    $_method->invokeArgs($instance, $_args);
                }
                #unset($_call, $_method, $_args); // Housekeeping.

                return $instance;
            };
        }
        return $closure;
    }

    /**
     * Expands parameters deeply; i.e., some magic happens here!
     *
     * @param \ReflectionMethod A reflection method to parameterize.
     * @param array $rule A specific rule that goes with the parent class instance.
     *
     * @return callable A closure that returns an array of parameters; w/ dependencies injected deeply.
     */
    protected function getParamsClosure(\ReflectionMethod $method, array $rule)
    {
        $parameter_details = []; // Initialize.

        foreach ($method->getParameters() as $_parameter) {
            if (($_class = $_parameter->getClass())) {
                $_class_name = $_class->name;
            } else {
                $_class_name = ''; // No typehint.
            }
            $parameter_details[] = [
                $_class_name, // Possible typehint on this parameter.
                $_parameter->allowsNull(), // If the parameter allows a `NULL` value.
                $_class_name && $rule['substitutions'] && array_key_exists($_class_name, $rule['substitutions']),
                $_class_name && $rule['new_instances'] && in_array($_class_name, $rule['new_instances'], true),
            ];
        }
        #unset($_parameter, $_class, $_class_name); // Housekeeping.

        return function (array $args, array $share) use ($parameter_details, $rule) {
            $parameters = []; // Initialize parameters.

            if ($rule['share_instances']) {
                $share = array_merge(
                    $share, // Existing shared instances.
                    array_map([$this, 'get'], $rule['share_instances'])
                );
            }
            if ($share) {
                $args = array_merge($args, $share);
            }
            if ($rule['construct_params']) {
                $args = array_merge($args, $this->expandInstanceKeys($rule['construct_params'], $share));
            }
            foreach ($parameter_details as $_parameter_detail) {
                list($_class_name, $_allows_null, $_substitution, $_force_new_instance) = $_parameter_detail;

                if ($args) { // Check if args contain class instance parameter overrides.
                    for ($_i = 0, $_total_args = count($args); $_i < $_total_args; $_i++) {
                        if (($_allows_null && is_null($args[$_i])) || ($_class_name && $args[$_i] instanceof $_class_name)) {
                            $parameters[] = array_splice($args, $_i, 1)[0];
                            continue 2; // Filled instance w/ args.
                        }
                    }
                    #unset($_i, $_total_args); // Housekeeping.
                }
                if ($_class_name) {
                    $parameters[] = $_substitution // Substitute this class instance?
                        ? $this->expandInstanceKeys($rule['substitutions'][$_class_name], $share)
                        : $this->get($_class_name, [], $_force_new_instance, $share);
                } elseif ($args) {
                    $parameters[] = $this->expandInstanceKeys(array_shift($args), []);
                }
            }
            #unset($_parameter_detail); // Housekeeping.
            #unset($_class_name, $_allows_null, $_substitution, $_force_new_instance);

            return $parameters; // With deep dependency injection.
        };
    }

    /**
     * Expands argument `::instance` keys deeply.
     *
     * @param mixed    $value Any input value can be scanned deeply.
     * @param object[] $share An array of class instances to share.
     *
     * @return mixed Input `$value` w/ `::instance` keys expanded deeply.
     */
    protected function expandInstanceKeys($value, array $share)
    {
        if (is_array($value)) {
            if (isset($value['::instance'])) {
                return is_callable($value['::instance'])
                    ? call_user_func($value['::instance'], $this, $share)
                    : $this->get($value['::instance'], [], false, $share);
            } else {
                foreach ($value as $_key => &$_value) {
                    $_value = $this->expandInstanceKeys($_value, $share);
                }
                unset($_key, $_value); // Housekeeping.
            }
        }
        return $value;
    }
}
