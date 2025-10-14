<?php

namespace Service\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Service\Controller\ServiceController;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


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



    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam('intervenant');
        /* @var $intervenant Intervenant */

        if (!$this->assertIntervenant($intervenant)) return false; // si on n'est pas le bon intervenant!!

        switch ($controller . '.' . $action) {
            case ServiceController::class . '.index':
            case ServiceController::class . '.resume':
                return $this->assertResume();
                break;
            case ServiceController::class . '.intervenant-saisie-prevu':
                return $this->assertPageServices($intervenant, TypeVolumeHoraire::CODE_PREVU);
                break;
            case ServiceController::class . '.intervenant-saisie-realise':
                return $this->assertPageServices($intervenant, TypeVolumeHoraire::CODE_REALISE);
                break;
        }

        return true;
    }



    protected function assertPageServices(?Intervenant $intervenant, string $typeVolumeHoraireCode): bool
    {
        if (!$intervenant) return true;

        $statut = $intervenant->getStatut();

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $asserts = [
            $this->assertIntervenant($intervenant),
            $this->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeEnseignementSaisie(), $intervenant) ||
            $this->assertEtapeAtteignable($typeVolumeHoraire->getWfEtapeReferentielSaisie(), $intervenant),
        ];
        if ($typeVolumeHoraire->isPrevu()) {
            $asserts[] = $statut->getServicePrevu() || $statut->getReferentielPrevu();
        }
        if ($typeVolumeHoraire->isRealise()) {
            $asserts[] = $statut->getServiceRealise() || $statut->getReferentielRealise();
        }

        return $this->asserts($asserts);
    }



    protected function assertResume(): bool
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



    protected function assertHasServices(Intervenant $intervenant, Structure $structure, string $etape): bool
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

            if (($etape == WorkflowEtape::ENSEIGNEMENT_VALIDATION || $etape == WorkflowEtape::REFERENTIEL_VALIDATION) && $regle->getTypeVolumeHoraire()->getCode() == TypeVolumeHoraire::CODE_PREVU) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure() == $this->getServiceContext()->getStructure())) {
                        return true;
                    }
                }
            }

            if (($etape == WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE || $etape == WorkflowEtape::REFERENTIEL_VALIDATION_REALISE) && $regle->getTypeVolumeHoraire()->getCode() == TypeVolumeHoraire::CODE_REALISE) {
                if ($regle->getTypeIntervenant()->getCode() == $typeIntervenant->getCode()) {
                    //Cas 1 : Si la priorité est sur l'enseignement et qu'il y a du service sur la composante du gestionnaire alors on peut valider ces services
                    //Cas 2 : Si la priorité est sur l'affectation et que la composante d'affectation de l'intervenant égale à la composante du gestionnaire alors on peut valider tout le service
                    if (($regle->getPriorite() == 'enseignement' && $nbServices > 0) ||
                        ($regle->getPriorite() == 'affectation' && $intervenant->getStructure() == $this->getServiceContext()->getStructure())) {
                        return true;
                    }
                }
            }
        }


        return false;
    }



    public function assertCampagneSaisie(TypeVolumeHoraire $typeVolumeHoraire): bool
    {
        if ($this->getServiceContext()->getIntervenant()) {
            $campagneSaisie = $this->getServiceCampagneSaisie()->getBy(
                $this->getServiceContext()->getIntervenant()->getStatut()->getTypeIntervenant(),
                $typeVolumeHoraire
            );
            if (!$campagneSaisie->estOuverte()) return false;
        }

        return true;
    }



    public function assertCloture(Intervenant $intervenant): bool
    {
        if ($intervenant->getStatut()->getCloture()) {
            $hardPassCloture = $this->authorize->isAllowedPrivilege(Privileges::CLOTURE_EDITION_SERVICES_AVEC_MEP);
            if ($hardPassCloture) return true; // on a toujours le droit

            $cloture = $this->getServiceValidation()->getValidationClotureServices($intervenant);
            if (!($cloture && $cloture->getId())) return true; // S'il n'y a pas de clôture alors OK

            $softPassCloture = $this->authorize->isAllowedPrivilege(Privileges::CLOTURE_EDITION_SERVICES);
            if ($softPassCloture) {
                return !$intervenant->hasMiseEnPaiement(); // S'il n'y a pas de DMEP alors OK ça passe
            } else {
                return false; // c'est cloturé => lecture seule
            }
        }

        return true;
    }



    public function assertMotifNonPaiement(Intervenant $intervenant): bool
    {
        // filtrer pour la structure ? ?
        return $this->asserts([
                                  $intervenant->getStatut()->getMotifNonPaiement(),
                                  $this->assertIntervenant($intervenant),
                              ]);
    }



    public function assertIntervenant(?Intervenant $intervenant = null): bool
    {
        if ($intervenant) {
            if ($ri = $this->getServiceContext()->getIntervenant()) {
                if ($ri != $intervenant) { // un intervenant ne peut pas voir les services d'un autre
                    return false;
                }
            }
        }

        return true;
    }



    public function assertStructure(?Structure $structure = null): bool
    {
        if ($structure) {
            if ($ri = $this->getServiceContext()->getStructure()) {
                if (!$structure->inStructure($ri)) { // une structure ne peut pas éditer les services d'une autre
                    return false;
                }
            }
        }

        return true;
    }



    public function assertEtapeAtteignable($etape, ?Intervenant $intervenant = null): bool
    {
        if (str_contains($etape, ',')){
            $etapes = explode(',', $etape);
            foreach ($etapes as $etape) {
                if ($this->assertEtapeAtteignable($etape, $intervenant)) {
                    return true;
                }
            }
        }

        if ($intervenant) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
            return $feuilleDeRoute->get($etape)?->isAllowed() ?? false;
        }

        return true;
    }
}