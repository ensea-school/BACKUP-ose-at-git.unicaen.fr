<?php

class OseConfig
{
    const CONFIG_FILE = 'config.local.php';

    private array $config = [];



    public function run(): array
    {
        $this->load();
        $this->initEnv();

        return $this->config;
    }



    public function load(): void
    {
        if (!file_exists(self::CONFIG_FILE)) {
            die('Le fichier de configuration ' . self::CONFIG_FILE . ' doit être mis en place et configuré, or il n\'a pas été trouvé.');
        }

        $this->config = require 'config.local.php';

        $this->config['module_listener_options'] = [
            'use_laminas_loader'       => false,
            'config_glob_paths'        => [
                realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
            ],
            'module_paths'             => [
                './module',
                './vendor',
            ],
            'cache_dir'                => 'var/cache/',
            'config_cache_enabled'     => PHP_SAPI !== 'cli', // pas de cache en mode cli
            'config_cache_key'         => 'application.config.cache',
            'module_map_cache_enabled' => true,
            'module_map_cache_key'     => 'application.module.cache',
        ];

        $this->config['modules'] = require __DIR__ . '/modules.config.php';

        // Connecteurs
        if ($config['actul']['host'] ?? null){
            $this->config['modules'][] = 'Connecteur\\Actul';
        }

        if ($config['pegase']['actif'] ?? false){
            $this->config['modules'][] = 'Connecteur\\Pegase';
        }
    }



    public function initEnv(): void
    {
        // 1. Détection du protocole (HTTP/HTTPS)
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
            || (!empty($_SERVER['HTTP_CF_VISITOR']) && strpos($_SERVER['HTTP_CF_VISITOR'], 'https') !== false);

        $protocol = $isHttps ? 'https://' : 'http://';
        $host     = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

        $rootUrl = $protocol . $host;

        putenv('DB_HOST=' . $this->config['bdd']['host']);
        putenv('DB_PORT=' . $this->config['bdd']['port']);
        putenv('DB_NAME=' . $this->config['bdd']['dbname']);
        putenv('DB_USER=' . ($this->config['bdd']['username'] ?? $this->config['bdd']['user']));
        putenv('DB_PASSWORD=' . $this->config['bdd']['password']);

        putenv('MERCURE_URL=http://php/.well-known/mercure');
        putenv('MERCURE_PUBLIC_URL=' . $rootUrl . '/.well-known/mercure');
    }
}

return (new OseConfig)->run();