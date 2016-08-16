<?php

namespace True\Data\FileSystem;

use Closure;
use InvalidArgumentException;
use True\Exceptions\FileNotFoundException;
use True\Exceptions\FileUnreadableException;
use True\Support\Facades\Lang;

class FS
{
    const ASSOC        = 'getAssoc';
    const INSERT       = 'insert';
    const INVOLVE      = 'involve';
    const INSERT_ONCE  = 'insertOnce';
    const INVOLVE_ONCE = 'involveOnce';

    /**
     * If file exists and readable calls callback function else throw exception.
     *
     * @param string $filePath
     * @param Closure $callback
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    private static function fileExistAndReadable($filePath, $callback) {
        if (is_file($filePath)) {
            if (file_exists($filePath)) {
                if (is_readable($filePath)) {
                    return $callback($filePath);
                } else {
                    throw new FileUnreadableException(Lang::get('exceptions.file_is_unreadable'));
                }
            } else {
                throw new FileNotFoundException(Lang::get('exceptions.file_not_found'));
            }
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions.invalid_argument'));
        }
    }

    /**
     * If file exists and readable include it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public static function insert($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return include $filePath;
        });
    }

    /**
     * If file exists and readable include it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public static function insertOnce($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return include_once $filePath;
        });
    }

    /**
     * If file exists and readable require it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public static function involve($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return require $filePath;
        });
    }

    /**
     * If file exists and readable require it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     */
    public static function involveOnce($filePath) {
        return self::fileExistAndReadable($filePath, function($filePath) {
            return require_once $filePath;
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
     */
    public static function get($filePath, $getMethod) {
        return self::fileExistAndReadable($filePath, function() use($filePath, $getMethod) {
            if (method_exists(get_called_class(), $getMethod)) {
                return static::$getMethod($filePath);
            } else {
                throw new InvalidArgumentException(Lang::get('exceptions.invalid_argument'));
            }
        });
    }

    /**
     * @param string $filePath
     * @return FileArrayQuery
     */
    public static function getAssoc($filePath) {
        return new FileArrayQuery($filePath);
    }
}
