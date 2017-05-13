<?php
namespace Truecode\Filesystem;

use Closure;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use UnexpectedValueException;
//use T\Services\ArrayObject\FileArrayObject;
use Truecode\Filesystem\Exceptions\FileNotFoundException;

class FS
{
    const TAKE         = 'take';
    const READ         = 'read';
    const ASSOC        = 'getAssoc';
    const INSERT       = 'insert';
    const INVOLVE      = 'involve';
    const INSERT_ONCE  = 'insertOnce';
    const INVOLVE_ONCE = 'involveOnce';

    protected $basedir;
    protected $lastFilePath;
    
    /**
     * Filesystem constructor with base directory path.
     *
     * @param string $base
     */
    public function __construct($base = '') {
        $this->basedir = $this->lastFilePath = realpath($base) . DIRECTORY_SEPARATOR;
    }
    
    public function isFile($filePath) {
        return is_file(self::full($filePath));
    }
    
    public function isDir($filePath) {
        return is_dir(self::full($filePath));
    }
    
    public function exists($filePath) {
        return file_exists(self::full($filePath));
    }
    
    public function dirname($filePath) {
        return dirname($filePath);
    }
    
    public function basename($filePath) {
        return basename($filePath);
    }
    
    public function filename($filePath) {
        return pathinfo($filePath, PATHINFO_FILENAME);
    }
    
    public function extension($filePath) {
        return pathinfo($filePath, PATHINFO_EXTENSION);
    }
    
    public function meta($filePath) {
        return pathinfo(self::full($filePath));
    }
    
    private function full($filePath) {
        return $this->lastFilePath = $this->basedir . $filePath;
    }
    
    /**
     * If file exists and readable calls callback function else throw exception.
     *
     * @param string  $filePath
     * @param Closure $callback
     *
     * @throws FileNotFoundException
     * @return boolean
     */
    private function isFileCallback($filePath, $callback) {
        if (self::isFile($filePath)) return $callback($this->lastFilePath);
        else throw new FileNotFoundException('File "' . $filePath . '" you try to open is not found');
    }
    
    private function isDirCallback($filePath, $callback) {
        if (self::isDir($filePath)) return $callback($this->lastFilePath);
        else throw new \Exception('Directory "' . $filePath . '" you try to open is not found');
    }
    
    /**
     * If file exists and readable include it else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function insert($filePath) {
        return self::isFileCallback($filePath, function (&$filePath) {
            return include $filePath;
        });
    }
    
    /**
     * If file exists and readable include it once else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function insertOnce($filePath) {
        return self::isFileCallback($filePath, function (&$filePath) {
            return include_once $filePath;
        });
    }
    
    public function insertAll($filePath) {
        return self::isDirCallback($filePath, function () {
            $data = [];
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->lastFilePath)) as $filename)
                $filename->isDir() ?: $data[pathinfo($filename, PATHINFO_FILENAME)] = include $filename;
            return $data;
        });
    }
    
    /**
     * If file exists and readable require it else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function involve($filePath) {
        return self::isFileCallback($filePath, function (&$filePath) {
            return require $filePath;
        });
    }
    
    /**
     * If file exists and readable require it once else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function involveOnce($filePath) {
        return self::isFileCallback($filePath, function (&$filePath) {
            return require_once $filePath;
        });
    }
    
    /**
     * If file exists and readable get (include / require / include_once / require_once)
     * it (once / more) else throw exception.
     *
     * @param string $filePath
     * @param string $getMethod
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function apply($filePath, $getMethod) {
        return self::isFileCallback($filePath, function ($filePath) use ($getMethod) {
            if (is_string($getMethod)) {
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
     * If file exists and readable get content as string without parsing else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function take($filePath) {
        return self::isFileCallback($filePath, function ($filePath) {
            return file_get_contents($this->basedir . $filePath);
        });
    }
    
    /**
     * If file exists and readable execute content without parsing else throw exception.
     *
     * @param string $filePath
     *
     * @return mixed
     */
    public function read($filePath) {
        return self::isFileCallback($filePath, function ($filePath) {
            return readfile($this->basedir . $filePath);
        });
    }
    
//    /**
//     * @param string $filePath
//     *
//     * @return FileArrayObject
//     */
//    public function arrayObject($filePath) {
//        return new FileArrayObject($this, $this->basedir . $filePath);
//    }
    
    /**
     * Get basedir for all paths
     *
     * @return string
     */
    public function getBasedir() {
        return $this->basedir;
    }
    
    public function clear($filePath, $size = 0) {
        $handle = fopen($filePath, 'r+');
        ftruncate($handle, $size);
        fclose($handle);
    }
    
    public function delete($filePath) {
        unlink($filePath);
    }
    
    public function parse($filePath, $type = File::EXT_PHP) {
        return new File($filePath);
    }
}
