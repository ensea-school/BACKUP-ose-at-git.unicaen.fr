<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of ServiceAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ServiceAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


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
            ) { // dans ce cas ce n'est pas le WF qui agit mais on voit la validation dès qu'on a des services directement...
                // car on peut très bien avoir à visualiser cette page sans pour autant avoir de services à soi à valider!!
                return $this->assertHasServices($intervenant, $role->getStructure());
            } else {
                if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                    return false;
                }
            }
        }

        if ($intervenant && isset($page['route'])) {
            switch ($page['route']) {
                case 'intervenant/validation/service/prevu':
                    return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_PREVU_VISUALISATION);
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
                        return $this->assertServiceVisualisation($role, $entity);
                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                        return $this->assertServiceEdition($role, $entity);
                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertServiceExterieur($role, $entity);
                    case Privileges::ENSEIGNEMENT_VALIDATION:
                        return $this->assertServiceValidation($role, $entity);
                }
            break;
            case $entity instanceof VolumeHoraire:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_VALIDATION:
                        return $this->assertVolumeHoraireValidation($role, $entity);
                }
            break;
            case $entity instanceof ServiceReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                        return $this->assertServiceReferentielVisualisation($role, $entity);
                    case Privileges::REFERENTIEL_PREVU_EDITION:
                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertServiceReferentielEdition($role, $entity);
                    case Privileges::REFERENTIEL_VALIDATION:
                        return $this->assertServiceReferentielValidation($role, $entity);
                }
            break;
            case $entity instanceof VolumeHoraireReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_VALIDATION:
                        return $this->assertVolumeHoraireReferentielValidation($role, $entity);
                }
            break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                    case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                    case Privileges::REFERENTIEL_PREVU_VISUALISATION:
                    case Privileges::REFERENTIEL_PREVU_EDITION:
                    case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                    case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                    case Privileges::REFERENTIEL_REALISE_VISUALISATION:
                    case Privileges::REFERENTIEL_REALISE_EDITION:
                        return $this->assertIntervenant($role, $entity);

                    case Privileges::MOTIF_NON_PAIEMENT_VISUALISATION:
                    case Privileges::MOTIF_NON_PAIEMENT_EDITION:
                        return $this->assertMotifNonPaiement($role, $entity);

                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertIntervenantServiceExterieur($role, $entity);
                }
            break;
            case $entity instanceof Validation:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_VALIDATION:
                    case Privileges::REFERENTIEL_VALIDATION:
                        return $this->assertValidationValidation($role, $entity);
                    case Privileges::ENSEIGNEMENT_DEVALIDATION:
                    case Privileges::REFERENTIEL_DEVALIDATION:
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

        if (!$this->assertIntervenant($role, $intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case 'Application\Controller\Service.validation':
                return $role->hasPrivilege(Privileges::ENSEIGNEMENT_PREVU_VISUALISATION) || $role->hasPrivilege(Privileges::ENSEIGNEMENT_REALISE_VISUALISATION);

            break;
            case 'Application\Controller\Service.resume':
                return $this->assertEnseignements($role);

            break;
            case 'Application\Controller\Service.importAgenda':
                return $this->assertImportAgenda($role);

            break;
            case 'Application\Controller\Intervenant.services':
                return $this->assertPageServices($role, $intervenant);
        }

        return true;
    }



    protected function assertPageServices(Role $role, Intervenant $intervenant = null)
    {
        if (!$intervenant) return true;

        $typeVolumehoraireCode = $this->getMvcEvent()->getRouteMatch()->getParam('type-volume-horaire-code');
        if (!$typeVolumehoraireCode) return true;
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumehoraireCode);

        $wfEtape = $this->getWorkflowEtape($typeVolumeHoraire, 'saisie');

        return $this->asserts([
            $this->assertIntervenant($role, $intervenant),
            $this->assertEtapeAtteignable($wfEtape, $intervenant),
        ]);
    }



    protected function assertEnseignements(Role $role)
    {
        return $this->asserts([
            ($role->hasPrivilege(Privileges::ENSEIGNEMENT_PREVU_VISUALISATION) || $role->hasPrivilege(Privileges::ENSEIGNEMENT_REALISE_VISUALISATION)),
            !$role->getIntervenant(),
        ]);
    }



    protected function assertImportAgenda(Role $role)
    {
        return true;
        //return $this->assertEtapeAtteignable(WfEtape::CODE_SERVICE_SAISIE);
    }



    protected function assertServiceVisualisation(Role $role, Service $service)
    {
        $wfEtape = $this->getWorkflowEtape($service->getTypeVolumeHoraire(), 'saisie');

        return $this->asserts([
            $this->assertIntervenant($role, $service->getIntervenant()),
            $this->assertEtapeAtteignable($wfEtape, $service->getIntervenant()),
        ]);
    }



    protected function assertServiceReferentielVisualisation(Role $role, ServiceReferentiel $serviceReferentiel)
    {
        $wfEtape = $this->getWorkflowEtape($serviceReferentiel->getTypeVolumeHoraire(), 'saisie');

        return $this->asserts([
            $this->assertIntervenant($role, $serviceReferentiel->getIntervenant()),
            $this->assertEtapeAtteignable($wfEtape, $serviceReferentiel->getIntervenant()),
        ]);
    }



    protected function assertServiceEdition(Role $role, Service $service)
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

        $asserts[] = $this->assertIntervenant($role, $service->getIntervenant());

        if ($service->getEtablissement() && $service->getEtablissement() != $this->getServiceContext()->getEtablissement()) {
            $asserts[] = $this->assertServiceExterieur($role, $service);
        }

        $asserts[] = $this->assertCampagneSaisie($role, $service->getTypeVolumeHoraire());
        $asserts[] = $this->assertCloture($role, $service->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertServiceReferentielEdition(Role $role, ServiceReferentiel $serviceReferentiel)
    {
        $asserts = [];
        if (!$role->hasPrivilege(Privileges::REFERENTIEL_SAISIE_TOUTES_COMPOSANTES)) {
            // Si on n'a pas le privilège pour pouvoir du référentiel dans toutes les composantes sans restriction
            if ($structure = $role->getStructure()) {
                $structureAffectation  = $serviceReferentiel->getIntervenant() ? $serviceReferentiel->getIntervenant()->getStructure() : null;
                $structureEnseignement = $serviceReferentiel->getStructure();

                if ($structureAffectation && $structureEnseignement) {
                    // cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
                    $asserts[] = $structure == $structureAffectation || $structure == $structureEnseignement; // le service doit avoir un lien avec la structure
                } elseif ($structureAffectation && !$structureEnseignement) {
                    // cas d'un intervenant prenant des enseignements à l'extérieur
                    $asserts[] = $structure == $structureAffectation;
                } elseif (!$structureAffectation && $structureEnseignement) {
                    // cas d'un intervenant extérieur prenant des enseignements de la composante
                    $asserts[] = $structure == $structureEnseignement;
                }
            }
        }

        $asserts[] = $this->assertIntervenant($role, $serviceReferentiel->getIntervenant());
        $asserts[] = $this->assertCampagneSaisie($role, $serviceReferentiel->getTypeVolumeHoraire());
        $asserts[] = $this->assertCloture($role, $serviceReferentiel->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertHasServices(Intervenant $intervenant, Structure $structure)
    {
        $services = $intervenant->getService()->filter(function (Service $service) use ($structure) {
            if (!$service->getElementPedagogique()) return false;

            return $service->getElementPedagogique()->getStructure() == $structure;
        });

        return $services->count() > 0;
    }



    protected function assertCampagneSaisie(Role $role, TypeVolumeHoraire $typeVolumeHoraire)
    {
        if ($typeVolumeHoraire && $role->getIntervenant()) {
            $campagneSaisie = $this->getServiceCampagneSaisie()->getBy(
                $role->getIntervenant()->getStatut()->getTypeIntervenant(),
                $typeVolumeHoraire
            );
            if (!$campagneSaisie->estOuverte()) return false;
        }

        return true;
    }



    protected function assertCloture(Role $role, Intervenant $intervenant)
    {
        if ($intervenant->getStatut()->getCloture()) {
            $softPassCloture = $role->hasPrivilege(Privileges::CLOTURE_EDITION_SERVICES);
            $hardPassCloture = $role->hasPrivilege(Privileges::CLOTURE_EDITION_SERVICES_AVEC_MEP);

            if ($hardPassCloture) return true; // on n'a toujours le droit

            if ($softPassCloture) { // si on peut éditer toujours alors pas la peine de tester...
                return !$intervenant->hasMiseEnPaiement(); // on n'a le droit s'il n'y a pas de MEP
            } else {
                $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
                if ($cloture && $cloture->getId()) return false; // pas de saisie si c'est clôturé
            }
        }

        return true;
    }



    protected function assertServiceVisualisationValidation(Role $role, Service $service)
    {
        $wfEtape = $this->getWorkflowEtape($service->getTypeVolumeHoraire(), 'validation-enseignement');

        return $this->asserts([
            $this->assertIntervenant($role, $service->getIntervenant()),
            $this->assertEtapeAtteignable($wfEtape, $service->getIntervenant()),
        ]);
    }



    protected function assertVolumeHoraireValidation(Role $role, VolumeHoraire $volumeHoraire)
    {
        $service = $volumeHoraire->getService();

        return $this->assertServiceValidation($role, $service);
    }



    protected function assertServiceValidation(Role $role, Service $service)
    {
        return $this->assertValidation($role, $service->getIntervenant(), $service->getStructure());
    }



    protected function assertVolumeHoraireReferentielValidation(Role $role, VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $serviceReferentiel = $volumeHoraireReferentiel->getServiceReferentiel();

        return $this->assertServiceReferentielValidation($role, $serviceReferentiel);
    }



    protected function assertServiceReferentielValidation(Role $role, ServiceReferentiel $serviceReferentiel)
    {
        return $this->assert($role, $serviceReferentiel->getIntervenant(), $serviceReferentiel->getStructure());
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
            $this->assertIntervenant($role, $intervenant),
            $this->assertStructure($role, $structure),
        ]);
    }



    protected function assertValidationDevalidation(Role $role, Validation $validation)
    {
        return $this->asserts([
            $validation->getId(),
            $this->assertIntervenant($role, $validation->getIntervenant()),
            $this->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertServiceExterieur(Role $role, Service $service)
    {
        return $this->asserts([
            $this->assertIntervenantServiceExterieur($role, $service->getIntervenant()),
        ]);
    }



    protected function assertIntervenantServiceExterieur(Role $role, Intervenant $intervenant)
    {
        return $this->asserts([
            $intervenant->getStatut()->estPermanent(),
            $role->hasPrivilege(Privileges::ENSEIGNEMENT_EXTERIEUR),
        ]);
    }



    protected function assertMotifNonPaiement(Role $role, Intervenant $intervenant)
    {
        // filtrer pour la structure ? ?
        return $this->asserts([
            $intervenant->getStatut()->getMotifNonPaiement(),
            $this->assertIntervenant($role, $intervenant),
        ]);
    }



    protected function assertIntervenant(Role $role, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            if ($ri = $role->getIntervenant()) {
                if ($ri != $intervenant) { // un intervenant ne peut pas voir les services d'un autre
                    return false;
                }
            }
        }

        return true;
    }



    protected function assertStructure(Role $role, ?Structure $structure = null)
    {
        if ($structure) {
            if ($ri = $role->getStructure()) {
                if ($ri != $structure) { // une structure ne peut pas éditer les services d'une autre
                    return false;
                }
            }
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }



    private function getWorkflowEtape(TypeVolumeHoraire $typeVolumeHoraire, $action)
    {
        $key    = $action . '-' . strtolower($typeVolumeHoraire->getCode());
        $etapes = [
            'saisie-prevu'                    => WfEtape::CODE_SERVICE_SAISIE,
            'saisie-realise'                  => WfEtape::CODE_SERVICE_SAISIE_REALISE,
            'validation-enseignement-prevu'   => WfEtape::CODE_SERVICE_VALIDATION,
            'validation-enseignement-realise' => WfEtape::CODE_SERVICE_VALIDATION_REALISE,
            'validation-referentiel-prevu'    => WfEtape::CODE_REFERENTIEL_VALIDATION,
            'validation-referentiel-realise'  => WfEtape::CODE_REFERENTIEL_VALIDATION_REALISE,
        ];

        return $etapes[$key];
    }
}