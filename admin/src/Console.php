<?php





class Console implements \BddAdmin\Logger\LoggerInterface
{
    const COLOR_BLACK        = '0;30';
    const COLOR_DARK_GRAY    = '1;30';
    const COLOR_BLUE         = '0;34';
    const COLOR_LIGHT_BLUE   = '1;34';
    const COLOR_GREEN        = '0;32';
    const COLOR_LIGHT_GREEN  = '1;32';
    const COLOR_CYAN         = '0;36';
    const COLOR_LIGHT_CYAN   = '1;36';
    const COLOR_RED          = '0;31';
    const COLOR_LIGHT_RED    = '1;31';
    const COLOR_PURPLE       = '0;35';
    const COLOR_LIGHT_PURPLE = '1;35';
    const COLOR_BROWN        = '0;33';
    const COLOR_YELLOW       = '1;33';
    const COLOR_LIGHT_GRAY   = '0;37';
    const COLOR_WHITE        = '1;37';

    const BG_BLACK      = '40';
    const BG_RED        = '41';
    const BG_GREEN      = '42';
    const BG_YELLOW     = '43';
    const BG_BLUE       = '44';
    const BG_MAGENTA    = '45';
    const BG_CYAN       = '46';
    const BG_LIGHT_GRAY = '47';

    /**
     * @var array
     */
    protected $options         = null;

    protected $logLevel        = 999;

    protected $logCurrentLevel = 0;

    protected $lastMessage     = null;

    protected $lastRewrite     = false;



    public function check(array $prerequis)
    {
        $len = 60;
        $res = true;

        $this->println("Contrôle des prérequis à l'exécution du script", self::COLOR_LIGHT_CYAN);
        $this->println($this->strPad('Commande (description éventuelle)', $len) . "Résultat");
        $this->println($this->strPad('----------------------', $len) . "--------");
        foreach ($prerequis as $command => $desc) {
            if (is_array($desc)) {
                extract($desc);
            } else {
                $description = $desc;
                $facultatif  = false;
            }

            $return = null;
            exec('command -v ' . $command, $null, $result);
            $passed = ($result == 0);

            $this->print($this->strPad($command . ($description ? " ($description)" : ''), $len));
            if ($passed) {
                $this->println('Commande trouvée', self::COLOR_LIGHT_GREEN);
            } elseif ($facultatif) {
                $this->println('Manquante, facultative', self::COLOR_LIGHT_PURPLE);
            } else {
                $this->println('Commande manquante', self::COLOR_LIGHT_RED);
                $res = false;
            }
        }

        if (!$res) {
            $this->printDie('Un ou plusieurs prérequis nécessaires ne sont pas présents sur cette machine. Merci de les installer avant de poursuivre l\'installation.');
        }

        return $res;
    }



    public function printMainTitle($title, $spaces = 1)
    {
        $pstr = str_repeat(' ', $spaces);
        $t    = $pstr . $title . $pstr;

        $len = mb_strlen($t);

        echo '╔' . str_repeat('═', $len) . "╗\n";
        echo '║' . str_repeat(' ', $len) . "║\n";
        echo "║" . $t . "║\n";
        echo '║' . str_repeat(' ', $len) . "║\n";
        echo '╚' . str_repeat('═', $len) . "╝\n\n";
    }



    public function print($text, $color = null, $bgColor = null)
    {
        if ($bgColor) $bgColor = ';' . $bgColor;

        if (!$color && !$bgColor) {
            echo $text;
        } else {
            echo "\e[$color$bgColor" . "m$text\e[0m";
        }
    }



    public function println($text, $color = null, $bgColor = null)
    {
        $this->print($text, $color, $bgColor);
        echo "\n";
    }



    public function printArray(array $a)
    {
        function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
        {
            $diff = strlen($input) - mb_strlen($input);

            return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
        }

        $lengths = [];

        $head = reset($a);
        if (isset($head)) {
            foreach ($head as $k => $null) {
                $head[$k] = $k;
                if (($lengths[$k] ?? 0) < mb_strlen($k)) {
                    $lengths[$k] = mb_strlen($k);
                }
            }
        } else {
            $head = [];
        }

        foreach ($a as $l) {
            foreach ($l as $k => $v) {
                if (($lengths[$k] ?? 0) < mb_strlen($v)) {
                    $lengths[$k] = mb_strlen($v);
                }
            }
        }

        $this->println('');
        $this->print('|');
        foreach ($head as $k => $v) {
            $this->print(mb_str_pad('', $lengths[$k], '-') . ' | ');
        }
        $this->println('');
        if ($head) {
            $this->print('|');
            foreach ($head as $k => $v) {
                $this->print(mb_str_pad($v, $lengths[$k], ' ') . ' | ');
            }
            $this->println('');
            $this->print('|');
            foreach ($head as $k => $v) {
                $this->print(mb_str_pad('', $lengths[$k], '-') . ' | ');
            }
            $this->println('');
        }
        foreach ($a as $n => $l) {
            $this->print('|');
            foreach ($l as $k => $v) {
                $this->print(mb_str_pad($v, $lengths[$k], ' ') . ' | ');
            }
            $this->println('');
        }
        $this->print('|');
        foreach ($head as $k => $v) {
            $this->print(mb_str_pad('', $lengths[$k], '-') . ' | ');
        }
        $this->println('');
    }



    public function msg($message, bool $rewrite = false)
    {
        if ($this->logCurrentLevel <= $this->logLevel) {
            if (is_array($message)) {
                $message = nb2br(var_export($message, true));
            }
            if ($rewrite) {
                if ($this->lastMessage) {
                    $m = $message . str_pad('', strlen($this->lastMessage) - strlen($message) + 2) . "\r";
                } else {
                    $m = $message . "\r";
                }
                $this->print($m);
            } else {
                $this->println($message);
            }
            $this->lastMessage = $message;
            $this->lastRewrite = $rewrite;
        }
    }



    public function error($e)
    {
        if ($e instanceof \Throwable) {
            $e = $e->getMessage();
        }
        if ($this->lastRewrite) $this->println('');
        $this->println($e, self::COLOR_LIGHT_RED);
    }



    public function begin(string $title)
    {
        if ($this->lastMessage) {
            $title .= str_pad('', strlen($this->lastMessage) - strlen($title) + 2);
        }
        $this->lastRewrite = false;
        $this->lastMessage = null;

        $this->logCurrentLevel++;
        if ($this->logCurrentLevel <= $this->logLevel) {
            switch ($this->logCurrentLevel) {
                case 0:
                    $this->printMainTitle($title);
                break;
                case 1:
                    $this->println($title, self::COLOR_LIGHT_CYAN);
                break;
                case 2:
                    $this->println($title, self::COLOR_LIGHT_PURPLE);
                break;
                case 3:
                    $this->println($title);
                break;
            }
        }
    }



    public function end(?string $msg = null): void
    {
        if ($this->lastMessage && $this->lastRewrite) {
            $msg .= str_pad('', strlen($this->lastMessage) - strlen($msg ?? '') + 2);
        }
        if ($msg) {
            $this->println($msg);
        } else {
            $this->println('');
        }
        $this->logCurrentLevel--;
    }



    public function gestExitCode($code, bool $sendException = false): void
    {
        if (0 == $code) return;

        if ($sendException) {
            throw new \Exception("Une erreur ($code) est survenue. Le script est stoppé");
        } else {
            $this->printDie("Une erreur ($code) est survenue. Le script est stoppé");
        }
    }



    public function printDie($text): void
    {
        $this->println($text, self::COLOR_LIGHT_RED);
        $this->println(' -- FIN Prématurée de l\'exécution du script -- ', null, self::BG_RED);
        die("\n");
    }



    public function getArg(int $index = null): string
    {
        $args = isset($_SERVER['argv']) ? $_SERVER['argv'] : [];

        if (null === $index) return $args;

        if (isset($args[$index])) {
            return $args[$index];
        } else {
            return null;
        }
    }



    public function getOptions(): array
    {
        if (null === $this->options) {
            $args = isset($_SERVER['argv']) ? $_SERVER['argv'] : [];

            $this->options = [];
            foreach ($args as $arg) {
                if (0 === strpos($arg, '--')) {
                    $eqpos = strpos($arg, '=');
                    if (false !== $eqpos) {
                        $name                 = substr($arg, 2, $eqpos - 2);
                        $value                = substr($arg, $eqpos + 1);
                        $this->options[$name] = $value;
                    }
                } elseif (0 === strpos($arg, '-')) {
                    $eqpos = strpos($arg, '=');
                    if (false !== $eqpos) {
                        $name                 = substr($arg, 1, $eqpos - 1);
                        $value                = substr($arg, $eqpos + 1);
                        $this->options[$name] = $value;
                    }
                }
            }
        }

        return $this->options;
    }



    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        $options = $this->getOptions();

        return array_key_exists($option, $options);
    }



    /**
     * @param string $value
     * @param string $type
     * @param string $format
     *
     * @return bool|DateTime|float|int|string
     */
    protected function formatOption(string $value, string $type = 'string', string $format = 'd/m/Y')
    {
        switch ($type) {
            case 'boolean':
            case 'bool':
                return in_array(strtolower($value), ['o', 'y', '1', 'ok']);
            case 'date':
                return DateTime::createFromFormat($format, $value);
            case 'float':
                return (float)$value;
            case 'int':
            case 'integer':
                return (int)$value;
        }

        return $value;
    }



    /**
     * @param string $option
     * @param string $type
     *
     * @return mixed|null
     */
    public function getOption(string $option, string $type = 'string', string $format = 'd/m/Y')
    {
        if (!$this->hasOption($option)) return null;

        $value = $this->getOptions()[$option];

        return $this->formatOption($value, $type, $format);
    }



    /**
     * @param array $config
     *
     * @return array
     */
    public function getInputs(array $config): array
    {
        $res = [];
        foreach ($config as $option => $params) {
            $description = null;
            $type        = 'string';
            $format      = 'd/m/Y';
            $silent      = false;
            if (is_string($params)) {
                $description = $params;
            } else {
                if (isset($params['description'])) {
                    $description = $params['description'];
                }
                if (isset($params['type'])) {
                    $type = $params['type'];
                }
                if (isset($params['format'])) {
                    $format = $params['format'];
                }
                if (isset($params['silent'])) {
                    $silent = $params['silent'];
                }
            }

            if ($silent) {
                $res[$option] = $this->getSilentInput($option, $description, $type, $format);
            } else {
                $res[$option] = $this->getInput($option, $description, $type, $format);
            }
        }

        return $res;
    }



    /**
     * @param null   $option
     * @param string $type
     * @param string $format
     *
     * @return bool|DateTime|float|int|mixed|string|null
     */
    public function getInput($option = null, string $description = null, string $type = 'string', string $format = 'd/m/Y')
    {
        if ($option && $this->hasOption($option)) {
            return $this->getOption($option, $type, $format);
        }

        if ($description) {
            $this->println($description);
        }

        return $this->formatOption(trim(fgets(STDIN)), $type, $format);
    }



    /**
     * @param null   $option
     * @param string $type
     * @param string $format
     *
     * @return bool|DateTime|float|int|mixed|string|void|null
     */
    public function getSilentInput($option = null, string $description = null, string $type = 'string', string $format = 'd/m/Y')
    {
        if ($option && $this->hasOption($option)) {
            return $this->getOption($option, $type, $format);
        }

        if ($description) {
            $this->println($description);
        }

        if (preg_match('/^win/i', PHP_OS)) {
            $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
            file_put_contents(
                $vbscript, 'wscript.echo(InputBox("'
                . addslashes('')
                . '", "", "password here"))');
            $command  = "cscript //nologo " . escapeshellarg($vbscript);
            $password = rtrim(shell_exec($command));
            unlink($vbscript);

            return $this->formatOption($password, $type, $format);
        } else {
            $command = "/usr/bin/env bash -c 'echo OK'";
            if (rtrim(shell_exec($command)) !== 'OK') {
                trigger_error("Can't invoke bash");

                return;
            }
            $command  = "/usr/bin/env bash -c 'read -s -p \""
                . addslashes('')
                . "\" mypassword && echo \$mypassword'";
            $password = rtrim(shell_exec($command));
            echo "\n";

            return $this->formatOption($password, $type, $format);
        }
    }



    /**
     * @param      $command
     * @param bool $autoDisplay
     * @param bool $sendException
     *
     * @return mixed
     * @throws Exception
     */
    public function exec($command, bool $autoDisplay = true, bool $sendException = false)
    {
        if (is_array($command)) {
            $command = implode(';', $command);
        }

        exec($command, $output, $return);
        if ($autoDisplay) {
            echo implode("\n", $output) . "\n";
        }
        $this->gestExitCode($return, $sendException);

        return $output;
    }



    /**
     * @param $command
     *
     * @return mixed
     * @throws Exception
     */
    public function passthru($command)
    {
        if (is_array($command)) {
            $command = implode(';', $command);
        }

        passthru($command, $returnVar);
        $this->gestExitCode($returnVar);

        return $returnVar;
    }



    /**
     * @param        $input
     * @param null   $padLength
     * @param string $padString
     *
     * @return string
     */
    public function strPad($input, $padLength = null, $padString = ' ')
    {
        return utf8_encode(str_pad(utf8_decode($input), $padLength, $padString));
    }
}