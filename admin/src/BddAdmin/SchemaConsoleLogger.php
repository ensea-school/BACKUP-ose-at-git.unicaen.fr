<?php

namespace BddAdmin;

class SchemaConsoleLogger implements SchemaLoggerInterface
{

    /**
     * @var \Console
     */
    public $console;



    public function log(string $message)
    {
        if ($this->console) {
            $this->console->println($message);
        }
    }



    public function logTitle(string $title)
    {
        if ($this->console) {
            $this->console->println($title, $this->console::COLOR_LIGHT_PURPLE);
        }
    }

}