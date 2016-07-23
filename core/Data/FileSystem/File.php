<?php

namespace True\Data\FileSystem;

use Closure;
use InvalidArgumentException;
use True\Exceptions\FileNotFoundException;
use True\Exceptions\FileUnreadableException;
use True\Multilingual\Lang;

class File
{
    const INC      = 0;
    const REQ      = 1;
    const INC_ONCE = 2;
    const REQ_ONCE = 3;

    /**
     * @var string
     */
    protected $fileContent;

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
    private static function existAndReadable($filePath, $callback) {
        if (is_file($filePath)) {
            if (file_exists($filePath)) {
                if (is_readable($filePath)) {
                    return $callback();
                } else {
                    throw new FileUnreadableException(Lang::get('exceptions')['file_is_unreadable']);
                }
            } else {
                throw new FileNotFoundException(Lang::get('exceptions')['file_not_found']);
            }
        } else {
            throw new InvalidArgumentException(Lang::get('exceptions')['invalid_argument']);
        }
    }

    /**
     * If file exists and readable get (include / require / include_once / require_once)
     * it (once / more) else throw exception.
     *
     * @param $filePath
     * @param int $getMethod
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function get($filePath, $getMethod) {
        return self::existAndReadable($filePath, function() use($filePath, $getMethod) {
            switch ($getMethod) {
                case self::INC:      return include     ($filePath); break;
                case self::REQ:      return require     ($filePath); break;
                case self::INC_ONCE: return include_once($filePath); break;
                case self::REQ_ONCE: return require_once($filePath); break;
                default: throw new InvalidArgumentException;
            }
        });
    }

    /**
     * If file exists and readable include it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function inc($filePath) {
        return self::existAndReadable($filePath, function() use($filePath) {
            return include $filePath;
        });
    }

    /**
     * If file exists and readable include it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function incOnce($filePath) {
        return self::existAndReadable($filePath, function() use($filePath) {
            return include_once $filePath;
        });
    }

    /**
     * If file exists and readable require it else throw exception.
     *
     * @param string $filePath
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function req($filePath) {
        return self::existAndReadable($filePath, function() use($filePath) {
            return require $filePath;
        });
    }

    /**
     * If file exists and readable require it once else throw exception.
     *
     * @param string $filePath
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public static function reqOnce($filePath) {
        return self::existAndReadable($filePath, function() use($filePath) {
            return require_once $filePath;
        });
    }

    /**
     * File constructor.
     *
     * @param string $filePath
     * @param int $getMethod
     *
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     * @throws FileUnreadableException
     */
    public function __construct($filePath, $getMethod = self::REQ_ONCE)
    {
        $this->fileContent = self::get($filePath, $getMethod);
    }
}
