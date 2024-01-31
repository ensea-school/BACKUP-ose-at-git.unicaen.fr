<?php

class OseEnv
{
    private OseAdmin $oa;



    public function __construct(OseADmin $oa)
    {
        $this->oa = $oa;
    }



    public function inConsole()
    {
        return PHP_SAPI == 'cli';
    }



    public function getEnv()
    {
        $forcedEnv = $this->oa->config()->get('dev', 'forced-env', false);

        if (false !== $forcedEnv) return $forcedEnv;

        return getenv('APPLICATION_ENV') ?: 'dev';
    }



    public function inDev()
    {
        return 'dev' == $this->getEnv();
    }



    public function inTest()
    {
        return 'test' == $this->getEnv();
    }



    public function inProd()
    {
        return 'prod' == $this->getEnv();
    }
}