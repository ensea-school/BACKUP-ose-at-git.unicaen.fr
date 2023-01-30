<?php

namespace Service\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Enseignement\Entity\Db\Service;
use Referentiel\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
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
    use RegleStructureValidationServiceAwareTrait;


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
                return $this->assertHasServices($intervenant, $role->getStructure(), $etape, $role);
            } else {
                if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                    return false;
                }
            }
        }

        if ($intervenant && isset($page['route'])) {
            switch ($page['route']) {
                case 'intervenant/services-prevus':
                    return $this->assertPageServices($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
                break;
                case 'intervenant/services-realises':
                    return $this->assertPageServices($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
                break;
            }
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
            case ServiceController::class . '.index':
            case ServiceController::class . '.resume':
                return $this->assertResume($role);
            break;
            case ServiceController::class . '.intervenant-saisie-prevu':
                return $this->assertPageServices($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
            break;
            case ServiceController::class . '.intervenant-saisie-realise':
                return $this->assertPageServices($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
            break;
        }

        return true;
    }



    protected function assertPageServices(Role $role, Intervenant $intervenant = null, string $typeVolumeHoraireCode)
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->assertIntervenant($role, $intervenant),
            $this->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeServiceSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu() || $statut->getReferentielPrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise() || $statut->getReferentielRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertResume(Role $role)
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



    protected function assertHasServices(Intervenant $intervenant, Structure $structure, string $etape, Role $role)
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



    public function assertCampagneSaisie(Role $role, TypeVolumeHoraire $typeVolumeHoraire)
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



    public function assertCloture(Role $role, Intervenant $intervenant)
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



    public function assertMotifNonPaiement(Role $role, Intervenant $intervenant)
    {
        // filtrer pour la structure ? ?
        return $this->asserts([
            $intervenant->getStatut()->getMotifNonPaiement(),
            $this->assertIntervenant($role, $intervenant),
        ]);
    }



    public function assertIntervenant(Role $role, Intervenant $intervenant = null)
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



    public function assertStructure(Role $role, ?Structure $structure = null)
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



    public function assertEtapeAtteignable($etape, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}