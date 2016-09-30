<?php

namespace Truth\Support\Services\Routing\TrueRouter;

use Truth\Support\Services\Http\HttpStatus;
use Truth\Support\Services\Http\Response;

class Router
{
    // ([^/]|\\\/)+[^\\](?=\/)|.$
    // '~:?[^/]+(?!/)?~i'
    // '@([^/\s]*(?:\\\/(*SKIP))[^/\s]*)|[^/\s]+@i' // work
    // '/(?:(? >\\\)\/|[^\/\s])+/i'; // work
    const REGEXP_ROUTES_SPLITTING = '/(?:(?>\\\)\/|[^\/\s])+/i';

    const KEY_STEADY = 0;//'__steady__';//0;
    const KEY_VARIED = 1;//'__varied__';//1;
    const KEY_LAUNCH = 2;//'__launch__';//2;
    const KEY_REGEXP = 3;//'__regexp__';//0;
//    const KEY_NODULE = 1;//'__nodule__';//1;

    protected $keys;

    protected $host;
    protected $path;
    protected $query;

    protected $switcher;
    protected $pointer;
    protected $params = [];
    protected $launch;
    protected $launchCounter = -1;
    protected $performStatistic;

    protected static $filters = [
        's0' => '[\w]*',
        's'  => '[\w]+', // true/:user|c+s/:name:\w{3}[/profile]
        'c'  => '[a-z]+',
        'i'  => '\d+',
        'cs' => '[a-z]+[\w]*',
    ];

    public static function getFilter($name) {
        return self::$filters[$name];
    }

    public function __construct($host = null, $data = [])
    {
        $this->host = $host;
        $this->switcher = $data;
        $this->performStatistic = \SplFixedArray::fromArray([
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0
        ]);
    }

    public function split($uri, $callback) {
        preg_replace_callback(self::REGEXP_ROUTES_SPLITTING, function ($matches) use ($callback) {
            return $callback($matches[0]);
        }, $uri);
    }

    public function match($methods, $uri, $handler) {
        $this->pointer = &$this->switcher;
        $this->split($this->host . $uri, function($node) {
            if ($node[0] === ':') {
                $pointer = &$this->pointer[self::KEY_VARIED];
                $parts_regexp = explode(':', $node, 3);
                $node = &$parts_regexp[1];
                if (isset($parts_regexp[2])) {
                    $pointer = &$pointer[$node];
                    $pointer[self::KEY_REGEXP] = '^' . $parts_regexp[2] . '$';
                } else {
                    $parts_filter = explode('|', $node, 2);
                    if (isset($parts_filter[1])) {
                        $node = &$parts_filter[0];
                        $pointer = &$pointer[$node];
                        $pointer[self::KEY_REGEXP] = '^' . preg_replace_callback('/\+|\w+/i', function($matches) {
//                            return $matches[0] == '+' ? '' : trim(Router::getFilter($matches[0]), '^$');
                            return $matches[0] == '+' ? '' : Router::getFilter($matches[0]);
                        }, $parts_filter[1]) . '$';
                    } else {
                        $pointer = &$pointer[$node];
//                        $pointer[self::KEY_REGEXP] = '';
                    }
                }
                $this->pointer = &$pointer;
            } else {
                $this->pointer = &$this->pointer[self::KEY_STEADY][$node];
            }
        });
        $this->pointer[self::KEY_LAUNCH] = $handler;
//        if (is_string($handler)) {
//            $this->pointer[self::KEY_LAUNCH] = explode('@', $handler);
//        } else {
////            $this->launch[] = $handler;
////            $this->pointer[self::KEY_LAUNCH] = $this->launchCounter;
//            $this->launch[++$this->launchCounter] = $handler;
//            $this->pointer[self::KEY_LAUNCH] = ++$this->launchCounter;
//        }
    }

    public function make($route) {
        $this->pointer = &$this->switcher;
        $this->params = []; // reset params
        $this->split($route, function ($node) {
            $pointer = &$this->pointer;
            if (isset($pointer[self::KEY_STEADY][$node])) {
                $this->pointer = &$pointer[self::KEY_STEADY][$node];
            } elseif (isset($pointer[self::KEY_VARIED])) {
                $pointer = &$pointer[self::KEY_VARIED];
                for (end($pointer); $key = key($pointer), $value = current($pointer); prev($pointer) ) {
                    if (isset($value[self::KEY_REGEXP]) and preg_match('/' . $value[self::KEY_REGEXP] . '/i', $node)) {
                        $this->params[$key] = $node;
                        $this->pointer = &$value;
                        break;
                    }
                }
//                $key ?: $this->make404();
                return $key ? true : false;
            } else return false;
        });
        return true;
//        isset($this->pointer[self::KEY_LAUNCH]) ?
//            call_user_func_array($this->pointer[self::KEY_LAUNCH], $this->params) : $this->make404();
//            is_array($this->pointer[self::KEY_LAUNCH]) ?
//                call_user_func_array($this->pointer[self::KEY_LAUNCH], $this->params) :
//            call_user_func_array($this->launch[$this->pointer[self::KEY_LAUNCH]], $this->params) : $this->make404();
    }

    public function dispatch($route) {

    }

    public function run() {
        $parse_uri = parse_url($_SERVER['REQUEST_URI']);
        $parse_uri ? $this->make($_SERVER['HTTP_HOST'] . $parse_uri['path']) : $this->make404();
    }

    /**
     * @throws \Exception
     */
    public function make404() {
//        throw new \Exception(404);
//        return (new Response('File not found', HttpStatus::NOT_FOUND))->send();
        throw new \Exception(404);
    }

    public function cache() {
        $this->optimize($this->switcher);
//        d(serialize($this->switcher));
//        $time = time();
//        file_put_contents(__DIR__ .'/cache/' . $time, serialize($this->switcher), FILE_BINARY);die;
//        d(filemtime(__DIR__ .'/cache/'));
//        d(time());
    }

    public function optimize(&$array) {
        if (is_array($array)) {
            $length = count($array);
//            $splArray = new \SplFixedArray($length);
            foreach ($array as $key => $value) {
                if (is_int($key)) $this->performStatistic[$key] += 1;
                if (is_array($value)) $this->optimize($value);
            }
        }
        $sortArray = $this->performStatistic->toArray();
        arsort($sortArray);
        for (; $key = key($sortArray); next($sortArray)) {

        }
    }

    public function __destruct()
    {
//        $_SERVER['HTTP_HOST'] = 'true';
//        $_SERVER['REQUEST_URI'] = '/roman';
//        $this->run();
    }
}