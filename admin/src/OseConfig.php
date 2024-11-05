<?php

class OseConfig
{
    const LOCAL_APPLICATION_CONFIG_FILE = 'config.local.php';

    private array $config = [];



    public function get(string $section = null, string $key = null, $default = null)
    {
        if (empty($this->config)) {
            $configFilename = self::LOCAL_APPLICATION_CONFIG_FILE;
            if (file_exists($configFilename)) {
                $this->config = require $configFilename;
            }
        }

        if (empty($this->config)) {
            return $default;
        }


        if ($this->config && $section && $key) {
            if (isset($this->config[$section][$key])) {
                return $this->config[$section][$key];
            } else {
                return $default;
            }
        }

        if ($this->config && $section) {
            if (isset($this->config[$section])) {
                return $this->config[$section];
            }
        }

        return $this->config;
    }

}