<?php

namespace Enseignement\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Enseignement\Controller\EnseignementController;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Service\Assertion\ServiceAssertionAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of EnseignementAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EnseignementAssertionOld extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ServiceAssertionAwareTrait;


    protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Service:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                        return $this->assertEnseignementVisualisation($entity);
                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                        return $this->assertEnseignementEdition($entity);
                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertEnseignementExterieur($entity);
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertEnseignementValidation($entity);
                }
                break;
            case $entity instanceof VolumeHoraire:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertVolumeHoraireValidation($entity);
                }
                break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                        return $this->assertIntervenantEnseignement($entity, TypeVolumeHoraire::CODE_PREVU, false);

                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                        return $this->assertIntervenantEnseignement($entity, TypeVolumeHoraire::CODE_PREVU, true);

                    case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                        return $this->assertIntervenantEnseignement($entity, TypeVolumeHoraire::CODE_REALISE, false);

                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                        return $this->assertIntervenantEnseignement($entity, TypeVolumeHoraire::CODE_REALISE, true);

                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertIntervenantEnseignementExterieur($entity);
                }
                break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertValidationValidation($entity);
                    case Privileges::ENSEIGNEMENT_DEVALIDATION:
                        return $this->assertValidationDevalidation($entity);
                }
                break;
        }

        return true;
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);
        /* @var $intervenant Intervenant */

        if (!$this->getAssertionService()->assertIntervenant($intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case EnseignementController::class . '.validationPrevu':
                return true;

            case EnseignementController::class . '.validationRealise':
                return true;

            case EnseignementController::class . '.resume':
                return $this->assertEnseignements();

            case EnseignementController::class . '.importAgenda':
                return $this->assertImportAgenda();

            case EnseignementController::class . '.intervenantSaisiePrevu':
                return $this->assertPageEnseignements($intervenant, TypeVolumeHoraire::CODE_PREVU);

            case EnseignementController::class . '.intervenantSaisieRealise':
                return $this->assertPageEnseignements($intervenant, TypeVolumeHoraire::CODE_REALISE);
        }

        return true;
    }



    protected function assertPageEnseignements(?Intervenant $intervenant, string $typeVolumeHoraireCode): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->getAssertionService()->assertIntervenant($intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeEnseignementSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu() || $statut->getReferentielPrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise() || $statut->getReferentielRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertEnseignements(): bool
    {
        return $this->asserts([
                                  (
                                      $this->authorize->isAllowedPrivilege(Privileges::ENSEIGNEMENT_PREVU_VISUALISATION)
                                      || $this->authorize->isAllowedPrivilege(Privileges::ENSEIGNEMENT_REALISE_VISUALISATION)
                                      || $this->authorize->isAllowedPrivilege(Privileges::REFERENTIEL_PREVU_VISUALISATION)
                                      || $this->authorize->isAllowedPrivilege(Privileges::REFERENTIEL_REALISE_VISUALISATION)
                                  ),
                                  !$this->getServiceContext()->getIntervenant(),
                              ]);
    }



    protected function assertImportAgenda(): bool
    {
        return true;
    }



    protected function assertEnseignementVisualisation(Service $service): bool
    {
        $typeVolumeHoraire = $service->getTypeVolumeHoraire();
        $intervenant       = $service->getIntervenant();
        $statut            = $intervenant->getStatut();

        $asserts = [
            $this->getAssertionService()->assertIntervenant($intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeEnseignementSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertEnseignementEdition(Service $service): bool
    {
        $structure = $this->getServiceContext()->getStructure();

        $asserts = [];

        if ($structure) {
            $structureAffectation  = $service->getIntervenant() ? $service->getIntervenant()->getStructure() : null;
            $structureEnseignement = $service->getElementPedagogique() ? $service->getElementPedagogique()->getStructure() : null;

            if ($structureAffectation && $structureEnseignement) {
                // cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
                $asserts[] = $structureAffectation->inStructure($structure) || $structureEnseignement->inStructure($structure); // le service doit avoir un lien avec la structure
            } elseif ($structureAffectation && !$structureEnseignement) {
                // cas d'un intervenant prenant des enseignements à l'extérieur
                //$asserts[] = $structure == $structureAffectation;
            }
        }

        $asserts[] = $this->getAssertionService()->assertIntervenant($service->getIntervenant());

        if ($service->getEtablissement() && $service->getEtablissement() != $this->getServiceContext()->getEtablissement()) {
            $asserts[] = $this->assertEnseignementExterieur($service);
        }

        $asserts[] = $this->getAssertionService()->assertCampagneSaisie($service->getTypeVolumeHoraire());
        $asserts[] = $this->getAssertionService()->assertCloture($service->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertHasEnseignements(Intervenant $intervenant, Structure $structure, string $etape): bool
    {
        $typeIntervenant = $intervenant->getStatut()->getTypeIntervenant();
        switch ($etape) {
            case WorkflowEtape::ENSEIGNEMENT_VALIDATION:
                $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
                break;
            case WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE:
                $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
                break;
            default:
                throw new \Exception('Etape de workflow non gérée');
        }

        $reglesValidation = $this->getServiceRegleStructureValidation()->getList();

        foreach ($reglesValidation as $regle) {

            if ($regle->getTypeVolumeHoraire() == $typeVolumeHoraire) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $this->hasHeures($intervenant, $typeVolumeHoraire)) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($this->getServiceContext()->getStructure()))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }



    protected function hasHeures(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire): bool
    {
        $sql = "
        SELECT
          sum(vh.heures)
        FROM
          volume_horaire vh
          JOIN service s ON s.id = vh.service_id
        WHERE
          s.histo_destruction IS NULL
          AND vh.histo_destruction IS NULL
          AND s.intervenant_id = :intervenant
          AND vh.type_volume_horaire_id = :typeVolumeHoraire
        ";

        $conn = $this->getServiceContext()->getEntityManager()->getConnection();

        $res = $conn->fetchOne($sql, ['intervenant' => $intervenant->getId(), 'typeVolumeHoraire' => $typeVolumeHoraire->getId()]);

        return (float)$res > 0;
    }



    protected function assertVolumeHoraireValidation(VolumeHoraire $volumeHoraire): bool
    {
        $service = $volumeHoraire->getService();

        return $this->assertEnseignementValidation($service);
    }



    protected function assertEnseignementValidation(Service $service): bool
    {
        return $this->assertValidation($service->getIntervenant(), $service->getStructure());
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
                                  //$this->assertValidation($validation->getIntervenant(), $validation->getStructure()),
                                  $this->getAssertionService()->assertIntervenant($validation->getIntervenant()),
                                  $this->getAssertionService()->assertStructure($validation->getStructure()),
                              ]);
    }



    protected function assertEnseignementExterieur(Service $service): bool
    {
        return $this->asserts([
                                  $this->assertIntervenantEnseignementExterieur($service->getIntervenant()),
                              ]);
    }



    protected function assertIntervenantEnseignementExterieur(Intervenant $intervenant): bool
    {

        return $this->asserts([
                                  $intervenant->getStatut()->getServiceExterieur(),
                                  $this->authorize->isAllowedPrivilege(Privileges::ENSEIGNEMENT_EXTERIEUR),
                              ]);
    }



    protected function assertIntervenantEnseignement(
        Intervenant $intervenant,
        string      $typeVolumeHoraireCode,
        bool        $edition = false
    ): bool
    {
        if (!$this->getAssertionService()->assertIntervenant($intervenant)) return false; // si on n'est pas le bon intervenant!!

        $statut = $intervenant->getStatut();
        if (TypeVolumeHoraire::CODE_PREVU == $typeVolumeHoraireCode) {
            if (!$statut->getServicePrevu()) return false;
        }
        if (TypeVolumeHoraire::CODE_REALISE == $typeVolumeHoraireCode) {
            if (!$statut->getServiceRealise()) return false;
        }

        return true;
    }

}