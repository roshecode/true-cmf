<?php

class TemplateParser
{
    const REGEX_HTML        = 0;
//    const REGEX_SPACE       = 1;
    const REGEX_VAR         = 1;
    const REGEX_MACRO       = 2;
    const REGEX_CONDITION   = 3;

    protected $file;
    protected $data;
    protected $stack;

    public function render($template, $data = []) {
        $this->file = file_get_contents($template);
        $this->data = $data;
        return preg_replace_callback(
            '/(?<=[%}]})[\s\S]*?(?=\n[\t\ ]*{%|{[{%])|{{\s*(\w[\s\S]*?)\s*}}|{%\s*(\w+)\s*([\s\S]*?)\s*%}/um',
            function($matches) {
//                if (count($matches) == )
                if (isset($matches[self::REGEX_MACRO])) {
                    return '';//'MACRO: ' . $matches[self::REGEX_MACRO];
                } elseif (isset($matches[self::REGEX_VAR])) {
                    return $matches[self::REGEX_VAR] . ': ' . (isset($this->data[$matches[self::REGEX_VAR]]) ?
                        $this->data[$matches[self::REGEX_VAR]] : 'undefined');
                }
                return $matches[0];
//            d($matches);
            }, $this->file);
    }

    public function parseMacro($macro) {
        preg_replace_callback('/{[{%]\s*(\w[\s\w]*)\s*[%}]}/U', function($matches) {
            d($matches);
        }, $macro);
        return $macro;
    }
}

$parser = new TemplateParser();
$dir = __DIR__ . '/app/core/Exceptions/Manage/page';
echo $parser->render($dir . '/simpleTemplate.html', [
    'dir' => $dir,
    'title' => 'template engine',
    'style' => $dir . '/style.css',
    'codes' => ['строка1', 'строка2', 'строка3']
]);
