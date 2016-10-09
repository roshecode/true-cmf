<?php

namespace T\Support\Services\View;

use Twig_Environment;
use Twig_Extension_Debug;
use Symfony\Component\Yaml\Yaml;
use T\Support\Abstracts\ServiceProvider;
use T\Support\Interfaces\ViewInterface;

class Twig extends ServiceProvider implements ViewInterface
{
    const PATH_WRAPPERS = 'wrappers';
    const PATH_BLOCKS   = 'blocks';

    protected $structure;
    protected $engine;
    protected $fileExtension;
    protected $currentThemeName = 'default';

    public function __construct(Twig_Environment $environment, Twig_Extension_Debug $debug)
    {
        $this->fileExtension = 'twig';
        $this->engine = $environment;
        $this->engine->addExtension($debug);
    }

    public function addLayout($path, $blocks) {
        $data = [];
        foreach ($blocks as $blockName => &$blockWrapper) {
            $data[$blockName] = [
                'layout' => self::PATH_WRAPPERS . '/' . $blockWrapper,
                'data' => [
                    'name' => $blockName,
                    'path' => self::PATH_WRAPPERS . '/' . $blockWrapper,
                    'template' => self::PATH_BLOCKS . '/' . $blockName
                ]
            ];
        }
        $this->structure[$path] = $data;
    }

    protected function associateBlockWithTheme($block, $data = []) {
        $block['layout'] = $this->currentThemeName . '/' . $block['layout'] . '.' . $this->fileExtension;
        $blockData = &$block['data'];
        $blockData['template'] = $this->currentThemeName . '/' . $blockData['template'] . '.' . $this->fileExtension;
        $blockData = array_merge($blockData, $data);
        $blockData['path'] = $this->currentThemeName . '/' . $blockData['path'] . '/';
        return $block;
    }

    public function create($layout, $blocksData = null) {
        $createdBlocks = [];
        if (isset($this->structure[$layout])) {
            foreach ($this->structure[$layout] as $blockName => $block) {
                $createdBlocks[$blockName] = $this->associateBlockWithTheme($block, isset($blocksData[$blockName]) ?
                    $blocksData[$blockName] : []);
            }
        }
        return $createdBlocks;
    }

    public function render($layout, $data = null) {
//        $structure = Yaml::parse(file_get_contents($data));
        $this->currentThemeName = $this->box->make('Config')->getCurrentThemeName();
        echo $this->engine->render($this->currentThemeName . '/' .
            $layout . '.' . $this->fileExtension, $this->create($layout, $data));
    }

    public function display($layout, $data)
    {
        // TODO: Implement display() method.
    }
}
