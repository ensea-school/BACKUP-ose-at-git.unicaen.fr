<?php

namespace Application\Processus;

use Application\Processus\Intervenant\RechercheProcessus;
use Application\Processus\Intervenant\SuppressionProcessus;
use Application\Processus\Intervenant\ServiceProcessus as IntervenantServiceProcessus;


/**
 * Description of IntervenantProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessus extends AbstractProcessus
{

    /**
     * @var RechercheProcessus
     */
    protected $recherche;

    /**
     * @var SuppressionProcessus
     */
    protected $suppression;

    /**
     * @var IntervenantServiceProcessus
     */
    protected $service;



    public function recherche(): RechercheProcessus
    {
        if (!$this->recherche) {
            $this->recherche = new RechercheProcessus;
            $this->recherche->setEntityManager($this->getEntityManager());
        }

        return $this->recherche;
    }



    public function suppression(): SuppressionProcessus
    {
        if (!$this->suppression) {
            $this->suppression = new SuppressionProcessus;
        }

        return $this->suppression;
    }



    public function service(): IntervenantServiceProcessus
    {
        if (!$this->service) {
            $this->service = new IntervenantServiceProcessus;
            $this->service->setEntityManager($this->getEntityManager());
        }

        return $this->service;
    }
}