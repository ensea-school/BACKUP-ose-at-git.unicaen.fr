<?php

namespace BddAdmin\Logger;

interface LoggerInterface
{
    public function error($e);



    public function begin(string $title);



    public function end(?string $msg = null);



    public function msg($message, bool $rewrite = false);
}