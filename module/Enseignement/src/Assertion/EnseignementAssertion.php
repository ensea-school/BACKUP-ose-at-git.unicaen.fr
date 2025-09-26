<?php

namespace Enseignement\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Enseignement\Controller\EnseignementController;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Service\Assertion\ServiceAssertionAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Utilisateur\Acl\Role;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of EnseignementAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EnseignementAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use RegleStructureValidationServiceAwareTrait;
    use ServiceAssertionAwareTrait;


    /* ---- Routage général ---- */
    public function __invoke (array $page): bool // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertPage (array $page): bool
    {
        $role = $this->getRole();
        /* @var $role Role */

        $intervenant = null;
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (
                $intervenant
                && $role
                && $role->getStructure()
                && (WorkflowEtape::ENSEIGNEMENT_VALIDATION == $etape || WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE == $etape)
            ) { // dans ce cas ce n'est pas le WF qui agit, mais on voit la validation dès qu'on a des services directement,
                // car on peut très bien avoir à visualiser cette page sans pour autant avoir de services à soi à valider!!
                return $this->assertHasEnseignements($intervenant, $role->getStructure(), $etape, $role);
            } else {
                if (!$this->getAssertionService()->assertEtapeAtteignable($etape, $intervenant)) {
                    return false;
                }
            }
        }

        if ($intervenant && isset($page['route'])) {
            switch ($page['route']) {
                case 'intervenant/validation/enseignement/prevu':
                    return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_PREVU_VISUALISATION);
                case 'intervenant/validation/enseignement/realise':
                    return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_REALISE_VISUALISATION);
                case 'intervenant/enseignement-prevu':
                    return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
                break;
                case 'intervenant/enseignement-realise':
                    return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
                break;
            }
        }

        return true;
    }



    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity (ResourceInterface $entity, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof Service:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                        return $this->assertEnseignementVisualisation($role, $entity);
                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                        return $this->assertEnseignementEdition($role, $entity);
                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertEnseignementExterieur($role, $entity);
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertEnseignementValidation($role, $entity);
                }
            break;
            case $entity instanceof VolumeHoraire:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertVolumeHoraireValidation($role, $entity);
                }
            break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                        return $this->assertIntervenantEnseignement($role, $entity, TypeVolumeHoraire::CODE_PREVU, false);

                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                        return $this->assertIntervenantEnseignement($role, $entity, TypeVolumeHoraire::CODE_PREVU, true);

                    case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                        return $this->assertIntervenantEnseignement($role, $entity, TypeVolumeHoraire::CODE_REALISE, false);

                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                        return $this->assertIntervenantEnseignement($role, $entity, TypeVolumeHoraire::CODE_REALISE, true);

                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertIntervenantEnseignementExterieur($role, $entity);
                }
            break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
                    case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
                        return $this->assertValidationValidation($role, $entity);
                    case Privileges::ENSEIGNEMENT_DEVALIDATION:
                        return $this->assertValidationDevalidation($role, $entity);
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
    protected function assertController ($controller, $action = null, $privilege = null): bool
    {
        $role        = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        if (!$this->getAssertionService()->assertIntervenant($role, $intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case EnseignementController::class . '.validation':
                return $role->hasPrivilege(Privileges::ENSEIGNEMENT_PREVU_VISUALISATION) || $role->hasPrivilege(Privileges::ENSEIGNEMENT_REALISE_VISUALISATION);
            break;
            case EnseignementController::class . '.resume':
                return $this->assertEnseignements($role);
            break;
            case EnseignementController::class . '.importAgenda':
                return $this->assertImportAgenda($role);
            break;
            case EnseignementController::class . '.intervenant-saisie-prevu':
                return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
            break;
            case EnseignementController::class . '.intervenant-saisie-realise':
                return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
            break;
        }

        return true;
    }



    protected function assertPageEnseignements (Role $role, ?Intervenant $intervenant, string $typeVolumeHoraireCode): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
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



    protected function assertEnseignements (Role $role): bool
    {
        return $this->asserts([
            (
                $role->hasPrivilege(Privileges::ENSEIGNEMENT_PREVU_VISUALISATION)
                || $role->hasPrivilege(Privileges::ENSEIGNEMENT_REALISE_VISUALISATION)
                || $role->hasPrivilege(Privileges::REFERENTIEL_PREVU_VISUALISATION)
                || $role->hasPrivilege(Privileges::REFERENTIEL_REALISE_VISUALISATION)
            ),
            !$role->getIntervenant(),
        ]);
    }



    protected function assertImportAgenda (Role $role): bool
    {
        return true;
    }



    protected function assertEnseignementVisualisation (Role $role, Service $service): bool
    {
        $typeVolumeHoraire = $service->getTypeVolumeHoraire();
        $intervenant       = $service->getIntervenant();
        $statut            = $intervenant->getStatut();

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
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



    protected function assertEnseignementEdition (Role $role, Service $service): bool
    {
        $structure = $role->getStructure();

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

        $asserts[] = $this->getAssertionService()->assertIntervenant($role, $service->getIntervenant());

        if ($service->getEtablissement() && $service->getEtablissement() != $this->getServiceContext()->getEtablissement()) {
            $asserts[] = $this->assertEnseignementExterieur($role, $service);
        }

        $asserts[] = $this->getAssertionService()->assertCampagneSaisie($role, $service->getTypeVolumeHoraire());
        $asserts[] = $this->getAssertionService()->assertCloture($role, $service->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertHasEnseignements (Intervenant $intervenant, Structure $structure, string $etape, Role $role): bool
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
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($role->getStructure()))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }



    protected function hasHeures (Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire): bool
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



    protected function assertVolumeHoraireValidation (Role $role, VolumeHoraire $volumeHoraire): bool
    {
        $service = $volumeHoraire->getService();

        return $this->assertEnseignementValidation($role, $service);
    }



    protected function assertEnseignementValidation (Role $role, Service $service): bool
    {
        return $this->assertValidation($role, $service->getIntervenant(), $service->getStructure());
    }



    protected function assertValidationValidation (Role $role, Validation $validation): bool
    {
        return $this->asserts([
            !$validation->getId(),
            $this->assertValidation($role, $validation->getIntervenant(), $validation->getStructure()),
        ]);
    }



    protected function assertValidation (Role $role, Intervenant $intervenant, ?Structure $structure): bool
    {
        return $this->asserts([
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
            $this->getAssertionService()->assertStructure($role, $structure),
        ]);
    }



    protected function assertValidationDevalidation (Role $role, Validation $validation): bool
    {
        return $this->asserts([
            $validation->getId(),
            //$this->assertValidation($role, $validation->getIntervenant(), $validation->getStructure()),
            $this->getAssertionService()->assertIntervenant($role, $validation->getIntervenant()),
            $this->getAssertionService()->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertEnseignementExterieur (Role $role, Service $service): bool
    {
        return $this->asserts([
            $this->assertIntervenantEnseignementExterieur($role, $service->getIntervenant()),
        ]);
    }



    protected function assertIntervenantEnseignementExterieur (Role $role, Intervenant $intervenant): bool
    {

        return $this->asserts([
            $intervenant->getStatut()->getServiceExterieur(),
            $role->hasPrivilege(Privileges::ENSEIGNEMENT_EXTERIEUR),
        ]);
    }



    protected function assertIntervenantEnseignement (
        Role $role,
        Intervenant $intervenant,
        string $typeVolumeHoraireCode,
        bool $edition = false
    ): bool
    {
        if (!$this->getAssertionService()->assertIntervenant($role, $intervenant)) return false; // si on n'est pas le bon intervenant!!

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