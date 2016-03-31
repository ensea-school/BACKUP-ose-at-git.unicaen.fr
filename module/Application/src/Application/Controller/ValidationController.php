<?php

namespace Application\Controller;

use Application\Acl\IntervenantRole;
use Application\Entity\Db\Intervenant;
use Application\Exception\DbException;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use UnicaenApp\Util;
use RuntimeException;
use LogicException;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\ServiceValidation;
use Application\Form\Intervenant\ReferentielValidation;
use Application\Rule\Validation\ValidationEnsRefAbstractRule;
use Doctrine\ORM\Query\Expr\Join;
use Zend\View\Model\ViewModel;

/**
 * Description of ValidationController
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationController extends AbstractController
{
    use ContextAwareTrait;
    use StructureAwareTrait;
    use ValidationAwareTrait;
    use ServiceAwareTrait;
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeValidationAwareTrait;

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
     * @var \Application\Entity\Db\Intervenant
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
     * @var ViewModel
     */
    private $view;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\Validation::class,
            \Application\Entity\Db\Service::class,
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\ServiceReferentiel::class,
            \Application\Entity\Db\VolumeHoraireReferentiel::class,
        ]);
    }



    /**
     * Validation des enseignements prévisionnels par la composante d'affectation de l'intervenant.
     *
     * @return ViewModel
     * @throws \LogicException
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
        $this->title         = $this->getPageTitleForService();
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

        $this->em()->clear(\Application\Entity\Db\Service::class); // INDISPENSABLE entre 2 requêtes sur Service !

        // recherche des enseignements de l'intervenant non encore validés
        $servicesNonValides = $serviceService->fetchServicesDisposPourValidation($typeVolumeHoraire, $this->getIntervenant(), $structuresEns);
        $serviceService->setTypeVolumehoraire($servicesNonValides, $typeVolumeHoraire);

        if (!count($servicesNonValides)) {
            $this->validation = current($this->validations);
            if ($role->getIntervenant()) {
                $message = sprintf(
                    "Tous vos enseignements %s ont été validés.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnels" : "réalisés"
                );
            } else {
                $message = sprintf(
                    "Aucun enseignement %s n'est en attente de validation%s.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé",
                    $structuresEns ? " par la structure " . implode(" ou ", array_keys($structuresEns)) : null
                );
            }
            $messages[] = $message;
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

            $messages[] = sprintf("Enseignements %s en attente de validation par la structure '%s'.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnels" : "réalisés",
                    $structureValidation);
        }

        $this->view = new ViewModel([
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
                    $e        = DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }

                return $this->redirect()->toRoute(null, [], [], true);
            }
        }

        $this->injectAttenteValidationMessage();
        
        return $this->view;
    }

    private function getPageTitleForService()
    {
        $typeVolumeHoraire = $this->getTypeVolumeHoraire();
        $title             = "Validation des enseignements";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnels";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisés";
        }

        return $title;
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

            $this->em()->clear(\Application\Entity\Db\Service::class); // INDISPENSABLE entre 2 requêtes concernant les services !

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
     * @return ViewModel
     * @throws RuntimeException
     */
    public function referentielAction()
    {
        $this->isReferentiel    = true;
        $serviceReferentiel     = $this->getServiceServiceReferentiel();
        $serviceValidation      = $this->getServiceValidation();
        $role                   = $this->getServiceContext()->getSelectedIdentityRole();
        $typeVolumeHoraire      = $this->getServiceTypeVolumehoraire()->getByCode($this->params()->fromRoute('type-volume-horaire-code', TypeVolumeHoraire::CODE_PREVU));
        $this->formValider      = $this->getFormValidationService()->setIntervenant($this->getIntervenant())->init();
        $this->title            = $this->getPageTitleForReferentiel();//"Validation du référentiel de type '$typeVolumeHoraire' <small>{$this->getIntervenant()}</small>";
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
            throw new \LogicException("Validation du référentiel impossible.", null, $le);
        }

        $structureRef        = $rule->getStructuresIntervention();
        $structureValidation = $rule->getStructureValidation();

        // collecte des validations et des référentiels associés
        $this->collectValidationsReferentiels($typeValidation, $typeVolumeHoraire, $structureRef, $structureValidation);

        $this->em()->clear(\Application\Entity\Db\ServiceReferentiel::class); // INDISPENSABLE entre 2 requêtes sur ServiceReferentiel !

        // recherche des référentiels de l'intervenant non encore validés
        $qb = $serviceReferentiel->finderReferentielsNonValides($typeVolumeHoraire, $this->getIntervenant(), $structureRef);
        $referentielsNonValides = $qb->getQuery()->getResult();

        if (!count($referentielsNonValides)) {
            $this->validation = current($this->validations);
            if ($role instanceof IntervenantRole) {
                $message = sprintf(
                    "Tout votre référentiel % n'a pas encore été validé.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnels" : "réalisés"
                );
            } else {
                $message = sprintf(
                    "Aucun référentiel %s%s à valider n'a été trouvé.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé",
                    $structureRef ? " concernant la structure '$structureRef'" : null
                );
            }
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

            $messages[] = sprintf("Référentiel %s en attente de validation par la structure '%s'.",
                    $typeVolumeHoraire->isPrevu() ? "prévisionnel" : "réalisé",
                    $structureValidation);
        }

        $this->view = new ViewModel([
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

    private function getPageTitleForReferentiel()
    {
        $typeVolumeHoraire = $this->getTypeVolumeHoraire();
        $title             = "Validation du référentiel";

        if ($typeVolumeHoraire->isPrevu()) {
            $title .= " prévisionnel";
        } elseif ($typeVolumeHoraire->isRealise()) {
            $title .= " réalisé";
        }

        return $title;
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

        $serviceReferentiel = $this->getServiceServiceReferentiel();
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

            $this->em()->clear(\Application\Entity\Db\ServiceReferentiel::class); // INDISPENSABLE entre 2 requêtes concernant le référentiel !

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
                \Application\Entity\Db\VIndicAttenteValidationServiceRef::class :
                \Application\Entity\Db\VIndicAttenteValidationService::class;
        
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
            throw new \LogicException("Suppression de la validation interdite.");
        }

        $title     = "Suppression de la validation";
        $form = $this->makeFormSupprimer(function()use($validation){
            $this->getServiceValidation()->supprimer($validation);
            $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
        });

        return compact('entity', 'context', 'title', 'form');
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

}