<?php
namespace Lib;

class Config
{
    public $config;

    public function __construct(){
        $this->config = parse_ini_file('../config/config.ini',true);
    }

    public function getSection($section)
    {
        return $this->config[$section] ?? $this->config[$section];    
    }

    public function get($sectionKey){
        list($section,$key) = explode('.',$sectionKey);
        return $this->config[$section][$key] ?? $this->config[$section][$key]  ;
    }

    public static function section($section)
    {
        $config = parse_ini_file('../config/config.ini',true);
        return $config[$section] ?? $config[$section];
    }

    public static function load()
    {
        $config = parse_ini_file('../config/config.ini',true);
        return $config;
    }
}