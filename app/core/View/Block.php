<?php

namespace Truth\View;

use Truth\Support\Facades\Config;

class Block
{
    static $ns = 'True\\View\\Block::';

    const TYPE_STATIC           = 0;
    const TYPE_DYNAMIC          = 1;

    const VIEW_ORDERED_LIST     = 2;
    const VIEW_UNORDERED_LIST   = 3;
    const VIEW_GRID             = 4;
    const VIEW_TABLE            = 5;

    const PATH_LAYOUTS = '/layouts/blocks';
    const PATH_TEMPLATES = '/blocks';

    private $name;
    private $type;
    public $layout;
    private $template;
    private $extension;

    public function __construct($name, $type = self::TYPE_STATIC, $layout = 'wrap', $extension = 'twig')
    {
        $this->name = $name;
        $this->type = $type;
        $this->extension = $extension;
        $theme = Config::get('site.theme');
        $this->layout = $theme . self::PATH_LAYOUTS . '/' .
            ($type == self::TYPE_DYNAMIC ? 'dynamic' : 'static') . '/' . $layout . '.' . $extension;
        $this->template = $theme . self::PATH_TEMPLATES . '/' . $name . '.' . $extension;
    }

    public function data($data) {
        $vars = get_object_vars($this);
        unset($vars['layout']);
        $vars['ns'] = &self::$ns;
        $vars['data'] = $data;
        return $vars;
    }
}
