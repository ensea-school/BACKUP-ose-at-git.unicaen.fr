<?php

namespace Application\Controller;

use Application\Acl\IntervenantRole;
use Application\Entity\Db\Intervenant;
use Zend\Mvc\Controller\AbstractActionController;
use UnicaenApp\Util;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\ServiceValidation;
use Application\Form\Intervenant\ReferentielValidation;
use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Description of ValidationController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\StructureAwareTrait;

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
    private $isReferentiel;

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
                'Application\Entity\Db\Dossier',
                'Application\Entity\Db\Service',
                'Application\Entity\Db\VolumeHoraire',
                'Application\Entity\Db\ServiceReferentiel',
                'Application\Entity\Db\VolumeHoraireReferentiel',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }

    /**
     * Validation ou dévalidation des données personnelles.

     * NB : une seule validation pour toutes les composantes.
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function dossierAction()
    {
        $prg = $this->prg();
        
        if ($prg instanceof \Zend\Http\Response) {
            return $prg;
        }
        
        $this->initFilters();
        
        $intervenant       = $this->getIntervenant();
        $dossier           = $intervenant->getDossier();
        $role              = $this->getServiceContext()->getSelectedIdentityRole();
        $serviceValidation = $this->getServiceValidation();
        $typeValidation    = TypeValidation::CODE_DONNEES_PERSO;
        $this->title       = "Validation des données personnelles <small>$intervenant</small>";
        
        // recherche validation existante et instanciation si aucune trouvée
        $qb = $serviceValidation->finderByType($typeValidation);
        $serviceValidation->finderByIntervenant($intervenant, $qb);
        $serviceValidation->finderByHistorique($qb);
        $this->validation = $qb->getQuery()->getOneOrNullResult();
        
        if (! $this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation);
            $this->validation
                    ->setIntervenant($intervenant)
                    ->setStructure($role->getStructure());
        }
        
        $this->formValider = $this->getFormDossier($this->validation);

        $canEdit = $dossier && 
                ($this->isAllowed($this->validation, 'create') || $this->isAllowed($this->validation, 'delete'));
        
        if ($canEdit && is_array($prg)) {
            $data = $prg;
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                $complet = (bool) $data['valide'];
                $this->updateValidation($complet);

                return $this->redirect()->refresh();
            }
        }

        $this->view = new \Zend\View\Model\ViewModel([
            'intervenant' => $intervenant,
            'dossier'     => $dossier,
            'validation'  => $this->validation,
            'form'        => $this->formValider,
            'role'        => $role,
            'canEdit'     => $canEdit,
            'title'       => $this->title,
        ]);
        
        $this->view->formModifier = $this->getFormDossierModifier();
        
        return $this->view;
        
    }

    /**
     * Interrogation du contrôleur Dossier pour obtenir le formulaire de saisie des données personnelles.
     *
     * @return \Zend\Form\Form
     */
    private function getFormDossierModifier()
    {
        $controller       = 'Application\Controller\Dossier';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'modifier';
        $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */

        return $viewModel->form;
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

        $this->isReferentiel = false;
        $serviceService      = $this->getServiceService();
        $serviceValidation   = $this->getServiceValidation();
        $role                = $this->getServiceContext()->getSelectedIdentityRole();
        $typeVolumeHoraire   = $this->getTypeVolumeHoraire();
        $this->formValider   = $this->getFormValidationService()->setIntervenant($this->getIntervenant())->init();
        $this->title         = "Validation des enseignements de type '$typeVolumeHoraire' <small>{$this->getIntervenant()}</small>";
        $typeValidation      = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_ENSEIGNEMENT)->getQuery()->getOneOrNullResult();
        $messages            = [];

        $this->getEvent()->setParam('typeVolumeHoraire', $typeVolumeHoraire);
        
        // interrogation des règles métiers de validation
        $rule = $this->getServiceLocator()->get('ValidationEnseignementRule') /* @var $rule ValidationEnsRefAbstractRule */
                ->setIntervenant($this->getIntervenant())
                ->setTypeVolumeHoraire($typeVolumeHoraire)
                ->setRole($role)
                ->execute();
        $structuresEns       = $rule->getStructuresIntervention();
        $structureValidation = $rule->getStructureValidation();

        $this->collectValidationsServices($typeValidation, $typeVolumeHoraire, $structuresEns, $structureValidation);

        $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes sur Service !

        // recherche des enseignements de l'intervenant non encore validés
        $servicesNonValides = $serviceService->fetchServicesDisposPourValidation($typeVolumeHoraire, $this->getIntervenant(), $structuresEns);
        $serviceService->setTypeVolumehoraire($servicesNonValides, $typeVolumeHoraire);

        if (!count($servicesNonValides)) {
            $this->validation = current($this->validations);
            if (! $role instanceof IntervenantRole) {
                $message = sprintf("Aucun enseignement de type '$typeVolumeHoraire' à valider%s n'a été trouvé.",
                    $structuresEns ? " concernant la structure d'intervention " . implode(" ou ", array_keys($structuresEns)) : null);
                $messages[] = $message;
            }
        }

        if (count($servicesNonValides) && !$this->validation) {
            // instanciation de la nouvelle validation et peuplement avec les volumes horaires non validés
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->getIntervenant())
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
            'intervenant'       => $this->getIntervenant(),
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

        $this->injectAttenteValidationMessage();
        
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
                $this->getIntervenant(),
                $structureEns,
                $structureValidation);
        $validationsServices = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
        foreach ($validationsServices as $validation) { /* @var $validation \Application\Entity\Db\Validation */

            $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes concernant les services !

            $qb = $serviceService->finderServicesValides(
                    $typeVolumeHoraire,
                    $validation,
                    $this->getIntervenant(),
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
        $this->isReferentiel    = true;
        $serviceReferentiel     = $this->getServiceReferentiel();
        $serviceValidation      = $this->getServiceValidation();
        $role                   = $this->getServiceContext()->getSelectedIdentityRole();
        $typeVolumeHoraire      = $this->getServiceTypeVolumehoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU));
        $this->formValider      = $this->getFormValidationService()->setIntervenant($this->getIntervenant())->init();
        $this->title            = "Validation du référentiel de type '$typeVolumeHoraire' <small>{$this->getIntervenant()}</small>";
        $structureAffect        = $this->getIntervenant()->getStructure();
        $typeValidation         = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_REFERENTIEL)->getQuery()->getOneOrNullResult();
        $messages               = [];

        $this->initFilters();

        // interrogation des règles métiers de validation
        $rule = $this->getServiceLocator()->get('ValidationReferentielRule') /* @var $rule ValidationEnsRefAbstractRule */
                ->setIntervenant($this->getIntervenant())
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
        $qb = $serviceReferentiel->finderReferentielsNonValides($typeVolumeHoraire, $this->getIntervenant(), $structureRef);
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
                    ->setIntervenant($this->getIntervenant())
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
            'intervenant'          => $this->getIntervenant(),
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

        $this->injectAttenteValidationMessage();
        
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
                $this->getIntervenant(),
                $structureRef,
                $structureValidation);
        $validationsReferentiels = $qb->getQuery()->getResult();
        foreach ($validationsReferentiels as $validation) { /* @var $validation \Application\Entity\Db\Validation */

            $this->em()->clear('Application\Entity\Db\ServiceReferentiel'); // INDISPENSABLE entre 2 requêtes concernant le référentiel !

            $qb = $serviceReferentiel->finderReferentielsValides(
                    $typeVolumeHoraire,
                    $validation,
                    $this->getIntervenant(),
                    $structureRef,
                    $structureValidation);
            $referentielsValides = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();

            $this->validations[$validation->getId()]  = $validation;
            $this->referentiels[$validation->getId()] = $referentielsValides;
        }

        return $this;
    }

    /**
     * Recherche les composantes qui n'ont pas encore validé les enseignements réalisé
     *
     * @return self
     */
    private function injectAttenteValidationMessage()
    {
        $this->view->attenteValidationMessage = null;
        
        // on ne s'intéresse qu'aux permanents
        if (! $this->getIntervenant()->getStatut()->estPermanent()) {
            return $this;
        }
        // on ne s'intéresse qu'au Réalisé
        if ($this->getTypeVolumeHoraire()->getCode() !== TypeVolumeHoraire::CODE_REALISE) {
            return $this;
        }
        
        $entityClass = $this->isReferentiel ? 
                'Application\Entity\Db\VIndicAttenteValidationServiceRef' :
                'Application\Entity\Db\VIndicAttenteValidationService';
        
        $dqlAttenteValidation = $this->em()->createQueryBuilder()
                ->select("v.id")
                ->from($entityClass, 'v')
                ->join("v.intervenant", "i", Join::WITH, "i = :i")
                ->join("v.structure", "s")
                ->join("v.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                ->andWhere("v.structure = str")
                ->getQuery()
                ->getDQL();
        
        list($qb,) = $this->getServiceStructure()->initQuery();
        $qb
                ->select("str.libelleCourt")
                ->andWhere("EXISTS ( $dqlAttenteValidation )")
                ->orderBy("str.libelleCourt")
                ->setParameter('tvh', $this->getTypeVolumeHoraire())
                ->setParameter('i', $this->getIntervenant());
        
        $structures = $qb->getQuery()->getArrayResult();
        
        if ($structures) {
            $this->view->attenteValidationMessage = sprintf(
                    "Les composantes suivantes n'ont pas encore validé %s de type '%s' : %s",
                    $this->isReferentiel ? "le référentiel" : "les enseignements",
                    $this->getTypeVolumeHoraire(),
                    '<ul><li>' . implode("</li><li>", Util::extractArrayLeafNodes($structures)) . '</li></ul>'
            );
        }

        return $this;
    }

    /**
     *
     * @throws RuntimeException
     */
    public function supprimerAction()
    {
        $validation = $this->context()->mandatory()->validationFromRoute(); /* @var $validation \Application\Entity\Db\Validation */

        if (! $this->isAllowed($validation, 'delete')) {
            throw new MessageException("Suppression de la validation interdite.");
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
    protected function getFormDossier(\Application\Entity\Db\Validation $validation)
    {
        if (null === $this->formValider) {
            $this->formValider = new DossierValidation();
        }
        
        $this->formValider->init();
        
        if ($validation->getId()) {
            $this->formValider->get('valide')
                    ->setValue(true)
                    ->setLabel("Décochez pour dévalider les données personnelles");
        }
        
        $this->formValider->bind($this->validation);
        
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
     * @return Intervenant
     */
    protected function getIntervenant()
    {
        if (null === $this->intervenant) {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        return $this->intervenant;
    }

    /**
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;
    
    /**
     * @return TypeVolumeHoraire
     */
    protected function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $code = $this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU);
            $this->typeVolumeHoraire = $this->getServiceTypeVolumehoraire()->getByCode($code);
        }
        
        return $this->typeVolumeHoraire;
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