<?php

namespace T\Exceptions\Manage;

class EnvisageChecker
{
    protected static $namespace = 'Truth\\Exceptions\\';

    protected $messages;

    public function __construct($messages)
    {
        $this->messages = $messages;
    }

    public function createException($exception, $code, $previous) {
        $fullException = static::$namespace . $exception . 'Exception';
        return new $fullException(
            $this->messages['before'] . $this->messages['exceptions'][$exception] . $this->messages['after'],
            $code, $previous);
    }

    public function isArray($array, $previous = null) {
        if (is_array($array)) return true;
        throw $this->createException('InvalidArgument', Envisage::INVALID_ARRAY, $previous);
//        if (! is_array($array))
    }

    public function isCallable($value, $previous = null) {
        if (is_callable($value)) return true;
        return $this->createException('InvalidArgument', Envisage::INVALID_CALLABLE, $previous);
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
        call_user_func_array([static::$instance, $name], $arguments);
    }
}
