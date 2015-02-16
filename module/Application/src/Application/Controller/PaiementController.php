<?php

namespace Application\Controller;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;

/**
 * @method \Application\Controller\Plugin\Context     context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etape')
                ->disableForEntity('Application\Entity\Db\Etablissement')
                ->disableForEntity('Application\Entity\Db\FonctionReferentiel');
    }

    public function indexAction()
    {
        return [];
    }

    public function demandeMiseEnPaiementAction()
    {
        $this->initFilters();
        $intervenant        = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant \Application\Entity\Db\Intervenant */
        $annee              = $this->context()->getGlobalContext()->getAnnee();
        if ($this->getRequest()->isPost()) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            //var_dump($changements);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
        }
        $servicesAPayer     = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant, $annee);
        return compact('intervenant', 'servicesAPayer');
    }

    /**
     * @return \Application\Service\MiseEnPaiement
     */
    protected function getServiceMiseEnPaiement()
    {
        return $this->getServiceLocator()->get('applicationMiseEnPaiement');
    }

    /**
     * @return \Application\Service\ServiceAPayer
     */
    protected function getServiceServiceAPayer()
    {
        return $this->getServiceLocator()->get('applicationServiceAPayer');
    }
}