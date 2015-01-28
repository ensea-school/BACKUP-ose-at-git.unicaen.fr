<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Exception\DbException;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\IntervenantPermanent;
use Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset;
use Application\Acl\ComposanteRole;

/**
 * Description of ServiceReferentielController
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\FonctionReferentiel');
    }

    /**
     *
     * @param Intervenant|null $intervenant
     * @param Recherche $recherche
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getFilteredServices($intervenant, $recherche)
    {
                //\Test\Util::sqlLog($this->getServiceService()->getEntityManager());
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $serviceReferentiel              = $this->getServiceServiceReferentiel();
        $volumeHoraireReferentielService = $this->getServiceLocator()->get('applicationVolumehoraireReferentiel'); /* @var $volumeHoraireReferentielService \Application\Service\VolumeHoraireReferentiel */

        $this->initFilters();
        $qb = $serviceReferentiel->initQuery()[0];

        $serviceReferentiel
            ->join(     'applicationIntervenant',         $qb, 'intervenant',              ['id', 'nomUsuel', 'prenom','sourceCode'] )
            ->leftjoin( $volumeHoraireReferentielService, $qb, 'volumeHoraireReferentiel', ['id', 'heures'] );

//        $volumeHoraireReferentielService
//            ->leftJoin( 'applicationMotifNonPaiement',  $qb, 'motifNonPaiement',    ['id', 'libelleCourt', 'libelleLong'] );

        $serviceReferentiel->finderByContext($qb);
        $serviceReferentiel->finderByFilterObject($recherche, new \Zend\Stdlib\Hydrator\ClassMethods(false), $qb);

        if ($intervenant) {
            $serviceReferentiel->finderByIntervenant($intervenant, $qb);
        }
        if (! $intervenant && $role instanceof \Application\Acl\ComposanteRole){
            $serviceReferentiel->finderByStructure($role->getStructure(), $qb);
        }
        
        return $qb;
    }

    public function indexAction()
    {
        $typeVolumeHoraireCode    = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU' );
        $totaux                   = $this->params()->fromQuery('totaux', 0) == '1';
        $viewHelperParams         = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $role                     = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant              = $this->context()->intervenantFromRoute();
        $annee                    = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $viewModel                = new \Zend\View\Model\ViewModel();
        $canAddService            = $this->isAllowed($this->getServiceService()->newEntity()->setIntervenant($intervenant), 'create');
        $canAddServiceReferentiel = $intervenant instanceof IntervenantPermanent &&
                $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'create');

        if (! $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'read')) {
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        if (! $intervenant){
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'recherche';
            $rechercheViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($rechercheViewModel, 'recherche');
            
            $recherche = $this->getServiceService()->loadRecherche();
        }else{
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action = 'afficher'; // Affichage par défaut
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire( $this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode) );
            $recherche->setEtatVolumeHoraire( $this->getServiceEtatVolumeHoraire()->getSaisi() );

            $params = [
                'intervenant'   => $intervenant->getSourceCode(),
                'action'        => 'formule-totaux-hetd',
            ];
            $this->getEvent()->setParam('typeVolumeHoraire', $recherche->getTypeVolumeHoraire() );
            $this->getEvent()->setParam('etatVolumeHoraire', $recherche->getEtatVolumeHoraire() );
            $totalViewModel   = $this->forward()->dispatch('Application\Controller\Intervenant', $params);
            $viewModel->addChild($totalViewModel, 'formuleTotauxHetd');
        }

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux){
            $qb = $this->getFilteredServices($intervenant, $recherche);
            $services = $this->getServiceService()->getList($qb);
            $this->getServiceService()->setTypeVolumehoraire($services, $recherche->getTypeVolumeHoraire());

            // services référentiels : délégation au contrôleur
            if (! $totaux){
                $rech             = clone $recherche;
                $controller       = 'Application\Controller\ServiceReferentiel';
                $params           = $this->getEvent()->getRouteMatch()->getParams();
                $params['action'] = 'voirListe';
                $params['recherche'] = $rech->setTypeVolumeHoraire(null); /** @todo recherche également au niveau du service référentiel */
                $params['query']  = $this->params()->fromQuery();
                $params['renderIntervenants'] = ! $intervenant;
                $listeViewModel   = $this->forward()->dispatch($controller, $params);
                //$viewModel->addChild($listeViewModel, 'servicesRefListe');
            }
        }else{
            $services = [];
        }
        $renderReferentiel  = !$intervenant instanceof IntervenantExterieur;
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params = $viewHelperParams;
        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire','action', 'role', 'intervenant', 'renderReferentiel','canAddService', 'canAddServiceReferentiel', 'params'));
        if ($totaux){
            $viewModel->setTemplate('application/service/rafraichir-totaux');
        }else{
            $viewModel->setTemplate('application/service/index');
        }
        return $viewModel;
    }
    
    /**
     * @return \Application\Service\ServiceReferentiel
     */
    public function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->get('ApplicationServiceReferentiel');
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    public function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}
