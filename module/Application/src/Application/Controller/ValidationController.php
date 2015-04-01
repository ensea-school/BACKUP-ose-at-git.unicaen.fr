<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\ServiceValidation;
use Application\Form\Intervenant\ReferentielValidation;
use Application\Rule\Validation\ValidationEnseignementRule;
use Application\Rule\Validation\ValidationReferentielRule;

/**
 * Description of ValidationController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * @var \Application\Entity\Db\Service[]
     */
    private $services;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel[]
     */
    private $referentiels;

    /**
     * @var \Application\Entity\Db\Validation[]
     */
    private $validations;

    /**
     * @var \Application\Entity\Db\Validation[]
     */
    private $validation;

    /**
     * @var boolean
     */
    private $readonly = false;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;

    /**
     * @var ServiceValidation
     */
    private $formValider;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \Zend\View\Model\ViewModel
     */
    private $view;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Validation',
                'Application\Entity\Db\TypeValidation',
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire',
                'Application\Entity\Db\ServiceReferentiel',
                'Application\Entity\Db\VolumeHoraireReferentiel',
            ],
            $this->context()->getGlobalContext()->getDateObservation()
        );
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function dossierAction()
    {
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $role = $this->getContextProvider()->getSelectedIdentityRole();

        if ($role instanceof ComposanteRole) {
            return $this->modifierDossierAction();
        }
        else {
            return $this->voirDossierAction();
        }
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirDossierAction()
    {
        $this->title    = "Validation de vos données personnelles";
        $this->readonly = true;

        $this->commonDossier();

        $this->formValider->get('valide')->setLabel("Si cette case est cochée, cela indique que vos données personnelles ont été validées...");
        $this->view->setTemplate('application/validation/voir-dossier');

        return $this->view;
    }

    /**
     * (Dé)Validation des données personnelles vacataires.
     * NB : une seule validation pour toutes les composantes.
     *
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function modifierDossierAction()
    {
        $this->title    = "Validation des données personnelles <small>$this->intervenant</small>";
        $this->readonly = false;

        $this->commonDossier();

        if ($this->validation->getId()) {
            $this->formValider->get('valide')->setLabel("Décochez pour dévalider les données personnelles");
        }

        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                $complet = (bool) $data['valide'];
                $this->updateValidation($complet);

                return $this->redirect()->toRoute(null, [], [], true);
            }
        }

        $this->view->formModifier = $this->getDossierModifierViewModel()->form;

        return $this->view;
    }

    private function commonDossier()
    {
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceValidation = $this->getServiceValidation();
        $typeValidation    = TypeValidation::CODE_DONNEES_PERSO;

        $this->formValider = $this->getFormDossier()->setIntervenant($this->intervenant)->init();

        $this->initFilters();

        $qb = $serviceValidation->finderByType($typeValidation);
        $serviceValidation->finderByIntervenant($this->intervenant, $qb);
        $serviceValidation->finderByHistorique($qb);
        $this->validation = $qb->getQuery()->getOneOrNullResult();
        if (!$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation);
            $this->validation->setIntervenant($this->intervenant);
            if ($role instanceof \Application\Interfaces\StructureAwareInterface) {
                $this->validation->setStructure($role->getStructure());
            }
        }
        else {
            $this->formValider->get('valide')->setValue(true);
        }
        $this->formValider->bind($this->validation);

        $this->view = new \Zend\View\Model\ViewModel([
            'intervenant' => $this->intervenant,
            'validation'  => $this->validation,
            'form'        => $this->formValider,
            'role'        => $role,
            'readonly'    => $this->readonly,
            'title'       => $this->title,
        ]);
        $this->view->setTemplate('application/validation/dossier');
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    private function getDossierModifierViewModel()
    {
        $controller       = 'Application\Controller\Dossier';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'modifier';
        $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */

        return $viewModel;
    }

    /**
     * Validation des enseignements prévisionnels par la composante d'affectation de l'intervenant.
     *
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     *
     * @todo voirServiceAction et modifierServiceAction doivent sans doute pouvoir être fusionnée...
     */
    public function serviceAction()
    {
        $this->initFilters();

        $serviceService      = $this->getServiceService();
        $serviceValidation   = $this->getServiceValidation();
        $role                = $this->getContextProvider()->getSelectedIdentityRole();
        $typeVolumeHoraire   = $this->getServiceTypeVolumehoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU));
        $this->intervenant   = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider   = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title         = "Validation des enseignements de type '$typeVolumeHoraire' <small>$this->intervenant</small>";
        $typeValidation      = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_ENSEIGNEMENT)->getQuery()->getOneOrNullResult();
        $servicesNonValides  = [];
        $messages            = [];

        // interrogation des règles métiers de validation
        $rule = $this->getServiceLocator()->get('ValidationEnseignementRule') /* @var $rule ValidationEnseignementRule */
                ->setIntervenant($this->intervenant)
                ->setTypeVolumeHoraire($typeVolumeHoraire)
                ->setRole($role)
                ->execute();
        $structuresEns       = $rule->getStructuresIntervention();
        $structureValidation = $rule->getStructureValidation();
//        if (!$rule->isAllowed('read')) {
//            return $this->redirect()->toRoute('home');
//        }

        $this->collectValidationsServices($typeValidation, $typeVolumeHoraire, $structuresEns, $structureValidation);

        $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes sur Service !

        // recherche des enseignements de l'intervenant non encore validés
//        if ($typeVolumeHoraire->isRealise() && $this->intervenant->estPermanent() && in_array(null, $structuresEns)) {
//            $structuresEns = array_filter($structuresEns);
//        }
        $servicesNonValides = $serviceService->fetchServicesDisposPourValidation($typeVolumeHoraire, $this->intervenant, $structuresEns);
        $serviceService->setTypeVolumehoraire($servicesNonValides, $typeVolumeHoraire);

        if (!count($servicesNonValides)) {
            $this->validation = current($this->validations);
            $message = sprintf("Aucun enseignement de type '$typeVolumeHoraire' à valider%s n'a été trouvé.",
                    $structuresEns ? " concernant la structure d'intervention " . implode(" ou ", array_keys($structuresEns)) : null);
            $messages[] = $message;
        }

        if (count($servicesNonValides) && !$this->validation) {
            // instanciation de la nouvelle validation et peuplement avec les volumes horaires non validés
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($structureValidation);
            foreach ($servicesNonValides as $s) { /* @var $s \Application\Entity\Db\Service */
                foreach ($s->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                    $this->validation->addVolumeHoraire($vh);
                }
            }

            $this->validations = [$this->validation->getId() => $this->validation] + $this->validations;
            $this->services    = [$this->validation->getId() => $servicesNonValides] + $this->services;

            $this->formValider->bind($this->validation);

            $messages[] = sprintf("Des enseignements de type '%s' à valider par la structure '%s' ont été trouvés...",
                    $typeVolumeHoraire,
                    $structureValidation);
        }

        $this->view = new \Zend\View\Model\ViewModel([
            'role'              => $role,
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'intervenant'       => $this->intervenant,
            'validations'       => $this->validations,
            'services'          => $this->services,
            'formValider'       => $this->formValider,
            'title'             => $this->title,
            'messages'          => $messages,
        ]);
        $this->view->setTemplate('application/validation/service');

        if ($this->getRequest()->isPost()) {
            $allowed =
                    $this->isAllowed($this->validation, 'create') ||
                    $this->isAllowed($this->validation, 'delete');
            if (!$allowed) {
                return $this->redirect()->toRoute(null, [], [], true);
            }

            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                try {
                    $serviceValidation->enregistrerValidationServices($this->validation/*, $servicesNonValides*/);

                    $this->flashMessenger()->addSuccessMessage("Validation enregistrée avec succès.");
                }
                catch (\Exception $e) {
                    $e        = \Application\Exception\DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }

                return $this->redirect()->toRoute(null, [], [], true);
            }
        }

        return $this->view;
    }

    /**
     *
     * @param TypeValidation $typeValidation
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Structure|array $structureEns
     * @param Structure $structureValidation
     * @return \Application\Controller\ValidationController
     */
    public function collectValidationsServices(
            TypeValidation $typeValidation,
            TypeVolumeHoraire $typeVolumeHoraire,
            $structureEns = null,
            Structure $structureValidation = null)
    {
        $this->initFilters();

        $serviceService = $this->getServiceService();
        $serviceValidation = $this->getServiceValidation();

        $this->services = [];
        $this->validations = [];

        // recherche des enseignements de l'intervenant déjà validés par la composante d'affectation
        $qb = $serviceValidation->finderValidationsServices(
                $typeVolumeHoraire,
                $typeValidation,
                $this->intervenant,
                $structureEns,
                $structureValidation);
        $validationsServices = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
        foreach ($validationsServices as $validation) { /* @var $validation \Application\Entity\Db\Validation */

            $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes concernant les services !

            $qb = $serviceService->finderServicesValides(
                    $typeVolumeHoraire,
                    $validation,
                    $this->intervenant,
                    $structureEns,
                    $structureValidation);
            $servicesValides = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
            $serviceService->setTypeVolumeHoraire($servicesValides, $typeVolumeHoraire);

            $this->validations[$validation->getId()] = $validation;
            $this->services[$validation->getId()]    = $servicesValides;
        }

        return $this;
    }

    /**
     * (Dé)Validation du référentiel.
     *
     * Un gestionnaire de composante (dé)valide le référentiel des intervenants affectés à sa composante.
     * NB: un rôle sans structure (ex: administrateur) peut aussi (dé)valider (ssi il est habilité).
     *
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function referentielAction()
    {
        $serviceReferentiel     = $this->getServiceReferentiel();
        $serviceValidation      = $this->getServiceValidation();
        $role                   = $this->getContextProvider()->getSelectedIdentityRole();
        $typeVolumeHoraire      = $this->getServiceTypeVolumehoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU));
        $this->intervenant      = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider      = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title            = "Validation du référentiel de type '$typeVolumeHoraire' <small>$this->intervenant</small>";
        $structureAffect        = $this->intervenant->getStructure();
        $typeValidation         = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_REFERENTIEL)->getQuery()->getOneOrNullResult();
        $referentielsNonValides = [];
        $messages               = [];

        $this->initFilters();

        // interrogation des règles métiers de validation
        $rule = $this->getServiceLocator()->get('ValidationReferentielRule') /* @var $rule ValidationReferentielRule */
                ->setIntervenant($this->intervenant)
                ->setTypeVolumeHoraire($typeVolumeHoraire)
                ->setRole($role);
        try {
            $rule->execute();
        }
        catch (LogicException $le) {
            throw new MessageException("Validation du référentiel impossible.", null, $le);
        }

        $structureRef        = $rule->getStructuresIntervention();
        $structureValidation = $rule->getStructureValidation();

        // collecte des validations et des référentiels associés
        $this->collectValidationsReferentiels($typeValidation, $typeVolumeHoraire, $structureRef, $structureValidation);

        $this->em()->clear('Application\Entity\Db\ServiceReferentiel'); // INDISPENSABLE entre 2 requêtes sur ServiceReferentiel !

        // recherche des référentiels de l'intervenant non encore validés
        $qb = $serviceReferentiel->finderReferentielsNonValides($typeVolumeHoraire, $this->intervenant, $structureRef);
        $referentielsNonValides = $qb->getQuery()->getResult();

        if (!count($referentielsNonValides)) {
            $this->validation = current($this->validations);
            $message = sprintf("Aucun référentiel de type '%s' %s à valider n'a été trouvé.",
                    $typeVolumeHoraire,
                    $structureRef ? " concernant la structure '$structureRef'" : null);
            $messages[] = $message;
        }

        if (count($referentielsNonValides) && !$this->validation) {
            // instanciation de la nouvelle validation et peuplement avec les volumes horaires non validés
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($structureValidation ?: $structureAffect);
            foreach ($referentielsNonValides as $s) { /* @var $s \Application\Entity\Db\ServiceReferentiel */
                foreach ($s->getVolumeHoraireReferentiel() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraireReferentiel */
                    $this->validation->addVolumeHoraireReferentiel($vh);
                }
            }

            $this->validations  = [$this->validation->getId() => $this->validation] + $this->validations;
            $this->referentiels = [$this->validation->getId() => $referentielsNonValides] + $this->referentiels;

            $this->formValider->bind($this->validation);

            $messages[] = sprintf("Du référentiel de type '%s' à valider par la structure '%s' ont été trouvés...",
                    $typeVolumeHoraire,
                    $structureValidation);
        }

        $this->view = new \Zend\View\Model\ViewModel([
            'role'                 => $role,
            'typeVolumeHoraire'    => $typeVolumeHoraire,
            'intervenant'          => $this->intervenant,
            'validations'          => $this->validations,
            'referentiels'         => $this->referentiels,
            'formValider'          => $this->formValider,
            'title'                => $this->title,
            'messages'             => $messages,
        ]);
        $this->view->setTemplate('application/validation/referentiel');

        if ($this->getRequest()->isPost()) {

            $allowed =
                    $this->isAllowed($this->validation, 'create') ||
                    $this->isAllowed($this->validation, 'delete');
            if (!$allowed) {
                return $this->redirect()->toRoute(null, [], [], true);
            }

            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                $serviceValidation->save($this->validation);
                $this->flashMessenger()->addSuccessMessage("Validation enregistrée avec succès.");

                return $this->redirect()->toRoute(null, [], [], true);
            }
        }

        return $this->view;
    }

    /**
     *
     * @param TypeValidation $typeValidation
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Structure $structureRef
     * @param Structure $structureValidation
     * @return \Application\Controller\ValidationController
     */
    public function collectValidationsReferentiels(
            TypeValidation $typeValidation,
            TypeVolumeHoraire $typeVolumeHoraire,
            Structure $structureRef = null,
            Structure $structureValidation = null)
    {
        $this->initFilters();

        $serviceReferentiel = $this->getServiceReferentiel();
        $serviceValidation  = $this->getServiceValidation();

        $this->referentiels = [];
        $this->validations  = [];

        // recherche des référentiels de l'intervenant déjà validés par la structure spécifiée
        $qb = $serviceValidation->finderValidationsReferentiels(
                $typeVolumeHoraire,
                $typeValidation,
                $this->intervenant,
                $structureRef,
                $structureValidation);
        $validationsReferentiels = $qb->getQuery()->getResult();
        foreach ($validationsReferentiels as $validation) { /* @var $validation \Application\Entity\Db\Validation */

            $this->em()->clear('Application\Entity\Db\ServiceReferentiel'); // INDISPENSABLE entre 2 requêtes concernant le référentiel !

            $qb = $serviceReferentiel->finderReferentielsValides(
                    $typeVolumeHoraire,
                    $validation,
                    $this->intervenant,
                    $structureRef,
                    $structureValidation);
            $referentielsValides = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();

            $this->validations[$validation->getId()]  = $validation;
            $this->referentiels[$validation->getId()] = $referentielsValides;
        }

        return $this;
    }

    /**
     *
     * @throws RuntimeException
     */
    public function supprimerAction()
    {
        $role       = $this->getContextProvider()->getSelectedIdentityRole();
        $validation = $this->context()->mandatory()->validationFromRoute(); /* @var $validation \Application\Entity\Db\Validation */

        if ($role instanceof \Application\Interfaces\StructureAwareInterface) {
            if ($validation->getStructure() !== $role->getStructure()) {
                throw new RuntimeException("Suppression de la validation interdite.");
            }
        }

        $title     = "Suppression de la validation";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        if ($this->getRequest()->isPost()) {
            $errors = [];
            try {
                $this->getServiceValidation()->supprimer($validation);

                $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
            }
            catch(\Exception $e){
                $errors[\UnicaenApp\View\Helper\Messenger::ERROR] = $e->getMessage();
            }

            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }

    /**
     *
     * @param bool $valide
     * @return boolean
     */
    protected function updateValidation($valide)
    {
        if ($valide) {
            $this->getServiceValidation()->enregistrerValidationDossier($this->validation);
        }
        else {
            $this->getServiceValidation()->supprimer($this->validation);
        }
        $this->em()->flush();

        $this->flashMessenger()->addSuccessMessage(sprintf("Validation <strong>%s</strong> avec succès.", $valide ? "enregistrée" : "supprimée"));

        return $this;
    }

    /**
     * @return DossierValidation
     */
    protected function getFormDossier()
    {
        if (null === $this->formValider) {
            $this->formValider = new DossierValidation();
        }

        return $this->formValider;
    }

    /**
     * @return ServiceValidation
     */
    protected function getFormValidationService()
    {
        if (null === $this->formValider) {
            $this->formValider = new ServiceValidation();
        }

        return $this->formValider;
    }

    /**
     * @return ReferentielValidation
     */
    protected function getFormValidationReferentiel()
    {
        if (null === $this->formValider) {
            $this->formValider = new ReferentielValidation();
        }

        return $this->formValider;
    }

    /**
     * @return \Application\Service\Validation
     */
    private function getServiceValidation()
    {
        return $this->getServiceLocator()->get('ApplicationValidation');
    }

    /**
     * @return \Application\Service\Service
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    private function getServiceReferentiel()
    {
        return $this->getServiceLocator()->get('ApplicationServiceReferentiel');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    private function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\TypeValidation
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}