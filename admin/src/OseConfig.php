<?php

class OseConfig
{
    const LOCAL_APPLICATION_CONFIG_FILE = '/config.local.php';

    private array $config = [];

    private array $applicationConfig = [];



    public function hasLocalConfig(): bool
    {
        return file_exists(getcwd() . self::LOCAL_APPLICATION_CONFIG_FILE);
    }



    public function get(string $section = null, string $key = null, $default = null)
    {
        if (empty($this->config)){
            $configFilename = getcwd() . self::LOCAL_APPLICATION_CONFIG_FILE;
            if (file_exists($configFilename)) {
                $this->config = require $configFilename;
            }
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



    public function getApplicationConfig(): array
    {
        if (empty($this->applicationConfig)) {
            $this->applicationConfig = require getcwd() . '/config/application.config.php';
        }

        return $this->applicationConfig;
    }
}