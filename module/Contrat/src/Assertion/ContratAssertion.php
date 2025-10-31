<?php

namespace Contrat\Assertion;

use Administration\Entity\Db\Parametre;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Contrat\Controller\ContratController;
use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur


/**
 * Description of ContratAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ContratAssertion extends AbstractAssertion
{
    use ParametresServiceAwareTrait;
    use ContratServiceAwareTrait;
    use ContextServiceAwareTrait;

    const PRIV_LISTER_FICHIERS   = 'contrat-lister-fichiers';
    const PRIV_SUPPRIMER_FICHIER = 'contrat-supprimer-fichier';
    const PRIV_AJOUTER_FICHIER   = 'contrat-ajouter-fichier';
    const PRIV_EXPORT            = 'contrat-export-all';



    /**
     * @return \Application\Service\ContextService|null
     */
    public function getContextService(): ?\Application\Service\ContextService
    {
        return $this->getServiceContext();
    }



    use WorkflowServiceAwareTrait;


    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Contrat:
                switch ($privilege) {
                    case Privileges::CONTRAT_PROJET_GENERATION:
                    case Privileges::CONTRAT_CONTRAT_GENERATION:
                    case self::PRIV_EXPORT:
                        return $this->assertGeneration($entity);
                    case self::PRIV_LISTER_FICHIERS:
                        return $this->assertListerFichiers($entity);

                    case self::PRIV_AJOUTER_FICHIER:
                    case self::PRIV_SUPPRIMER_FICHIER:
                        return $this->assertModifierFichier($entity);

                    case Privileges::CONTRAT_VISUALISATION:
                        return $this->assertVisualisation($entity);

                    case Privileges::CONTRAT_CREATION:
                        return $this->assertCreation($entity);

                    case Privileges::CONTRAT_VALIDATION:
                        return $this->assertValidation($entity);

                    case Privileges::CONTRAT_DEPOT_RETOUR_SIGNE:
                        return $this->assertDepotRetourSigne($entity);

                    case Privileges::CONTRAT_ENVOYER_SIGNATURE_ELECTRONIQUE:
                        return $this->assertEnvoyerSignatureElectronique($entity);

                    case Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE:
                        return $this->assertSaisieDateRetour($entity);

                    case Privileges::CONTRAT_DEVALIDATION:
                        return $this->assertDevalidation($entity);

                    case Privileges::CONTRAT_SUPPRESSION:
                        return $this->assertSuppression($entity);
                    case Privileges::CONTRAT_ENVOI_EMAIL:
                        return true;
                }
                break;
        }

        throw new UnAuthorizedException('Action interdite pour la resource ' . $entity->getResourceId() . ', privilège ' . $privilege);
    }



    protected function assertGeneration(Contrat $contrat): bool
    {
        //Si je suis connecté en tant qu'intervenant
        if ($this->getContextService()->getIntervenant()) {
            //Si le role à le même intervenant que le contrat et que le contrat est validé
            if ($this->getServiceContext()->getIntervenant() == $contrat->getIntervenant() && !$contrat->estUnProjet()) {
                return true;
            }
        }

        if ($contrat->estUnProjet()) {
            return $this->authorize->isAllowedPrivilege(Privileges::CONTRAT_PROJET_GENERATION);
        } else {
            return $this->authorize->isAllowedPrivilege(Privileges::CONTRAT_CONTRAT_GENERATION);
        }
    }



    protected function assertListerFichiers(Contrat $contrat): bool
    {
        return $this->asserts(
            $this->authorize->isAllowedPrivilege(Privileges::CONTRAT_VISUALISATION),
            $this->assertVisualisation($contrat),
            !$contrat->estUnProjet(),
        );
    }



    protected function assertVisualisation(Contrat $contrat): bool
    {
        return $this->assertRole($contrat);
    }



    protected function assertRole(Contrat $contrat, $checkStructure = true): bool
    {
        if ($intervenant = $this->getServiceContext()->getIntervenant()) {
            if (!$this->assertIntervenant($contrat, $intervenant)) return false;
        }

        if ($checkStructure && ($structure = $this->getServiceContext()->getStructure())) {
            if (!$this->assertStructure($contrat, $structure)) return false;
        }

        return true;
    }



    protected function assertIntervenant(Contrat $contrat, Intervenant $intervenant): bool
    {
        return $contrat->getIntervenant() == $intervenant;
    }



    protected function assertStructure(Contrat $contrat, Structure $structure): bool
    {
        return $contrat->getStructure() == null || $contrat->getStructure()->inStructure($structure);
    }



    protected function assertModifierFichier(Contrat $contrat): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::CONTRAT_DEPOT_RETOUR_SIGNE),
                                  empty($contrat->getDateRetourSigne()),
                                  $this->assertDepotRetourSigne($contrat),
                              ]);
    }



    protected function assertDepotRetourSigne(Contrat $contrat): bool
    {
        return $this->asserts([
                                  $this->assertRole($contrat),
                                  !$contrat->estUnProjet(),
                              ]);
    }



    protected function assertCreation(Contrat $contrat): bool
    {
        return $this->asserts([
                                  $this->assertRole($contrat),
                                  $this->assertWorkflow($contrat),
                              ]);
    }



    protected function assertWorkflow(Contrat $contrat): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($contrat->getIntervenant(), $contrat->getStructure());
        $wfEtape        = $feuilleDeRoute->get(WorkflowEtape::CONTRAT);

        return $wfEtape->isAllowed();
    }



    protected function assertValidation(Contrat $contrat): bool
    {
        return $this->asserts([
                                  $this->assertRole($contrat),
                                  !$contrat->getValidation(),
                              ]);
    }



    protected function assertEnvoyerSignatureElectronique(Contrat $contrat): bool
    {


        return $this->asserts([
                                  $this->assertRole($contrat),
                                  !$contrat->estUnProjet(),
                                  $contrat->getValidation(),
                              ]);
    }



    protected function assertSaisieDateRetour(Contrat $contrat): bool
    {
        return $this->asserts([
                                  $this->assertRole($contrat),
                                  !$contrat->estUnProjet(),

                              ]);
    }



    protected function assertDevalidation(Contrat $contrat): bool
    {
        //Si j'ai un signature electronique sur le contrat je ne peux plus le dévalider
        if ($contrat->getProcessSignature()) {
            return false;
        }
        if (!$contrat->estUnAvenant()) {
            $contratService = $this->getServiceContrat();
            $devalid        = !$contratService->hasAvenant($contrat);
        } else {
            $devalid = true;
        }

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        return $this->asserts([
                                  $this->assertRole($contrat),
                                  $contrat->getValidation() && !$contratDirect,
                                  !$contrat->getDateRetourSigne(),
                                  $devalid,
                              ]);
    }



    protected function assertSuppression(Contrat $contrat): bool
    {
        if (!$contrat->estUnAvenant()) {
            $devalid = $contrat->getIntervenant()->getAvenantEnfant($contrat)->count() < 1; // on ne peut supprimer un contrat que si aucun avenant n'existe
        } else {
            $devalid = true;
        }

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        return $this->asserts([
                                  $devalid,
                                  $this->assertRole($contrat),
                                  !$contrat->getValidation() || $contratDirect,
                                  !$contrat->getDateRetourSigne(),
                              ]);
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        switch ($controller . '.' . $action) {
            case ContratController::class . '.index':
            case ContratController::class . '.exporter':
            case ContratController::class . '.creer':
            case ContratController::class . '.creerMission':
            case ContratController::class . '.supprimer':
            case ContratController::class . '.valider':
            case ContratController::class . '.devalider':
            case ContratController::class . '.deposerFichier':
            case ContratController::class . '.supprimerFichier':
            case ContratController::class . '.saisirRetour':
            case ContratController::class . '.creerProcessSignature':
            case ContratController::class . '.supprimerProcessSignature':
            case ContratController::class . '.rafraichirProcessSignature':
                $intervenant = $this->getParam(Intervenant::class);
                if (!$intervenant) {
                    $contrat = $this->getParam(Contrat::class);
                    if ($contrat) {
                        $intervenant = $contrat->getIntervenant();
                    }
                }
                if ($intervenant) {
                    $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
                    $wfEtape        = $feuilleDeRoute->get(WorkflowEtape::CONTRAT);
                    if ($wfEtape && $wfEtape->isAllowed()) return true;
                }

                throw new UnAuthorizedException('Action de contrôleur ' . $controller . ':' . $action . ' non autorisée');
            default:
                throw new UnAuthorizedException('Action de contrôleur ' . $controller . ':' . $action . ' non traitée');
        }
    }
}