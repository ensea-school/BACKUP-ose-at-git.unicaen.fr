<?php

namespace Dossier\Assertion;

use Application\Provider\Privileges;
use Dossier\Controller\IntervenantDossierController;
use Dossier\Entity\Db\IntervenantDossier;
use Intervenant\Entity\Db\Intervenant;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of IntervenantDossierAssertion
 */
class IntervenantDossierAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;


    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);

        switch ($controller) {
            case IntervenantDossierController::class . 'index':
                return $this->assertDossierEdition($intervenant);
        }

        return true;
    }



    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {


        switch (true) {
            case $entity instanceof IntervenantDossier:
                switch ($privilege) {
                    case Privileges::DOSSIER_VALIDATION:
                        return $this->assertCanValidate($entity);
                    case Privileges::DOSSIER_VALIDATION_COMP:
                        return $this->assertCanValidateComplementaire($entity);
                    case Privileges::DOSSIER_DEVALIDATION:
                        return $this->assertCanDevalidate($entity);
                    case Privileges::DOSSIER_DEVALIDATION_COMP:
                        return $this->assertCanDevalidateComplementaire($entity);
                    case Privileges::DOSSIER_SUPPRESSION:
                        return $this->assertCanSupprime($entity);
                    case Privileges::DOSSIER_EDITION:
                        return $this->assertDossierEdition($entity);//DOSSIER_VISUALISATION_COMP
                    case Privileges::DOSSIER_VISUALISATION_COMP:
                        return $this->assertDossierComplementaireVisualisation($entity);//DOSSIER_VISUALISATION_COMP
                    case Privileges::DOSSIER_EDITION_COMP:
                        return $this->assertDossierComplementaireEdition($entity);
                    case Privileges::DOSSIER_IDENTITE_EDITION:
                        return $this->assertEditIdentite($entity);
                    case Privileges::DOSSIER_IDENTITE_VISUALISATION:
                        return $this->assertViewIdentite();
                    case Privileges::DOSSIER_ADRESSE_EDITION:
                        return $this->assertEditAdresse($entity);
                    case Privileges::DOSSIER_ADRESSE_VISUALISATION:
                        return $this->assertViewAdresse();
                    case Privileges::DOSSIER_CONTACT_EDITION:
                        return $this->assertEditContact($entity);
                    case Privileges::DOSSIER_CONTACT_VISUALISATION:
                        return $this->assertViewContact();
                    case Privileges::DOSSIER_INSEE_EDITION:
                        return $this->assertEditInsee($entity);
                    case Privileges::DOSSIER_INSEE_VISUALISATION:
                        return $this->assertViewInsee();
                    case Privileges::DOSSIER_BANQUE_EDITION:
                        return $this->assertEditIban($entity);
                    case Privileges::DOSSIER_BANQUE_VISUALISATION:
                        return $this->assertViewIban();
                    case Privileges::DOSSIER_EMPLOYEUR_EDITION:
                        return $this->assertEditEmployeur($entity);
                    case Privileges::DOSSIER_EMPLOYEUR_VISUALISATION:
                        return $this->assertViewEmployeur();
                    case Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION:
                        return $this->assertEditAutre1($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION:
                        return $this->assertViewAutre1($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION:
                        return $this->assertEditAutre2($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION:
                        return $this->assertViewAutre2($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION:
                        return $this->assertEditAutre3($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION:
                        return $this->assertViewAutre3($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION:
                        return $this->assertEditAutre4($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION:
                        return $this->assertViewAutre4($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION:
                        return $this->assertEditAutre5($entity);
                    case Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION:
                        return $this->assertViewAutre5($entity);
                }
        }

        return false;
    }



    protected function assertEditIdentite(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'identite'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_IDENTITE_EDITION),
                              ]);
    }



    protected function assertViewIdentite(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_IDENTITE_VISUALISATION),
                              ]);
    }



    protected function assertEditAdresse(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'adresse'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_ADRESSE_EDITION),
                              ]);
    }



    protected function assertViewAdresse(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION),
                              ]);
    }



    protected function assertEditContact(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'contact'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CONTACT_EDITION),
                              ]);
    }



    protected function assertViewContact(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CONTACT_VISUALISATION),
                              ]);
    }



    protected function assertEditInsee(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'insee'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_INSEE_EDITION),
                              ]);
    }



    protected function assertViewInsee(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),

                              ]);
    }



    protected function assertEditIban(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'iban'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_BANQUE_EDITION),
                              ]);
    }



    protected function assertViewIban(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
                              ]);
    }



    protected function assertEditEmployeur(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'employeur'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_EMPLOYEUR_EDITION),
                              ]);
    }



    protected function assertViewEmployeur(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_VISUALISATION),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_EMPLOYEUR_VISUALISATION),
                              ]);
    }



    protected function assertEditAutre1(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'autre1'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION),
                              ]);
    }



    protected function assertViewAutre1(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION),
                              ]);
    }



    protected function assertEditAutre2(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'autre2'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION),
                              ]);
    }



    protected function assertViewAutre2(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION),
                              ]);
    }



    protected function assertEditAutre3(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'autre3'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION),
                              ]);
    }



    protected function assertViewAutre3(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION),
                              ]);
    }



    protected function assertEditAutre4(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'autre4'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION),
                              ]);
    }



    protected function assertViewAutre4(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION),
                              ]);
    }



    protected function assertEditAutre5(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertStep($intervenantDossier, 'autre5'),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION),
                              ]);
    }



    protected function assertViewAutre5(): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION),
                              ]);
    }



    protected function assertCanValidate(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $intervenantDossier->getTblDossier()->isCompletAvantRecrutement(),
                                  !$intervenantDossier->getTblDossier()->getValidation(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_VALIDATION),
                              ]);
    }



    protected function assertCanValidateComplementaire(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  $intervenantDossier->getTblDossier()->isCompletApresRecrutement(),
                                  !$intervenantDossier->getTblDossier()->getValidationComplementaire(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_VALIDATION_COMP),
                              ]);
    }



    protected function assertCanDevalidate(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $intervenantDossier->getTblDossier()->getValidation(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_DEVALIDATION),
                              ]);
    }



    protected function assertCanDevalidateComplementaire(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  $intervenantDossier->getTblDossier()->getValidationComplementaire(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_DEVALIDATION_COMP),
                              ]);
    }



    protected function assertCanEdit(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  !$intervenantDossier->getTblDossier()->getValidation(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_EDITION),
                              ]);
    }



    protected function assertDossierComplementaireEdition(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  !$intervenantDossier->getTblDossier()->getValidationComplementaire(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_EDITION_COMP),
                              ]);
    }



    protected function assertDossierComplementaireVisualisation(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_VISUALISATION_COMP),
                              ]);
    }



    protected function assertCanSupprime(IntervenantDossier $intervenantDossier): bool
    {

        return $this->asserts([
                                  $intervenantDossier->getTblDossier()->getValidation(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_SUPPRESSION),
                              ]);
    }



    protected function assertDossierEdition(IntervenantDossier $intervenantDossier): bool
    {
        return $this->asserts([
                                  $this->assertEtapeAtteignable(WorkflowEtape::DONNEES_PERSO_SAISIE, $intervenantDossier->getIntervenant()),
                                  !$intervenantDossier->getTblDossier()->getValidation(),
                                  $this->authorize->isAllowedPrivilege(Privileges::DOSSIER_EDITION),
                              ]);
    }



    protected function assertEtapeAtteignable(string $etape, Intervenant $intervenant): bool
    {

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        return $feuilleDeRoute->get($etape)?->isAllowed() ?: false;

        return true;
    }



    protected function assertStep(IntervenantDossier $intervenantDossier, string $step): bool
    {
        $validation               = $intervenantDossier->getTblDossier()->getvalidation();
        $validationComplementaire = $intervenantDossier->getTblDossier()->getvalidationComplementaire();
        $statut                   = $intervenantDossier->getIntervenant()->getStatut();

        switch ($step) {
            case 'identite':
                $step = 1;
                break;
            case 'donneesComplementaires':
                $step = $statut->getDossierIdentiteComplementaire();
                break;
            case 'contact':
                $step = $statut->getDossierContact();
                break;
            case 'adresse':
                $step = $statut->getDossierAdresse();
                break;
            case 'insee':
                $step = $statut->getDossierInsee();
                break;
            case 'iban':
                $step = $statut->getDossierBanque();
                break;
            case 'employeur':
                $step = $statut->getDossierEmployeur();
                break;
            case 'autre1':
                $step = $statut->getDossierAutre1();
                break;
            case 'autre2':
                $step = $statut->getDossierAutre2();
                break;
            case 'autre3':
                $step = $statut->getDossierAutre3();
                break;
            case 'autre4':
                $step = $statut->getDossierAutre4();
                break;
            case 'autre5':
                $step = $statut->getDossierAutre5();
                break;
        }

        if (($step == 1 && !empty($validation)) || ($step == 2 && !empty($validationComplementaire))) {
            return false;
        }

        return true;
    }

}
