<?php

namespace Enseignement\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Enseignement\Controller\EnseignementController;
use Enseignement\Controller\VolumeHoraireController;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Service\Assertion\ServiceAssertionAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\RegleStructureValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Unicaen\Framework\Authorize\UnAuthorizedException;
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
    use IntervenantServiceAwareTrait;
    use LocalContextServiceAwareTrait;


    protected function assertEntity(ResourceInterface $entity, ?string $privilege = null): bool
    {
        switch ($privilege) {
            case Privileges::ENSEIGNEMENT_PREVU_VISUALISATION:
                switch (true) {
                    case $entity instanceof Service:
                        return $this->assertVisualisation($entity->getIntervenant(), TypeVolumeHoraire::CODE_PREVU);
                    case $entity instanceof Intervenant:
                        return $this->assertVisualisation($entity, TypeVolumeHoraire::CODE_PREVU);
                }

            case Privileges::ENSEIGNEMENT_PREVU_EDITION:
                switch (true) {
                    case $entity instanceof Service:
                    case $entity instanceof Intervenant:
                        return $this->assertEdition($entity, TypeVolumeHoraire::CODE_PREVU);
                }

            case Privileges::ENSEIGNEMENT_REALISE_VISUALISATION:
                switch (true) {
                    case $entity instanceof Service:
                        return $this->assertVisualisation($entity->getIntervenant(), TypeVolumeHoraire::CODE_REALISE);
                    case $entity instanceof Intervenant:
                        return $this->assertVisualisation($entity, TypeVolumeHoraire::CODE_REALISE);
                }

            case Privileges::ENSEIGNEMENT_REALISE_EDITION:
                switch (true) {
                    case $entity instanceof Service:
                    case $entity instanceof Intervenant:
                        return $this->assertEdition($entity, TypeVolumeHoraire::CODE_REALISE);
                }

            case Privileges::ENSEIGNEMENT_EXTERIEUR:
                switch (true) {
                    case $entity instanceof Service:
                    case $entity instanceof Intervenant:
                        return $this->assertEditionExterieur($entity);
                }


            case Privileges::ENSEIGNEMENT_PREVU_VALIDATION:
            case Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION:
                switch (true) {
                    case $entity instanceof Service:
                    case $entity instanceof VolumeHoraire:
                    case $entity instanceof Validation:
                        return $this->assertValidation($entity, TypeVolumeHoraire::CODE_PREVU);
                }

            case Privileges::ENSEIGNEMENT_REALISE_VALIDATION:
            case Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION:
                switch (true) {
                    case $entity instanceof Service:
                    case $entity instanceof VolumeHoraire:
                    case $entity instanceof Validation:
                        return $this->assertValidation($entity, TypeVolumeHoraire::CODE_REALISE);
                }

            case Privileges::ENSEIGNEMENT_DEVALIDATION:
                if ($entity instanceof Validation) {
                    return $this->assertDevalidation($entity);
                }

            case Privileges::MOTIF_NON_PAIEMENT_VISUALISATION:
            case Privileges::MOTIF_NON_PAIEMENT_EDITION:
                switch (true) {
                    case $entity instanceof Intervenant:
                        return $this->assertMotifNonPaiement($entity);
                }

            case Privileges::TAG_EDITION:
            case Privileges::TAG_VISUALISATION:
                return true; // ps de paramétrage de tags selon statut
        }

        throw new UnAuthorizedException('Action interdite pour la resource ' . $entity->getResourceId() . ', privilège ' . $privilege);
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        /* @var $intervenant ?Intervenant */
        $intervenant = $this->getParam(Intervenant::class);

        if (!$intervenant) {
            $intervenantId = $this->getParam('intervenant');
            if (!$intervenantId) {
                $intervenantId = $_GET['intervenant'] ?? null; // @todo à revoir...
            }
            if ($intervenantId) {
                $intervenant = $this->getServiceIntervenant()->get($intervenantId);
            }
        }

        if (!$intervenant) {
            $intervenant = $this->getServiceLocalContext()->getIntervenant();
        }

        if (!$intervenant) {
            // pas d'accès si intervenant pas identifié
            return false;
        }

        $typeVolumeHoraireCode = $this->getParam('type-volume-horaire-code');
        if (!$typeVolumeHoraireCode) {
            $typeVolumeHoraireId =
                $_GET['type-volume-horaire']
                ?? $_GET['params']['type-volume-horaire']
                   ?? $_POST['type-volume-horaire']
                      ?? $_POST['params']['type-volume-horaire']
                         ?? null; // @todo à revoir

            if ($typeVolumeHoraireId) {
                $typeVolumeHoraireCode = $this->getServiceTypeVolumeHoraire()->get($typeVolumeHoraireId)?->getCode();
            }
        }

        if (!$typeVolumeHoraireCode) {
            if (EnseignementController::class . '.initialisation' === $controller . '.' . $action) {
                $typeVolumeHoraireCode = TypeVolumeHoraire::CODE_PREVU;
            }
            if (EnseignementController::class . '.constatation' === $controller . '.' . $action) {
                $typeVolumeHoraireCode = TypeVolumeHoraire::CODE_REALISE;
            }
        }

        /** @var null|Service $service */
        $service = $this->getParam(Service::class);

        switch ($controller . '.' . $action) {
            case EnseignementController::class . '.prevu':
                return $this->assertVisualisation($intervenant, TypeVolumeHoraire::CODE_PREVU);

            case EnseignementController::class . '.realise':
                return $this->assertVisualisation($intervenant, TypeVolumeHoraire::CODE_REALISE);

            case EnseignementController::class . '.validationPrevu':
                return $this->assertValidation($intervenant, TypeVolumeHoraire::CODE_PREVU);

            case EnseignementController::class . '.validationRealise':
                return $this->assertValidation($intervenant, TypeVolumeHoraire::CODE_REALISE);

            case VolumeHoraireController::class . '.saisie':
            case VolumeHoraireController::class . '.saisieCalendaire':
            case VolumeHoraireController::class . '.suppressionCalendaire':
                $volumeHoraire = new VolumeHoraire();
                return $this->assertEdition($service, $typeVolumeHoraireCode);

            case EnseignementController::class . '.saisie':
            case EnseignementController::class . '.rafraichirLigne':
            case EnseignementController::class . '.saisieFormRefreshVh':
            case EnseignementController::class . '.suppression':
            case EnseignementController::class . '.initialisation':
            case EnseignementController::class . '.constatation':
                return $this->assertEdition($intervenant, $typeVolumeHoraireCode);
        }

        throw new UnAuthorizedException('Action de contrôleur ' . $controller . ':' . $action . ' non traitée');
    }



    protected function assertVisualisation(Intervenant $entite, string $typeVolumeHoraireCode): bool
    {
        $statut         = $entite->getStatut();
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($entite);

        return match ($typeVolumeHoraireCode) {
            TypeVolumeHoraire::CODE_PREVU   => $this->asserts(
                $statut->getServicePrevu(),
                $feuilleDeRoute->get(WorkflowEtape::ENSEIGNEMENT_SAISIE)?->isAllowed()
            ),
            TypeVolumeHoraire::CODE_REALISE => $this->asserts(
                $statut->getServiceRealise(),
                $feuilleDeRoute->get(WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE)?->isAllowed()
            ),
            default                         => false,
        };
    }



    protected function assertEdition(Service|Intervenant $entite, string $typeVolumeHoraireCode): bool
    {
        $contextIntervenant = $this->getServiceContext()->getIntervenant();

        if (!$contextIntervenant) {
            // On est gestionnaire => selon le périmètre
            if (!$this->assertStructureEdition($entite)) {
                return false;
            }
        }

        if ($entite instanceof Service) {

            // Test pour les enseignements faits à l'extérieur
            $isExterieur = $entite->getEtablissement() && $entite->getEtablissement() !== $this->getServiceContext()->getEtablissement();
            if ($isExterieur && !$this->assertEditionExterieur($entite)) {
                return false;
            }

            // Test relatif aux campagnes de saisie
            if ($entite->getTypeVolumeHoraire()) {
                if ($entite->getTypeVolumeHoraire()->getCode() !== $typeVolumeHoraireCode) {
                    //throw new \Exception('Incohérence entre le type de volume horaire du service et le test d\'assertion');
                    return false;
                }

                if (!$this->getAssertionService()->assertCampagneSaisie($entite->getTypeVolumeHoraire())) {
                    return false;
                }
            }

            $entite = $entite->getIntervenant();
        }

        $statut = $entite->getStatut();

        return match ($typeVolumeHoraireCode) {
            TypeVolumeHoraire::CODE_PREVU   => $statut->getServicePrevuEdition(),
            TypeVolumeHoraire::CODE_REALISE => $this->asserts(
                $statut->getServiceRealiseEdition(),
                $this->getAssertionService()->assertCloture($entite)
            ),
            default                         => false,
        };
    }



    protected function assertEditionExterieur(Service|Intervenant $entite): bool
    {
        if ($entite instanceof Service) {
            if ($entite->getId()) {
                return true; // La ligne existe déjà => on doit pouvoir la supprimer
            }
            $entite = $entite->getIntervenant();
        }

        if (!$entite->getStructure()) {
            // Un intervenant n'ayant aucune structure d'affectation ne peut pas suivre d'enseignement à l'extérieur
            // Prérequis indispensable pour savoir qui aura à valider ses enseignements
            return false;
        }

        return $entite->getStatut()->getServiceExterieur();
    }



    protected function assertMotifNonPaiement(Intervenant $intervenant): bool
    {
        return $intervenant->getStatut()->getMotifNonPaiement();
    }



    protected function assertStructureEdition(Service|Intervenant|Structure $entite): bool
    {
        $contextStructure = $this->getServiceContext()->getStructure();

        if (empty($contextStructure)) {
            // Pas de périmètre structure
            return true;
        }

        $structure = $this->getServiceContext()->getStructure();

//        $asserts = [];
//
//        if ($structure) {
//            $structureAffectation  = $service->getIntervenant() ? $service->getIntervenant()->getStructure() : null;
//            $structureEnseignement = $service->getElementPedagogique() ? $service->getElementPedagogique()->getStructure() : null;
//
//            if ($structureAffectation && $structureEnseignement) {
//                 cas d'un intervenant d'une autre structure prenant un enseignement dans une autre structure
//                $asserts[] = $structureAffectation->inStructure($structure) || $structureEnseignement->inStructure($structure); // le service doit avoir un lien avec la structure
//            } elseif ($structureAffectation && !$structureEnseignement) {
//                 cas d'un intervenant prenant des enseignements à l'extérieur
//                $asserts[] = $structure == $structureAffectation;
//            }
//        }

        /// @todo compléter...
        /// A consolider, logique temporaire pour accéder de nouveau à la saisi d'enseignement
        if ($entite instanceof Service) {
            $structure = $entite->getStructure();
        } elseif ($entite instanceof Intervenant) {
            $structure = $entite->getStructure();
        } else {
            $structure = $entite;
        }


        // On ne peut éditer que dans sa structure & ses filles
        return $structure->inStructure($contextStructure);
    }



    protected function assertStructureValidation(Service $service): bool
    {
        /// @todo à coder : exploitation des règles de validation
        return false;
    }



    protected function assertValidation(Service|VolumeHoraire|Intervenant|Validation $entite, string $typeVolumeHoraireCode): bool
    {
        if ($entite instanceof Validation) {
            $entite = $entite->getIntervenant();
        }

        if ($entite instanceof VolumeHoraire) {
            $entite = $entite->getService();
        }

        if ($entite instanceof Service) {
            if (!$this->assertStructureValidation($entite)) {
                return false;
            }

            $entite = $entite->getIntervenant();
        }

        $statut         = $entite->getStatut();
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($entite);

        return match ($typeVolumeHoraireCode) {
            TypeVolumeHoraire::CODE_PREVU   => $this->asserts(
                $statut->getServicePrevu(),
                $feuilleDeRoute->get(WorkflowEtape::ENSEIGNEMENT_VALIDATION)?->isAllowed()
            ),
            TypeVolumeHoraire::CODE_REALISE => $this->asserts(
                $statut->getServiceRealise(),
                $feuilleDeRoute->get(WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE)?->isAllowed()
            ),
            default                         => false,
        };
    }



    protected function assertValidationValidation(Validation $validation): bool
    {
        return $this->asserts([
                                  !$validation->getId(),
                                  $this->assertValidation($validation->getIntervenant(), $validation->getStructure()),
                              ]);
    }



    protected function assertDevalidation(Validation $validation): bool
    {
        return $this->asserts([
                                  $validation->getId(),
                                  //$this->assertValidation($validation->getIntervenant(), $validation->getStructure()),
                                  $this->getAssertionService()->assertStructure($validation->getStructure()),
                              ]);
    }

}