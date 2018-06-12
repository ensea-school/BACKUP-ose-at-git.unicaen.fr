<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


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
            ){ // dans ce cas ce n'est pas le WF qui agit mais on voit la validation dès qu'on a des services directement...
               // car on peut très bien avoir à visualiser cette page sans pour autant avoir de services à soi à valider!!
                return $this->assertHasServices( $intervenant, $role->getStructure() );
            }else if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        if ($intervenant && isset($page['route'])) {
            switch ($page['route']) {
                case 'intervenant/validation/service/prevu':
                    return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_VISUALISATION);
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
                    case Privileges::ENSEIGNEMENT_VISUALISATION:
                        return $this->assertServiceVisualisation($role, $entity);
                    case Privileges::ENSEIGNEMENT_EDITION:
                        return $this->assertServiceEdition($role, $entity);
                    case Privileges::ENSEIGNEMENT_EXTERIEUR:
                        return $this->assertServiceExterieur($role, $entity);
                }
            break;
            case $entity instanceof ServiceReferentiel:
                switch ($privilege) {
                    case Privileges::REFERENTIEL_VISUALISATION:
                        return $this->assertServiceReferentielVisualisation($role, $entity);
                    case Privileges::REFERENTIEL_EDITION:
                        return $this->assertServiceReferentielEdition($role, $entity);
                }
            break;
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::ENSEIGNEMENT_VISUALISATION:
                    case Privileges::ENSEIGNEMENT_EDITION:
                    case Privileges::REFERENTIEL_VISUALISATION:
                    case Privileges::REFERENTIEL_EDITION:
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
                        return $this->assertServiceValidation($role, $entity);
                    case Privileges::ENSEIGNEMENT_DEVALIDATION:
                    case Privileges::REFERENTIEL_DEVALIDATION:
                        return $this->assertServiceDevalidation($role, $entity);
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
                return $role->hasPrivilege(Privileges::ENSEIGNEMENT_VISUALISATION);

            break;
            case 'Application\Controller\Service.resume':
                return $this->assertEnseignements($role);

            break;
            case 'Application\Controller\Service.importAgenda':
                return $this->assertImportAgenda($role);

            break;
        }

        return true;
    }



    protected function assertEnseignements(Role $role)
    {
        return $this->asserts([
            $role->hasPrivilege(Privileges::ENSEIGNEMENT_VISUALISATION),
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
        $structure = $role->getStructure();

        $asserts = [];

        if ($structure) {
            $structureAffectation  = $serviceReferentiel->getIntervenant() ? $serviceReferentiel->getIntervenant()->getStructure() : null;
            $structureEnseignement = $serviceReferentiel->getStructure();

            if ($structureAffectation && $structureEnseignement) {
                // cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
                $asserts[] = $structure == $structureAffectation || $structure == $structureEnseignement; // le service doit avoir un lien avec la structure
            } elseif ($structureAffectation && !$structureEnseignement) {
                // cas d'un intervenant prenant des enseignements à l'extérieur
                $asserts[] = $structure == $structureAffectation;
            }
        }

        $asserts[] = $this->assertIntervenant($role, $serviceReferentiel->getIntervenant());
        $asserts[] = $this->assertCampagneSaisie($role, $serviceReferentiel->getTypeVolumeHoraire());
        $asserts[] = $this->assertCloture($role, $serviceReferentiel->getIntervenant());

        return $this->asserts($asserts);
    }



    protected function assertHasServices( Intervenant $intervenant, Structure $structure )
    {
        $services = $intervenant->getService()->filter( function(Service $service) use ($structure){
            if (!$service->getElementPedagogique()) return false;
            return $service->getElementPedagogique()->getStructure() == $structure;
        });
        return $services->count() > 0;
    }



    protected function assertCampagneSaisie( Role $role, TypeVolumeHoraire $typeVolumeHoraire )
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
        if ($intervenant->getStatut()->getPeutCloturerSaisie()) {
            if (!$role->hasPrivilege(Privileges::CLOTURE_EDITION_SERVICES)) { // si on peut éditer toujours alors pas la peine de tester...
                $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
                if ($cloture->getId() !== null) return false; // pas de saisie si c'est clôturé
            } else {
                if ($intervenant->hasMiseEnPaiement()) {
                    return false;
                }
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



    protected function assertServiceValidation(Role $role, Validation $validation)
    {
        return $this->asserts([
            !$validation->getId(),
            $this->assertIntervenant($role, $validation->getIntervenant()),
            $this->assertStructure($role, $validation->getStructure()),
        ]);
    }



    protected function assertServiceDevalidation(Role $role, Validation $validation)
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
            $intervenant->estPermanent(),
            $role->hasPrivilege(Privileges::ENSEIGNEMENT_EXTERIEUR),
        ]);
    }



    protected function assertMotifNonPaiement(Role $role, Intervenant $intervenant)
    {
        // filtrer pour la structure ? ?
        return $this->asserts([
            $intervenant->getStatut()->getPeutSaisirMotifNonPaiement(),
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



    protected function assertStructure(Role $role, Structure $structure = null)
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