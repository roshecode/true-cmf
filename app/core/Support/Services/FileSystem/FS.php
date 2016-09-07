<?php

namespace Truth\Support\Services\FileSystem;

use Closure;
use InvalidArgumentException;
use Truth\Support\Abstracts\ServiceProvider;
use Truth\Support\Services\FileSystem\Exceptions\FileNotFoundException;
use Truth\Support\Services\FileSystem\Exceptions\UnreadableFileException;
use UnexpectedValueException;

class FS extends ServiceProvider
{
//    const ASSOC        = 'getAssoc';
//    const INSERT       = 'insert';
//    const INVOLVE      = 'involve';
//    const INSERT_ONCE  = 'insertOnce';
//    const INVOLVE_ONCE = 'involveOnce';

    protected $basedir;

    public static function register(&$box)
    {
        $box->singleton('FS', self::CORE_SERVICES . '\\FileSystem\\FS');
        $box->make('FS', [BASEDIR]);
    }

    /**
     * FS constructor with base directory path.
     *
     * @param string $base
     */
    public function __construct($base = '')
    {
        $this->basedir = $base;
    }

    /**
     * If file exists and readable calls callback function else throw exception.
     *
     * @param string $filePath
     * @param Closure $callback
     *
     * @throws FileNotFoundException
     * @throws UnreadableFileException
     */
    private function fileExistAndReadable($filePath, $callback) {
        if (is_file($this->basedir . $filePath)) {
//            if (is_readable($filePath)) {
                return $callback($filePath);
//            } else {
//                throw new UnreadableFileException(Lang::get('exceptions.file_is_unreadable'));
//            }
        } else {
//            throw new FileNotFoundException(Lang::get('exceptions.file_not_found'));
            throw new FileNotFoundException('File "' . $filePath . '" you try to open is not found'); // TODO: Envisage
        }
    }

    /**
     * If file exists and readable include it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public function insert($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return include $this->basedir . $filePath;
        });
    }

    /**
     * If file exists and readable include it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public function insertOnce($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return include_once $this->basedir . $filePath;
        });
    }

    /**
     * If file exists and readable require it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public function involve($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return require $this->basedir . $filePath;
        });
    }

    /**
     * If file exists and readable require it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public function involveOnce($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return require_once $this->basedir . $filePath;
        });
    }

    /**
     * If file exists and readable get (include / require / include_once / require_once)
     * it (once / more) else throw exception.
     *
     * @param string $filePath
     * @param Closure $getMethod
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function get($filePath, $getMethod) {
        return self::fileExistAndReadable($filePath, function() use($filePath, $getMethod) {
            if (is_string($getMethod)) {
//                if (method_exists(get_called_class(), $getMethod)) {
                if (is_callable([get_called_class(), $getMethod])) {
                    return $this->$getMethod($filePath);
                } else {
                    throw new UnexpectedValueException('exceptions.unexpected_value'); // TODO: Envisage
                }
            } else {
                throw new InvalidArgumentException('exceptions.invalid_argument'); // TODO: Envisage
            }
        });
    }

    /**
     * @param string $filePath
     * @return FileArrayQuery
     */
    public function getAssoc($filePath) {
        return new FileArrayQuery($this, $filePath);
    }

    public function getBasedir() {
        return $this->basedir;
    }
}