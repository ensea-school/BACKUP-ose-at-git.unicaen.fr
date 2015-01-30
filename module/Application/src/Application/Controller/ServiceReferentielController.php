<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Exception\DbException;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\IntervenantPermanent;
use Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset;
use Application\Acl\ComposanteRole;
use Application\Entity\Service\Recherche;

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
        $typeVolumeHoraireCode    = $this->params()->fromRoute('type-volume-horaire-code', 'PREVU');
        $totaux                   = $this->params()->fromQuery('totaux', 0) == '1';
        $viewHelperParams         = $this->params()->fromPost('params', $this->params()->fromQuery('params'));
        $role                     = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant              = $this->context()->intervenantFromRoute();
        $annee                    = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $viewModel                = new \Zend\View\Model\ViewModel();
        $canAddService            = $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'create');
        $canAddServiceReferentiel = $intervenant instanceof IntervenantPermanent &&
                $this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'create');

        if (!$this->isAllowed($this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant), 'read')) {
            throw new \BjyAuthorize\Exception\UnAuthorizedException();
        }

        if (!$intervenant) {
//            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
//            $params = $this->getEvent()->getRouteMatch()->getParams();
//            $params['action'] = 'recherche';
//            $rechercheViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
//            $viewModel->addChild($rechercheViewModel, 'recherche');
//            
//            $recherche = $this->getServiceServiceReferentiel()->loadRecherche();
            throw new LogicException("Pas implémenté!");
        }
        else {
            $this->getContextProvider()->getLocalContext()->setIntervenant($intervenant); // passage au contexte pour le présaisir dans le formulaire de saisie
            $action    = 'afficher'; // Affichage par défaut
            $recherche = new Recherche;
            $recherche->setTypeVolumeHoraire($this->getServiceTypeVolumehoraire()->getByCode($typeVolumeHoraireCode));
            $recherche->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());

            $params         = [
                'intervenant' => $intervenant->getSourceCode(),
                'action'      => 'formule-totaux-hetd',
            ];
            $this->getEvent()->setParam('typeVolumeHoraire', $recherche->getTypeVolumeHoraire());
            $this->getEvent()->setParam('etatVolumeHoraire', $recherche->getEtatVolumeHoraire());
        }

        /* Préparation et affichage */
        if ('afficher' === $action || $totaux) {
            $qb       = $this->getFilteredServices($intervenant, $recherche);
            $services = $this->getServiceServiceReferentiel()->getList($qb);
            $this->getServiceServiceReferentiel()->setTypeVolumeHoraire($services, $recherche->getTypeVolumeHoraire());
        }
        else {
            $services = [];
        }
        
        $renderReferentiel = !$intervenant instanceof IntervenantExterieur;
        $typeVolumeHoraire = $recherche->getTypeVolumeHoraire();
        $params            = $viewHelperParams;
        
        $viewModel->setVariables(compact('annee', 'services', 'typeVolumeHoraire', 'action', 'role', 'intervenant', 'renderReferentiel', 'canAddService', 'canAddServiceReferentiel', 'params'));
        
        if ($totaux) {
            $viewModel->setTemplate('application/service-referentiel/rafraichir-totaux');
        }
        
        return $viewModel;
    }

    public function saisieAction()
    {
        $this->initFilters();
        $id = (int)$this->params()->fromRoute('id');
        $typeVolumeHoraire = $this->params()->fromQuery('type-volume-horaire', $this->params()->fromPost('type-volume-horaire') );
        if (empty($typeVolumeHoraire)){
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getPrevu();
        }else{
            $typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->get( $typeVolumeHoraire );
        }
        $service = $this->getServiceServiceReferentiel();
        //$role    = $this->getContextProvider()->getSelectedIdentityRole();
        $form    = $this->getFormSaisie();
        $errors  = array();

        if ($id) {
            $entity = $service->get($id);
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $title   = "Modification de référentiel";
        } else {
            $entity = $service->newEntity();
            $entity->setTypeVolumeHoraire($typeVolumeHoraire);
            $form->bind($entity);
            $form->initFromContext();
            $title   = "Ajout de référentiel";
        }

        $intervenant = $this->getContextProvider()->getLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'create') || ! $this->isAllowed($assertionEntity, 'update')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->saveToContext();
            if ($form->isValid()) {
                try {
                    $entity = $service->save($entity);
                    $form->get('service')->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(compact('form','errors','title'));
        
        return $viewModel;
    }

    public function rafraichirLigneAction()
    {
        $this->initFilters();

        $params             = $this->params()->fromPost('params', $this->params()->fromQuery('params') );
        $details            = 1 == (int)$this->params()->fromQuery('details',               (int)$this->params()->fromPost('details',0));
        $onlyContent        = 1 == (int)$this->params()->fromQuery('only-content',          0);
        $service = $this->context()->serviceReferentielFromRoute('id'); // remplacer id par service au besoin, à cause des routes définies en config.

        return compact('service', 'params', 'details', 'onlyContent');
    }

    public function suppressionAction()
    {
        $id        = (int) $this->params()->fromRoute('id', 0);
        $service   = $this->getServiceServiceReferentiel();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression de référentiel";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $intervenant = $this->getContextProvider()->getLocalContext()->getIntervenant();
        $assertionEntity = $this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'delete')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                $service->delete($entity);
            }
            catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }

    /**
     *
     * @return \Application\Form\ServiceReferentiel\Saisie
     */
    protected function getFormSaisie()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('ServiceReferentielSaisie');
    }

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    private function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->get('ApplicationServiceReferentiel');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    private function getServiceTypeVolumehoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\EtatVolumeHoraire
     */
    private function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }
}
