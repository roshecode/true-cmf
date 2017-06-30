<?php
namespace T\Interfaces;

interface ViewInterface extends ServiceInterface
{
    /**
     * Create a new template
     *
     * @param string $layout
     *
     * @return \League\Plates\Template\Template
     */
    public function make($layout);

    /**
     * Add preassigned template data.
     *
     * @param  array             $data;
     * @param  null|string|array $templates;
     *
     * @return \League\Plates\Engine
     */
//    public function addData(array $data, $templates = null);

    /**
     * Create a new template and render it.
     *
     * @param  string $layout
     * @param  array  $data
     *
     * @return string
     */
    public function render($layout, array $data = []);
}
