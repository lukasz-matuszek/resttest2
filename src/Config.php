<?php

namespace Lib;

class Config
{
    public $config;

    public function __construct()
    {
        $this->config = parse_ini_file('../config/config.ini', true);
    }

    public function getSection($section)
    {
        return $this->config[$section] ?? [];
    }

    public function get($sectionKey)
    {
        [$section, $key] = explode('.', $sectionKey);

        return $this->config[$section][$key] ?? '';
    }

}