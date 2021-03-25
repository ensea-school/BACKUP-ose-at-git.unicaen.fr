<?php

namespace ExportRh\Connecteur\Siham;

use Application\Entity\Db\Intervenant;
use ExportRh\Connecteur\ExportRhInterface;

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



    public function intervenantExists(Intervenant $intervenant): bool
    {
        // TODO: Implement intervenantExists() method.
    }



    public function intervenantDiff(Intervenant $intervenant): array
    {
        // TODO: Implement intervenantDiff() method.
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