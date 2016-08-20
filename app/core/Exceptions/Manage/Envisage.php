<?php

namespace Truth\Exceptions\Manage;

class EnvisageChecker
{
    protected static $namespace = 'Truth\\Exceptions\\';

    protected $messages;
    protected $expected;
    protected $code;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function createException($num, $exception, array $arguments = []) {
        $givenType = gettype($arguments[0]);
        foreach ($arguments as $key => $value) {
            $arguments[$key] = static::stringify($value);
        }
        $arguments['num'] = $num;
        $arguments['valid'] = $this->expected;
        $arguments['invalid'] = $givenType;
//        ob_start();
//        vprintf($this->messages[$exception], $arguments);
//        return new $fullException(ob_get_clean(), $this->code);
        $fullException = static::$namespace . $exception . 'Exception';
        return new $fullException($this->messages['prefix'] . $this->messages['exceptions'][$exception] .
            $this->messages['suffix'], $arguments, $this->code);
    }

    public function getCode() {
        return $this->code;
    }

    public function arr($array) {
        if (is_array($array)) return true;
        $this->expected = 'array';
        $this->code = Envisage::INVALID_ARRAY;
        return false;
//        if (! is_array($array))
    }

    public function isCallable($value) {
        if (is_callable($value)) return true;
        $this->expected = 'callable';
        $this->code = Envisage::INVALID_CALLABLE;
        return false;
    }

    /**
     * Make a string version of a value.
     *
     * @param mixed $value
     * @return string
     */
    protected static function stringify($value)
    {
        if (is_bool($value)) {
            return $value ? '<TRUE>' : '"false"';
        }
        if (is_scalar($value)) {
            $val = (string)$value;
            if (strlen($val) > 100) {
                $val = substr($val, 0, 97) . '...';
            }
            return $val;
        }
        if (is_array($value)) {
            return '<ARRAY>';
        }
        if (is_object($value)) {
            return get_class($value);
        }
        if (is_resource($value)) {
            return '<RESOURCE>';
        }
        if ($value === NULL) {
            return '<NULL>';
        }
        return 'unknown';
    }
}

class Envisage {
    const INVALID_ARRAY             = 24;
    const INVALID_CALLABLE          = 215;

    /**
     * @var EnvisageChecker
     */
    public static $instance;

    public static function register() {
        $data = include APP_DIR . '/core/Languages/en-EN.php';
        static::$instance = new EnvisageChecker($data);
    }

    public static function __callStatic($name, $arguments)
    {
        if (! call_user_func_array([static::$instance, $name], $arguments)) {
            $last = array_pop($arguments);
            if (is_int($last)) {
                $num = $last;
                $exception = array_pop($arguments);
            } else {
                $num = 1;
                $exception = $last;
            }
            throw static::$instance->createException($num, $exception, $arguments);
        }
    }
}
