<?php

namespace Core\Services;

use True\Support\ArrayObject\MultiFileArrayObject;

class Lang extends MultiFileArrayObject implements Contracts\Lang
{
    const WRAP_TAG = 'strong';
    const BASE_LANG = 'en-EN';

    /**
     * @param Contracts\FS $fileSystem
     * @param string        $filePath
     * @param string        $separator
     */
    public function __construct(Contracts\FS $fileSystem, $filePath = null, $separator = '.')
    {
        parent::__construct($fileSystem, $filePath);
    }

    public function load($lang)
    {
        $this->loadFiles($lang);
    }

    public function parse($str, array $data, $tag = null)
    {
        return preg_replace_callback('|{{\s*\w+\s*}}|U', function ($matches) use ($data, $tag) {
            $key = trim($matches[0], '{ }');

            return isset($data[$key]) ? $this->tag($data[$key], $tag) : '';
        }, $str);
    }

    public function exception($exception, array $data)
    {
        return $this->debug('exceptions', $exception, $data);
    }

    public function notice($notice, array $data)
    {
        return $this->debug('notices', $notice, $data);
    }

    protected function tag($data, $tag)
    {
        return $tag ? "<$tag>$data</$tag>" : '';
    }

    protected function debug($placeholder, $text, array $data)
    {
        $debug = &$this->data['debug'];

        return $this->parse("{$debug['before']}{$debug[$placeholder][$text]}{$debug['after']}", $data, self::WRAP_TAG);
    }
}
