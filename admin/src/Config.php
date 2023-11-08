<?php





class Config
{

    /**
     * Configuration locale de l'application
     *
     * @var array
     */
    private static $config = false;



    public static function get($section = null, $key = null, $default = null)
    {
        if (false === self::$config) {
            $configFile = dirname(dirname(__DIR__)) . '/config.local.php';
            if (file_exists($configFile)) {
                self::$config = require($configFile);
            }
        }

        if (self::$config && $section && $key) {
            if (isset(self::$config[$section][$key])) {
                return self::$config[$section][$key];
            } else {
                return $default;
            }
        }

        if (self::$config && $section && $key === null) {
            if (isset(self::$config[$section])) {
                return self::$config[$section];
            } else {
                return $default;
            }
        }

        return self::$config;
    }



    public static function getBdd(): array
    {
        return [
            'driver'   => self::get('bdd', 'driver') ?? 'Oracle',
            'host'     => self::get('bdd', 'host'),
            'port'     => self::get('bdd', 'port'),
            'dbname'   => self::get('bdd', 'dbname'),
            'username' => self::get('bdd', 'username'),
            'password' => self::get('bdd', 'password'),
        ];
    }

}