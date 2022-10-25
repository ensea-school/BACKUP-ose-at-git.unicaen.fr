<?php

namespace Enseignement\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Enseignement\Controller\EnseignementController;
use Enseignement\Entity\Db\Service;
use Referentiel\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Service\Assertion\ServiceAssertionAwareTrait;
use Service\Controller\ServiceController;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Enseignement\Entity\Db\VolumeHoraire;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


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
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertPage(array $page)
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
                && (WfEtape::CODE_SERVICE_VALIDATION == $etape || WfEtape::CODE_SERVICE_VALIDATION_REALISE == $etape)
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
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
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
    protected function assertController($controller, $action = null, $privilege = null)
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



    protected function assertPageEnseignements(Role $role, Intervenant $intervenant = null, string $typeVolumeHoraireCode)
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeServiceSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu() || $statut->getReferentielPrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise() || $statut->getReferentielRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertEnseignements(Role $role)
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



    protected function assertImportAgenda(Role $role)
    {
        return true;
        //return $this->getAssertionService()->assertEtapeAtteignable(WfEtape::CODE_SERVICE_SAISIE);
    }



    protected function assertEnseignementVisualisation(Role $role, Service $service)
    {
        $typeVolumeHoraire = $service->getTypeVolumeHoraire();
        $intervenant       = $service->getIntervenant();
        $statut            = $intervenant->getStatut();

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
            $this->getAssertionService()->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeServiceSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertEnseignementEdition(Role $role, Service $service)
    {
        $structure = $role->getStructure();

        $asserts = [];

        if ($structure) {
            $structureAffectation  = $service->getIntervenant() ? $service->getIntervenant()->getStructure() : null;
            $structureEnseignement = $service->getElementPedagogique() ? $service->getElementPedagogique()->getStructure() : null;

            if ($structureAffectation && $structureEnseignement) {
                // cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
                $asserts[] = $structure == $structureAffectation || $structure == $structureEnseignement; // le service doit avoir un lien avec la structure
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



    protected function assertHasEnseignements(Intervenant $intervenant, Structure $structure, string $etape, Role $role)
    {
        $services        = $intervenant->getService();
        $typeIntervenant = $intervenant->getStatut()->getTypeIntervenant();

        $nbServices = 0;
        foreach ($services as $service) {

            if ($service->getHistoDestruction()) continue;
            if (!$service->getElementPedagogique()) continue;

            $nbServices++;
        }

        $reglesValidation = $this->getServiceRegleStructureValidation()->getList();

        foreach ($reglesValidation as $regle) {

            if ($etape == WfEtape::CODE_SERVICE_VALIDATION && $regle->getTypeVolumeHoraire()->getCode() == 'PREVU') {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure() == $role->getStructure())) {
                        return true;
                    }
                }
            }

            if ($etape == WfEtape::CODE_SERVICE_VALIDATION_REALISE && $regle->getTypeVolumeHoraire()->getCode() == 'REALISE') {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure() == $role->getStructure())) {
                        return true;
                    }
                }
            }
        }

        return false;
    }



    protected function assertVolumeHoraireValidation(Role $role, VolumeHoraire $volumeHoraire)
    {
        $service = $volumeHoraire->getService();

        return $this->assertEnseignementValidation($role, $service);
    }



    protected function assertEnseignementValidation(Role $role, Service $service)
    {
        return $this->assertValidation($role, $service->getIntervenant(), $service->getStructure());
    }



    protected function assertValidationValidation(Role $role, Validation $validation)
    {
        return $this->asserts([
            !$validation->getId(),
            $this->assertValidation($role, $validation->getIntervenant(), $validation->getStructure()),
        ]);
    }



    protected function assertValidation(Role $role, Intervenant $intervenant, ?Structure $structure)
    {
        return $this->asserts([
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
            $this->getAssertionService()->assertStructure($role, $structure),
        ]);
    }



    protected function assertValidationDevalidation(Role $role, Validation $validation)
    {
        return $this->asserts([
            $validation->getId(),
            //$this->assertValidation($role, $validation->getIntervenant(), $validation->getStructure()),
            $this->getAssertionService()->assertIntervenant($role, $validation->getIntervenant()),
            $this->getAssertionService()->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertEnseignementExterieur(Role $role, Service $service)
    {
        return $this->asserts([
            $this->assertIntervenantEnseignementExterieur($role, $service->getIntervenant()),
        ]);
    }



    protected function assertIntervenantEnseignementExterieur(Role $role, Intervenant $intervenant)
    {
        return $this->asserts([
            $intervenant->getStatut()->estPermanent(),
            $role->hasPrivilege(Privileges::ENSEIGNEMENT_EXTERIEUR),
        ]);
    }



    protected function assertIntervenantEnseignement(
        Role $role,
        Intervenant $intervenant,
        string $typeVolumeHoraireCode,
        bool $edition = false
    )
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