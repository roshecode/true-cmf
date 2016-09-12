<?php

namespace Truth\View;

use Truth\Support\Facades\Config;

class Block
{
    static $ns = 'Truth\\View\\Block::';

    const TYPE_STATIC           = 0;
    const TYPE_DYNAMIC          = 1;

    const VIEW_ORDERED_LIST     = 2;
    const VIEW_UNORDERED_LIST   = 3;
    const VIEW_GRID             = 4;
    const VIEW_TABLE            = 5;

    const PATH_LAYOUTS = '/layouts/blocks';
    const PATH_TEMPLATES = '/blocks';

    private $data;
    private $name;
    private $path;
    private $layout;
    private $template;

    public function __construct($name, $layout = 'static', $data = [], $extension = 'twig')
    {
        $this->name = $name;
        $theme = Config::getCurrentThemeName();
        $this->path = $theme . self::PATH_LAYOUTS . '/' . dirname($layout) . '/';
        $this->layout = $theme . self::PATH_LAYOUTS . '/' . $layout . '.' . $extension;
        $this->template = $theme . self::PATH_TEMPLATES . '/' . $name . '.' . $extension;
        $this->data = $data;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function content($content) {
//        $vars = get_object_vars($this);
        $data = $this->data;
        $data['name'] = $this->name;
        $data['path'] = $this->path;
        $data['template'] = $this->template;
        $data['content'] = $content;
        return [
            'layout' => $this->layout,
            'data' => $data
        ];
    }
}
