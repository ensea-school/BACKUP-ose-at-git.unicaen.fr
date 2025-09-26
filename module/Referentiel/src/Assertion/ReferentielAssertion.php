<?php

namespace Referentiel\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Service\Authorize;
use Framework\Application\Application;
use Framework\Authorize\AbstractAssertion;
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
use Utilisateur\Acl\Role;
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


    /* ---- Routage général ---- */
    public function __invoke(array $page): bool // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }


    protected function assertPage(array $page): bool
    {
        $role = $this->getRole();
        /* @var $role Role */

        $intervenant = null;
        if (isset($page['workflow-etape-code'])) {
            $etape = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (
                $intervenant
                && $role
                && $role->getStructure()
                && (WorkflowEtape::REFERENTIEL_VALIDATION == $etape || WorkflowEtape::REFERENTIEL_VALIDATION_REALISE == $etape)
            ) { // dans ce cas ce n'est pas le WF qui agit mais on voit la validation dès qu'on a des services directement...
                // car on peut très bien avoir à visualiser cette page sans pour autant avoir de services à soi à valider!!
                return $this->assertHasReferentiel($intervenant, $role->getStructure(), $etape, $role);
            } else {
                if (!$this->getAssertionService()->assertEtapeAtteignable($etape, $intervenant)) {
                    return false;
                }
            }
        }

        if ($intervenant && isset($page['route'])) {
            switch ($page['route']) {
                case 'intervenant/validation/referentiel/prevu':
                    return $this->assertEntity($intervenant, Privileges::REFERENTIEL_PREVU_VISUALISATION);
                    break;
                case 'intervenant/validation/referentiel/realise':
                    return $this->assertEntity($intervenant, Privileges::REFERENTIEL_REALISE_VISUALISATION);
                    break;
                case 'intervenant/referentiel-prevu':
                    return $this->assertPageReferentiel($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
                    break;
                case 'intervenant/referentiel-realise':
                    return $this->assertPageReferentiel($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
                    break;
            }
        }

        return true;
    }


    /**
     * @param ResourceInterface $entity
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof ServiceReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                        return $this->assertServiceReferentielVisualisation($role, $entity);
                    case Privileges::REFERENTIEL_PREVU_EDITION:
                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertServiceReferentielEdition($role, $entity);
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertServiceReferentielValidation($role, $entity);
                }
                break;
            case $entity instanceof VolumeHoraireReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertVolumeHoraireReferentielValidation($role, $entity);
                }
                break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                        return $this->assertIntervenantReferentiel($role, $entity, TypeVolumeHoraire::CODE_PREVU, false);

                    case Privileges::REFERENTIEL_PREVU_EDITION:
                        return $this->assertIntervenantReferentiel($role, $entity, TypeVolumeHoraire::CODE_PREVU, true);

                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                        return $this->assertIntervenantReferentiel($role, $entity, TypeVolumeHoraire::CODE_REALISE, false);

                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertIntervenantReferentiel($role, $entity, TypeVolumeHoraire::CODE_REALISE, true);
                }
                break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VALIDATION:
                    case Privileges::REFERENTIEL_REALISE_VALIDATION:
                        return $this->assertValidationValidation($role, $entity);
                    case Privileges::REFERENTIEL_DEVALIDATION:
                        return $this->assertValidationDevalidation($role, $entity);
                }
                break;
            case $entity instanceof FonctionReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_ADMIN_EDITION:
                        return $this->assertFonctionReferentielEdition($role, $entity);
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
    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $role = $this->getRole();
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        if (!$this->getAssertionService()->assertIntervenant($role, $intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case ServiceReferentielController::class . '.validation':
                return $role->hasPrivilege(Privileges::REFERENTIEL_PREVU_VISUALISATION) || $role->hasPrivilege(Privileges::REFERENTIEL_REALISE_VISUALISATION);

                break;
            case ServiceReferentielController::class . '.referentiel-prevu':
                return $this->assertPageReferentiel($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
                break;
            case ServiceReferentielController::class . '.referentiel-realise':
                return $this->assertPageReferentiel($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
                break;
        }

        return true;
    }


    protected function assertPageReferentiel(Role $role, ?Intervenant $intervenant, string $typeVolumeHoraireCode): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
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


    protected function assertServiceReferentielVisualisation(Role $role, ServiceReferentiel $serviceReferentiel): bool
    {
        $typeVolumeHoraire = $serviceReferentiel->getTypeVolumeHoraire();
        $intervenant = $serviceReferentiel->getIntervenant();
        $statut = $intervenant->getStatut();

        $asserts = [
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
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


    protected function assertServiceReferentielEdition(Role $role, ServiceReferentiel $serviceReferentiel): bool
    {
        $asserts = [];
        if (!$role->hasPrivilege(Privileges::REFERENTIEL_SAISIE_TOUTES_COMPOSANTES)) {
            // Si on n'a pas le privilège pour pouvoir du référentiel dans toutes les composantes sans restriction
            if ($structure = $role->getStructure()) {
                $structureAffectation = $serviceReferentiel->getIntervenant() ? $serviceReferentiel->getIntervenant()->getStructure() : null;
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

        $asserts[] = $this->getAssertionService()->assertIntervenant($role, $serviceReferentiel->getIntervenant());
        $asserts[] = $this->getAssertionService()->assertCampagneSaisie($role, $serviceReferentiel->getTypeVolumeHoraire());
        $asserts[] = $this->getAssertionService()->assertCloture($role, $serviceReferentiel->getIntervenant());

        return $this->asserts($asserts);
    }


    protected function assertHasReferentiel(Intervenant $intervenant, Structure $structure, string $etape, Role $role): bool
    {
        $services = $intervenant->getServiceReferentiel();
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
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($role->getStructure()))) {
                        return true;
                    }
                }
            }

            if ($etape == WorkflowEtape::REFERENTIEL_VALIDATION_REALISE && $regle->getTypeVolumeHoraire()->isRealise()) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure()?->inStructure($role->getStructure()))) {
                        return true;
                    }
                }
            }
        }


        return false;
    }


    protected function assertVolumeHoraireReferentielValidation(Role $role, VolumeHoraireReferentiel $volumeHoraireReferentiel): bool
    {
        $serviceReferentiel = $volumeHoraireReferentiel->getServiceReferentiel();

        return $this->assertServiceReferentielValidation($role, $serviceReferentiel);
    }


    protected function assertServiceReferentielValidation(Role $role, ServiceReferentiel $serviceReferentiel): bool
    {
        // Hack à supprimer une fois le nouveau moteur authorize ok
        $acl = Application::getInstance()->container(Authorize::class)->getAcl();

        return $this->assert($acl, $this->$role, $serviceReferentiel->getIntervenant(), $serviceReferentiel->getStructure());
    }


    protected function assertValidationValidation(Role $role, Validation $validation): bool
    {
        return $this->asserts([
            !$validation->getId(),
            $this->assertValidation($role, $validation->getIntervenant(), $validation->getStructure()),
        ]);
    }


    protected function assertValidation(Role $role, Intervenant $intervenant, ?Structure $structure): bool
    {
        return $this->asserts([
            $this->getAssertionService()->assertIntervenant($role, $intervenant),
            $this->getAssertionService()->assertStructure($role, $structure),
        ]);
    }


    protected function assertValidationDevalidation(Role $role, Validation $validation): bool
    {
        return $this->asserts([
            $validation->getId(),
            $this->getAssertionService()->assertIntervenant($role, $validation->getIntervenant()),
            $this->getAssertionService()->assertStructure($role, $validation->getStructure()),
        ]);
    }


    protected function assertIntervenantReferentiel(
        Role        $role,
        Intervenant $intervenant,
        string      $typeVolumeHoraireCode,
        bool        $edition = false
    ): bool
    {
        if (!$this->getAssertionService()->assertIntervenant($role, $intervenant)) return false; // si on n'est pas le bon intervenant!!

        $statut = $intervenant->getStatut();
        if (TypeVolumeHoraire::CODE_PREVU == $typeVolumeHoraireCode) {
            if (!$statut->getReferentielPrevu()) return false;
        }
        if (TypeVolumeHoraire::CODE_REALISE == $typeVolumeHoraireCode) {
            if (!$statut->getReferentielRealise()) return false;
        }

        return true;
    }



    protected function assertFonctionReferentielEdition(Role $role, FonctionReferentiel $fonctionReferentiel): bool
    {
        if ($role->getStructure()){
            if (!$fonctionReferentiel->getStructure()){
                return false;
            }
            return $fonctionReferentiel->getStructure()->inStructure($role->getStructure());
        }

        return true;
    }
}