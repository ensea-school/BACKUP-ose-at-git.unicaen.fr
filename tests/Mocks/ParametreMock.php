<?php

namespace tests\Mocks;

use Application\Service\ParametresService;

class ParametreMock extends ParametresService
{

    private array $parametres = [];



    public function get($param)
    {
        if (!isset($this->parametres[$param])){
            throw new \Exception("Parametre $param non défini par défaut");
        }

        return $this->parametres[$param];
    }



    public function set($param, $value)
    {
        $this->parametres[$param] = $value;
    }



    public function setParametres(array $parametres)
    {
        foreach( $parametres as $param => $value){
            $this->set($param, $value);
        }
    }



    public function __construct(array $parametres = [])
    {
        $this->setParametres($parametres);
    }

}