<?php

namespace T\Exceptions;

class EnvisageException extends \Exception
{
    const UNKNOWN = 'unknown';

    protected $data;
    protected $messageTemplate;

    public function __construct($message, $code, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $lastTrace = $this->getTrace();
        $lastTrace = $lastTrace[count($lastTrace) - 1];
//        $lastTrace = last($this->getTrace());
        $data = array_pop($lastTrace);
        $params = $this->processPHPDoc(isset($lastTrace['class']) ?
            new \ReflectionMethod($lastTrace['class'], $lastTrace['function']) :
            new \ReflectionFunction($lastTrace['function']))['params'];
        foreach ($data as $i => $arg) {
            if (isset($params[$i]['type'])) {
                $data['given'] = gettype($arg);
                if ($data['given'] != $params[$i]['type']) {
                    $data['expected'] = $params[$i]['type'];
                    $data['position'] = $i + 1;
                    $data['param'] = empty($params[$i]['name']) ? self::UNKNOWN : $params[$i]['name'];
                    break;
                }
            } else {
                $data['given'] = gettype($arg);
                $data['expected'] = $data['position'] = $data['param'] = self::UNKNOWN;
            }
        }
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(static::stringify($value));
        }
        $data = array_merge($data, $lastTrace);
        $data['code'] = $code;
        $data['filename'] = ltrim(strrchr($data['file'], DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
        $data['exception'] = get_called_class();
        $this->data = $data;
        $this->message = $this->parseStr($message, $data, '<mark>', '</mark>');
    }

    public function getData() {
        return $this->data;
    }

    public function parseStr($str, $data, $wrapLeft = '', $wrapRight = '') {
        return preg_replace_callback('|{{\s*\w+\s*}}|U', function($matches) use($data, $wrapLeft, $wrapRight) {
            $key = trim($matches[0], '{ }');
            return isset($data[$key]) ? $wrapLeft . $data[$key] . $wrapRight : '';
        }, $str);
    }

    protected function processPHPDoc(\ReflectionFunctionAbstract $reflect)
    {
        $phpDoc = array('params' => [], 'return' => null);
        $docComment = $reflect->getDocComment();
        if (trim($docComment) == '') {
            return null;
        }
        $parsedDocComment = ltrim(preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment), "\r\n");
        $lineNumber = 0;
        while (($newlinePos = strpos($parsedDocComment, "\n")) !== false) {
            ++$lineNumber;
            $line = substr($parsedDocComment, 0, $newlinePos);

            $matches = [];
            if ((strpos($line, '@') === 0) && (preg_match('#^(@\w+.*?)(\n)(?:@|\r?\n|$)#s', $parsedDocComment, $matches))) {
                $tagDocBlockLine = $matches[1];
                $matches2 = [];

                if (!preg_match('#^@(\w+)(\s|$)#', $tagDocBlockLine, $matches2)) {
                    break;
                }
                $matches3 = [];
                if (!preg_match('#^@(\w+)\s+([\w|\\\]+)(?:\s+(\$\S+))?(?:\s+(.*))?#s', $tagDocBlockLine, $matches3)) {
                    break;
                }
                if ($matches3[1] != 'param') {
                    if (strtolower($matches3[1]) == 'return') {
                        $phpDoc['return'] = ['type' => $matches3[2]];
                    }
                } else {
                    $phpDoc['params'][] = ['name' => $matches3[3], 'type' => $matches3[2]];
                }

                $parsedDocComment = str_replace($matches[1] . $matches[2], '', $parsedDocComment);
            }
        }
        return $phpDoc;
    }

    /**
     * Make a string version of a value.
     *
     * @param mixed $value
     * @return string
     */
    protected function stringify($value)
    {
        if (is_bool($value)) {
            return $value ? '<TRUE>' : '<FALSE>';
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
        return 'unknown type';
    }
}