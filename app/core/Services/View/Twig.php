<?php

namespace Truth\Services\View;

use Twig_Environment;
use Twig_Extension_Debug;
use Symfony\Component\Yaml\Yaml;
use Truth\Support\Interfaces\ViewInterface;
use Truth\Support\ServiceProvider;
use Truth\View\Block;

class Twig implements ViewInterface, ServiceProvider
{
    protected $layout;
    protected $engine;

    /**
     * @param \Truth\IoC\Box $box
     * @return void
     */
    public static function register(&$box)
    {
        $box->bind('Twig_LoaderInterface', 'Twig_Loader_Filesystem');
        $box->singleton('View', 'Truth\Services\View\Twig');
        $box->make('View', [
            APP_DIR . '/core/Themes',
            [
                'cache' => APP_DIR . '/cache/themes',
                'debug' => true,
                'auto_reload' => true
            ]
        ]);
    }

    public function __construct(Twig_Environment $environment, Twig_Extension_Debug $debug)
//    public function __construct()
    {
//        $loader = new Twig_Loader_Filesystem(APP_DIR . Config::getDirectoryPath('themes'));

//        $loader = new Twig_Loader_Filesystem(APP_DIR . '/core/Themes');
//        $this->engine = new Twig_Environment($loader, array(
////            'cache' => Config::get('directories.cache.themes'),
//            'cache' => APP_DIR . '/app/cache/templates',
//            'debug' => true,
//            'auto_reload' => true
//        ));
        $this->engine = $environment;
//        $this->engine->addExtension(new Twig_Extension_Debug());
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
