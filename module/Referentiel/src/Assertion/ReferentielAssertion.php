<?php

namespace Referentiel\Assertion;

use Application\Provider\Privileges;
use Application\Service\LocalContextService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\EntityManager;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Referentiel\Controller\ServiceReferentielController;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Service\Assertion\ServiceAssertionAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Unicaen\Framework\Authorize\Authorize;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of ReferentielAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ReferentielAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ServiceAssertionAwareTrait;

    public function __construct(
        Authorize $authorize,
        private readonly EntityManager $entityManager,
        private readonly LocalContextService $localContext,
    )
    {
        parent::__construct($authorize);
    }



    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof ServiceReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                        return $this->assertServiceReferentielVisualisation($entity);
                    case Privileges::REFERENTIEL_PREVU_EDITION:
                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertServiceReferentielEdition($entity);
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertServiceReferentielValidation($entity);
                }
                break;
            case $entity instanceof VolumeHoraireReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertVolumeHoraireReferentielValidation($entity);
                }
                break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                        return $this->assertIntervenantReferentiel($entity, TypeVolumeHoraire::CODE_PREVU, false);

                    case Privileges::REFERENTIEL_PREVU_EDITION:
                        return $this->assertIntervenantReferentiel($entity, TypeVolumeHoraire::CODE_PREVU, true);

                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                        return $this->assertIntervenantReferentiel($entity, TypeVolumeHoraire::CODE_REALISE, false);

                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertIntervenantReferentiel($entity, TypeVolumeHoraire::CODE_REALISE, true);
                }
                break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertValidationValidation($entity);
                    case Privileges::REFERENTIEL_DEVALIDATION:
                        return $this->assertValidationDevalidation($entity);
                }
                break;
            case $entity instanceof FonctionReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_ADMIN_EDITION:
                        return $this->assertFonctionReferentielEdition($entity);
                }
                break;
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController(string $controller, ?string $action): bool
    {

        /* @var $intervenant Intervenant */
        $intervenant = $this->getParam(Intervenant::class);

        if (!$intervenant){
            $intervenant = $this->localContext->getIntervenant();
        }

        if (!$this->getAssertionService()->assertIntervenant($intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case ServiceReferentielController::class.'.saisie':
            case ServiceReferentielController::class.'.suppression':
                $serviceReferentiel = $this->getParam('id') ? $this->entityManager->find(ServiceReferentiel::class, $this->getParam('id')) : null;
                if (!$serviceReferentiel){
                    $serviceReferentiel = new ServiceReferentiel();
                    $serviceReferentiel->setIntervenant($intervenant);
                }
                $typeVolumeHoraireId = $_GET['type-volume-horaire'] ?? null;
                if ($typeVolumeHoraireId) {
                    $typeVolumeHoraire = $this->entityManager->find(TypeVolumeHoraire::class, $typeVolumeHoraireId);
                    $serviceReferentiel->setTypeVolumeHoraire($typeVolumeHoraire);
                }
                return $this->assertServiceReferentielEdition($serviceReferentiel);

            case ServiceReferentielController::class . '.validationPrevu':
                return $this->authorize->isAllowedPrivilege(Privileges::REFERENTIEL_PREVU_VISUALISATION);

            case ServiceReferentielController::class . '.validationRealise':
                return $this->authorize->isAllowedPrivilege(Privileges::REFERENTIEL_REALISE_VISUALISATION);

            case ServiceReferentielController::class . '.referentiel-prevu':
                return $this->assertPageReferentiel($intervenant, TypeVolumeHoraire::CODE_PREVU);

            case ServiceReferentielController::class . '.referentiel-realise':
                return $this->assertPageReferentiel($intervenant, TypeVolumeHoraire::CODE_REALISE);
        }

        return false;
    }



    protected function assertPageReferentiel(?Intervenant $intervenant, string $typeVolumeHoraireCode): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->getAssertionService()->assertIntervenant($intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeReferentielSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu() || $statut->getReferentielPrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise() || $statut->getReferentielRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertServiceReferentielVisualisation(ServiceReferentiel $serviceReferentiel): bool
    {
        $typeVolumeHoraire = $serviceReferentiel->getTypeVolumeHoraire();
        $intervenant       = $serviceReferentiel->getIntervenant();
        $statut            = $intervenant->getStatut();

        $asserts = [
            $this->getAssertionService()->assertIntervenant($intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeReferentielSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertServiceReferentielEdition(ServiceReferentiel $serviceReferentiel): bool
    {
        $asserts = [];
        if (!$this->authorize->isAllowedPrivilege(Privileges::REFERENTIEL_SAISIE_TOUTES_COMPOSANTES)) {
            // Si on n'a pas le privilège pour pouvoir du référentiel dans toutes les composantes sans restriction
            if ($structure = $this->getServiceContext()->getStructure()) {
                $structureAffectation  = $serviceReferentiel->getIntervenant() ? $serviceReferentiel->getIntervenant()->getStructure() : null;
                $structureEnseignement = $serviceReferentiel->getStructure() ?? $structure;

                if ($structureAffectation && $structureEnseignement) {
                    // cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
                    $asserts[] = $structureAffectation->inStructure($structure) || $structureEnseignement->inStructure($structure); // le service doit avoir un lien avec la structure
                } elseif ($structureAffectation && !$structureEnseignement) {
                    // cas d'un intervenant prenant des enseignements à l'extérieur
                    $asserts[] = $structureAffectation->inStructure($structure);
                } elseif (!$structureAffectation && $structureEnseignement) {
                    // cas d'un intervenant extérieur prenant des enseignements de la composante
                    $asserts[] = $structureEnseignement->inStructure($structure);
                }
            }
        }

        $asserts[] = $this->getAssertionService()->assertIntervenant($serviceReferentiel->getIntervenant());
        if ($serviceReferentiel->getTypeVolumeHoraire()) {
            $asserts[] = $this->getAssertionService()->assertCampagneSaisie($serviceReferentiel->getTypeVolumeHoraire());
        }
        $asserts[] = $this->getAssertionService()->assertCloture($serviceReferentiel->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertHasReferentiel(Intervenant $intervenant, Structure $structure, string $etape): bool
    {
        $services        = $intervenant->getServiceReferentiel();
        $typeIntervenant = $intervenant->getStatut()->getTypeIntervenant();

        $nbServices = 0;
        foreach ($services as $service) {

            if ($service->getHistoDestruction()) continue;

            $nbServices++;
        }

        $reglesValidation = $this->getServiceRegleStructureValidation()->getList();

        foreach ($reglesValidation as $regle) {

            if ($etape == WorkflowEtape::REFERENTIEL_VALIDATION && $regle->getTypeVolumeHoraire()->isPrevu()) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($this->getServiceContext()->getStructure()))) {
                        return true;
                    }
                }
            }

            if ($etape == WorkflowEtape::REFERENTIEL_VALIDATION_REALISE && $regle->getTypeVolumeHoraire()->isRealise()) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($this->getServiceContext()->getStructure()))) {
                        return true;
                    }
                }
            }
        }


        return false;
    }



    protected function assertVolumeHoraireReferentielValidation(VolumeHoraireReferentiel $volumeHoraireReferentiel): bool
    {
        $serviceReferentiel = $volumeHoraireReferentiel->getServiceReferentiel();

        return $this->assertServiceReferentielValidation($serviceReferentiel);
    }



    protected function assertServiceReferentielValidation(ServiceReferentiel $serviceReferentiel): bool
    {
        return $this->assertEntity($serviceReferentiel->getIntervenant(), $serviceReferentiel->getStructure());
    }



    protected function assertValidationValidation(Validation $validation): bool
    {
        return $this->asserts([
                                  !$validation->getId(),
                                  $this->assertValidation($validation->getIntervenant(), $validation->getStructure()),
                              ]);
    }



    protected function assertValidation(Intervenant $intervenant, ?Structure $structure): bool
    {
        return $this->asserts([
                                  $this->getAssertionService()->assertIntervenant($intervenant),
                                  $this->getAssertionService()->assertStructure($structure),
                              ]);
    }



    protected function assertValidationDevalidation(Validation $validation): bool
    {
        return $this->asserts([
                                  $validation->getId(),
                                  $this->getAssertionService()->assertIntervenant($validation->getIntervenant()),
                                  $this->getAssertionService()->assertStructure($validation->getStructure()),
                              ]);
    }



    protected function assertIntervenantReferentiel(
        Intervenant $intervenant,
        string      $typeVolumeHoraireCode,
        bool        $edition = false
    ): bool
    {
        if (!$this->getAssertionService()->assertIntervenant($intervenant)) return false; // si on n'est pas le bon intervenant!!

        $statut = $intervenant->getStatut();
        if (TypeVolumeHoraire::CODE_PREVU == $typeVolumeHoraireCode) {
            if (!$statut->getReferentielPrevu()) return false;
        }
        if (TypeVolumeHoraire::CODE_REALISE == $typeVolumeHoraireCode) {
            if (!$statut->getReferentielRealise()) return false;
        }

        return true;
    }



    protected function assertFonctionReferentielEdition(FonctionReferentiel $fonctionReferentiel): bool
    {
        if ($this->getServiceContext()->getStructure()) {
            if (!$fonctionReferentiel->getStructure()) {
                return false;
            }
            return $fonctionReferentiel->getStructure()->inStructure($this->getServiceContext()->getStructure());
        }

        return true;
    }
}