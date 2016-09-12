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
    protected $fileExtension;

    public function __construct(Twig_Environment $environment, Twig_Extension_Debug $debug)
    {
        $this->fileExtension = 'twig';
        $this->engine = $environment;
        $this->engine->addExtension($debug);
    }

    public function renderBlock(Block $block, $content = null) {
        echo $this->engine->render($block->getLayout(), $block->content($content));
    }

    public function createBlockData($name, $layout, $data = [], $content = []) {
        return (new Block($name, $layout, $data, $this->fileExtension))->content($content);
    }

    public function render($layout, $data = null) {
        $structure = Yaml::parse(file_get_contents($data));
        $data = [
            'logo' => $this->createBlockData('logo', 'static'),
            'article' => $this->createBlockData('article', 'slider/slider',
                ['page' => 1, 'columns' => 3, 'header' => 'Table header'],
            [
                ['title' => 'My first article', 'text' => 'It will be awesome!!!'],
                ['title' => 'My second article', 'text' => 'I like what I doing.'],
                ['title' => 'My third article', 'text' => 'I hate what I doing.'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats cat!!!'],
                ['title' => 'LAST article', 'text' => 'Dog eats dos!!!'],
            ])
        ];
        echo $this->engine->render(self::$box->make('Config')->getCurrentThemeName() . '/' .
            $layout . '.' . $this->fileExtension, $data);
    }

    public function display($layout, $data)
    {
        // TODO: Implement display() method.
    }
}
