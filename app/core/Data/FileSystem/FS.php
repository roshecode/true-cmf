<?php

namespace Truth\Data\FileSystem;

use Closure;
use InvalidArgumentException;
use Truth\Exceptions\FileNotFoundException;
use Truth\Exceptions\UnreadableFileException;
use UnexpectedValueException;
use Truth\Support\Facades\Lang;

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
     * @throws FileNotFoundException
     * @throws UnreadableFileException
     */
    private static function fileExistAndReadable($filePath, $callback) {
        if (is_file($filePath)) {
//            if (is_readable($filePath)) {
                return $callback($filePath);
//            } else {
//                throw new UnreadableFileException(Lang::get('exceptions.file_is_unreadable'));
//            }
        } else {
//            throw new FileNotFoundException(Lang::get('exceptions.file_not_found'));
            throw new FileNotFoundException('File "' . $filePath . '" you try to open is not found');
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
     * @throws UnexpectedValueException
     */
    public static function get($filePath, $getMethod) {
        return self::fileExistAndReadable($filePath, function() use($filePath, $getMethod) {
            if (is_string($getMethod)) {
//                if (method_exists(get_called_class(), $getMethod)) {
                if (is_callable([get_called_class(), $getMethod])) {
                    return static::$getMethod($filePath);
                } else {
                    throw new UnexpectedValueException(Lang::get('exceptions.unexpected_value'));
                }
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
