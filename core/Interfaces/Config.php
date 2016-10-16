<?php
namespace T\Interfaces;

interface Config
{
    /**
     * Set php errors displaying mode
     *
     * @param array $params [display, reporting]
     */
    public function setErrors(array $params);
    
    /**
     * Set system language
     *
     * @param string $lang
     */
    public function setLanguage($lang);
    
    /**
     * Get directory path
     *
     * @param string $path
     *
     * @return string
     */
    public function getDirectoryPath($path);
    
    /**
     * Get current theme name
     *
     * @return string
     */
    public function getCurrentThemeName();
    
    /**
     * Get current theme path
     *
     * @return string
     */
    public function getCurrentThemePath();
}
