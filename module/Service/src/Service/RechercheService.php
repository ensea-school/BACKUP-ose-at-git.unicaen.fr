<?php

namespace Service\Service;

use Application\Service\AbstractService;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Enseignement\Service\ServiceService;
use Laminas\Session\Container as SessionContainer;
use Service\Entity\Recherche;
use Service\Hydrator\RechercheHydratorAwareTrait;

class RechercheService extends AbstractService
{
    use LocalContextServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use RechercheHydratorAwareTrait;


    /**
     *
     * @var SessionContainer
     */
    private $rechercheSessionContainer;

    /**
     *
     * @var Recherche
     */
    private $recherche;



    /**
     *
     * @return SessionContainer
     */
    protected function getRechercheSessionContainer()
    {
        if (null === $this->rechercheSessionContainer) {
            $this->rechercheSessionContainer = new SessionContainer(get_class($this) . '_Recherche');
        }

        return $this->rechercheSessionContainer;
    }



    /**
     * Les paramètres de recherche sont également remplis à l'aide du contexte local
     *
     * @return Recherche
     */
    public function loadRecherche()
    {
        if (null === $this->recherche) {
            $this->recherche = new Recherche;
            $session         = $this->getRechercheSessionContainer();
            if ($session->offsetExists('data')) {
                $this->getHydratorServiceRecherche()->hydrate($session->data, $this->recherche);
            }
        }

        if (!$this->recherche->getTypeVolumeHoraire()) {
            $this->recherche->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }

        if (!$this->recherche->getEtatVolumeHoraire()) {
            $this->recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
        }

        $localContext = $this->getServiceLocalContext();

        $this->recherche->setIntervenant($localContext->getIntervenant());
        $this->recherche->setStructureEns($localContext->getStructure());
        $this->recherche->setNiveauEtape($localContext->getNiveau());
        $this->recherche->setEtape($localContext->getEtape());
        $this->recherche->setElementPedagogique($localContext->getElementPedagogique());

        return $this->recherche;
    }



    /**
     * Les paramètres de recherche sont également sauvegardés dans le contexte local
     *
     * @param Recherche $recherche
     *
     * @return self
     */
    public function saveRecherche(Recherche $recherche)
    {
        if ($recherche !== $this->recherche) {
            $this->recherche = $recherche;
        }
        $data          = $this->getHydratorServiceRecherche()->extract($recherche);
        $session       = $this->getRechercheSessionContainer();
        $session->data = $data;

        $localContext = $this->getServiceLocalContext();

        $localContext->setIntervenant($recherche->getIntervenant());
        $localContext->setStructure($recherche->getStructureEns());
        $localContext->setNiveau($recherche->getNiveauEtape());
        $localContext->setEtape($recherche->getEtape());
        $localContext->setElementPedagogique($recherche->getElementPedagogique());

        return $this;
    }

}