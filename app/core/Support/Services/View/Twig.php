<?php

namespace Truth\Support\Services\View;

use Twig_Environment;
use Twig_Extension_Debug;
use Symfony\Component\Yaml\Yaml;
use Truth\Support\Abstracts\ServiceProvider;
use Truth\Support\Interfaces\ViewInterface;
use Truth\View\Block;

class Twig extends ServiceProvider implements ViewInterface
{
    protected $layout;
    protected $engine;

    /**
     * @return void
     */
    public static function register()
    {
        self::$box->bind('Twig_LoaderInterface', 'Twig_Loader_Filesystem');
        self::$box->singleton('View', self::NS . '\View\Twig');
        self::$box->make('View', [
            APP_DIR . '/core/Themes',
            [
                'cache' => APP_DIR . '/cache/themes',
                'debug' => true,
                'auto_reload' => true
            ]
        ]);
    }

    public function __construct(Twig_Environment $environment, Twig_Extension_Debug $debug)
    {
        $this->engine = $environment;
        $this->engine->addExtension($debug);
    }

    public function renderBlock(Block $block, $data = null) {
        echo $this->engine->render($block->layout, $block->data($data));
    }

    public function render($layout, $file_with_structure = null) {
//        $structure = Yaml::parse(file_get_contents($file_with_structure));
//        $data = [
//            'logo' => [
//                'name' => 'logo',
//                'layout' => 'path/to/layout',
//                'template' => 'path/to/logo',
//                'data' => [
//                    'name' => 'logo',
//                    'layout' => 'path/to/layout',
//                    'template' => 'path/to/logo',
//                    'data' => [
//                        ['title' => 'Hello', 'text' => 'Bie']
//                    ]
//                ]
//            ],
//        ];
        echo $this->layout . ' | ';
        $this->layout = $layout;
        echo $this->layout;
    }

    public function display($layout, $data)
    {
        // TODO: Implement display() method.
    }
}
