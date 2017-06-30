<?php
namespace T\Interfaces;

interface ConfigInterface extends ServiceInterface
{
    /**
     * @param array $data
     */
//    public function load(array $data);

    /**
     * Set value by query
     *
     * @param string $query
     * @param mixed $value
     */
    public function set(string $query, $value);

    /**
     * Select value by query
     *
     * @param     $query
     *
     * @return mixed
     */
    public function get(string $query);

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
    public function setLanguage(string $lang);
    
    /**
     * Get directory path
     *
     * @param string $path
     *
     * @return string
     */
    public function getDirectoryPath($path) : string;
    
    /**
     * Get current theme name
     *
     * @return string
     */
    public function getCurrentThemeName() : string;
    
    /**
     * Get current theme path
     *
     * @return string
     */
    public function getCurrentThemePath() : string;
}
