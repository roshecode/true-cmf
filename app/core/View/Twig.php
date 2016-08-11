<?php

namespace True\View;

use Symfony\Component\Yaml\Yaml;
use True\System\Config;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

interface TemplateEngine
{
    public function renderBlock(Block $block);
}

class Twig implements TemplateEngine
{
    protected $engine;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(Config::get('app_dir') . Config::get('directories.themes'));
        $this->engine = new Twig_Environment($loader, array(
            'cache' => Config::get('directories.cache.themes'),
            'debug' => true,
            'auto_reload' => true
        ));
        $this->engine->addExtension(new Twig_Extension_Debug());
    }

    public function renderBlock(Block $block, $data = null) {
        echo $this->engine->render($block->layout, $block->data($data));
    }

    public function render($file_with_structure) {
        $structure = Yaml::parse(file_get_contents($file_with_structure));
        $data = [
            'logo' => [
                'name' => 'logo',
                'layout' => 'path/to/layout',
                'template' => 'path/to/logo',
                'data' => [
                    'name' => 'logo',
                    'layout' => 'path/to/layout',
                    'template' => 'path/to/logo',
                    'data' => [
                        ['title' => 'Hello', 'text' => 'Bie']
                    ]
                ]
            ],
        ];
    }
}
