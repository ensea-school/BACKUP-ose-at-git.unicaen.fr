<?php

namespace ExportRh\Connecteur\Siham;


use ExportRh\Connecteur\ExportRhInterface;
use ExportRh\Entity\Intervenant;

class SihamConnecteur implements ExportRhInterface
{
    /**
     * @var array
     */
    private $config = [];



    public function __construct(array $config)
    {
        $this->config = $config;
    }



    public function intervenantEquivalents(\Application\Entity\Db\Intervenant $intervenant): Intervenant
    {
        
    }



    public function intervenantExport(Intervenant $intervenant): bool
    {
        // TODO: Implement intervenantExport() method.
    }



    public function test()
    {
        echo 'test réussi';
        var_dump($this->config);
    }
}