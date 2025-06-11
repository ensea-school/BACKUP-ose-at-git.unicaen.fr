<?php

namespace Dossier\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Dossier\Controller\IntervenantDossierController;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\WfEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur


/**
 * Description of IntervenantDossierAssertion
 */
class IntervenantDossierAssertion extends AbstractAssertion
{
    //Constantes privilieges personnalisés
    const PRIV_EDIT_IDENTITE  = 'dossier-edit-identite';
    const PRIV_VIEW_IDENTITE  = 'dossier-view-identite';
    const PRIV_EDIT_ADRESSE   = 'dossier-edit-adresse';
    const PRIV_VIEW_ADRESSE   = 'dossier-view-adresse';
    const PRIV_EDIT_CONTACT   = 'dossier-edit-contact';
    const PRIV_VIEW_CONTACT   = 'dossier-view-contact';
    const PRIV_VIEW_IBAN      = 'dossier-voir-iban';
    const PRIV_EDIT_IBAN      = 'dossier-edit-iban';
    const PRIV_EDIT_INSEE     = 'dossier-edit-insee';
    const PRIV_VIEW_INSEE     = 'dossier-voir-insee';
    const PRIV_EDIT_EMPLOYEUR = 'dossier-edit-employeur';
    const PRIV_VIEW_EMPLOYEUR = 'dossier-voir-employeur';
    const PRIV_EDIT_AUTRE1    = 'dossier-edit-autre1';
    const PRIV_VIEW_AUTRE1    = 'dossier-voir-autre1';
    const PRIV_EDIT_AUTRE2    = 'dossier-edit-autre2';
    const PRIV_VIEW_AUTRE2    = 'dossier-voir-autre2';
    const PRIV_EDIT_AUTRE3    = 'dossier-edit-autre3';
    const PRIV_VIEW_AUTRE3    = 'dossier-voir-autre3';
    const PRIV_EDIT_AUTRE4    = 'dossier-edit-autre4';
    const PRIV_VIEW_AUTRE4    = 'dossier-voir-autre4';
    const PRIV_EDIT_AUTRE5    = 'dossier-edit-autre5';
    const PRIV_VIEW_AUTRE5    = 'dossier-voir-autre5';
    const PRIV_CAN_VALIDE     = 'dossier-peut-valider';
    const PRIV_CAN_DEVALIDE   = 'dossier-peut-devalider';
    const PRIV_CAN_EDIT       = 'dossier-peut-editer';
    const PRIV_CAN_SUPPRIME   = 'dossier-peut-supprimer';

    use WorkflowServiceAwareTrait;
    use DossierServiceAwareTrait;

    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        $localPrivs = [
            self::PRIV_VIEW_IDENTITE,
            self::PRIV_EDIT_IDENTITE,
            self::PRIV_EDIT_ADRESSE,
            self::PRIV_VIEW_ADRESSE,
            self::PRIV_EDIT_CONTACT,
            self::PRIV_VIEW_CONTACT,
            self::PRIV_EDIT_INSEE,
            self::PRIV_VIEW_INSEE,
            self::PRIV_VIEW_IBAN,
            self::PRIV_EDIT_IBAN,
            self::PRIV_VIEW_EMPLOYEUR,
            self::PRIV_EDIT_EMPLOYEUR,
            self::PRIV_VIEW_AUTRE1,
            self::PRIV_EDIT_AUTRE1,
            self::PRIV_VIEW_AUTRE2,
            self::PRIV_EDIT_AUTRE2,
            self::PRIV_VIEW_AUTRE3,
            self::PRIV_EDIT_AUTRE3,
            self::PRIV_VIEW_AUTRE4,
            self::PRIV_EDIT_AUTRE4,
            self::PRIV_VIEW_AUTRE5,
            self::PRIV_EDIT_AUTRE5,
            self::PRIV_CAN_VALIDE,
            self::PRIV_CAN_DEVALIDE,
            self::PRIV_CAN_EDIT,
            self::PRIV_CAN_SUPPRIME,
        ];

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case self::PRIV_EDIT_IDENTITE:
                        return $this->assertEditIdentite($entity);
                    case self::PRIV_VIEW_IDENTITE:
                        return $this->assertViewIdentite();
                    case self::PRIV_EDIT_ADRESSE:
                        return $this->assertEditAdresse($entity);
                    case self::PRIV_VIEW_ADRESSE:
                        return $this->assertViewAdresse();
                    case self::PRIV_EDIT_CONTACT:
                        return $this->assertEditContact($entity);
                    case self::PRIV_VIEW_CONTACT:
                        return $this->assertViewContact();
                    case self::PRIV_EDIT_INSEE:
                        return $this->assertEditInsee($entity);
                    case self::PRIV_VIEW_INSEE:
                        return $this->assertViewInsee();
                    case self::PRIV_EDIT_IBAN:
                        return $this->assertEditIban($entity);
                    case self::PRIV_VIEW_IBAN:
                        return $this->assertViewIban();
                    case self::PRIV_EDIT_EMPLOYEUR:
                        return $this->assertEditEmployeur($entity);
                    case self::PRIV_VIEW_EMPLOYEUR:
                        return $this->assertViewEmployeur();
                    case self::PRIV_EDIT_AUTRE1:
                        return $this->assertEditAutre1($entity);
                    case self::PRIV_VIEW_AUTRE1:
                        return $this->assertViewAutre1();
                    case self::PRIV_EDIT_AUTRE2:
                        return $this->assertEditAutre2($entity);
                    case self::PRIV_VIEW_AUTRE2:
                        return $this->assertViewAutre2();
                    case self::PRIV_EDIT_AUTRE3:
                        return $this->assertEditAutre3($entity);
                    case self::PRIV_VIEW_AUTRE3:
                        return $this->assertViewAutre3();
                    case self::PRIV_EDIT_AUTRE4:
                        return $this->assertEditAutre4($entity);
                    case self::PRIV_VIEW_AUTRE4:
                        return $this->assertViewAutre4();
                    case self::PRIV_EDIT_AUTRE5:
                        return $this->assertEditAutre5($entity);
                    case self::PRIV_VIEW_AUTRE5:
                        return $this->assertViewAutre5();
                    case self::PRIV_CAN_VALIDE:
                        return $this->assertCanValidate($entity);
                    case self::PRIV_CAN_DEVALIDE:
                        return $this->assertCanDevalidate($entity);
                    case self::PRIV_CAN_EDIT:
                        return $this->assertCanEdit($entity);
                    case self::PRIV_CAN_SUPPRIME:
                        return $this->assertCanSupprime($entity);
                }
            break;
        }
    }



    protected function assertEditIdentite(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_EDITION),
        ]);
    }



    protected function assertViewIdentite(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_VISUALISATION),
        ]);
    }



    protected function assertEditAdresse(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_EDITION),
        ]);
    }



    protected function assertViewAdresse(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION),
        ]);
    }



    protected function assertEditContact(Intervenant $intervenant): bool
    {

        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_EDITION),
        ]);
    }



    protected function assertViewContact(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_VISUALISATION),
        ]);
    }



    protected function assertEditInsee(Intervenant $intervenant): bool
    {

        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_EDITION),
        ]);
    }



    protected function assertViewInsee(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),

        ]);
    }



    protected function assertEditIban(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_EDITION),
        ]);
    }



    protected function assertViewIban(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
        ]);
    }



    protected function assertEditEmployeur(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_EDITION),
        ]);
    }



    protected function assertViewEmployeur(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_VISUALISATION),
        ]);
    }



    protected function assertEditAutre1(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION),
        ]);
    }



    protected function assertViewAutre1(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION),
        ]);
    }



    protected function assertEditAutre2(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION),
        ]);
    }



    protected function assertViewAutre2(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION),
        ]);
    }



    protected function assertEditAutre3(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION),
        ]);
    }



    protected function assertViewAutre3(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION),
        ]);
    }



    protected function assertEditAutre4(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION),
        ]);
    }



    protected function assertViewAutre4(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION),
        ]);
    }



    protected function assertEditAutre5(Intervenant $intervenant): bool
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION),
        ]);
    }



    protected function assertViewAutre5(): bool
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION),
        ]);
    }



    protected function assertCanValidate(Intervenant $intervenant): bool
    {

        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);
        $isValidate         = $this->getServiceDossier()->getValidation($intervenant);

        return $this->asserts([
            (!empty($intervenantDossier->getTblDossier())) ? $intervenantDossier->getTblDossier()->getCompletude() : false,
            !$isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VALIDATION),
        ]);
    }



    protected function assertCanDevalidate(Intervenant $intervenant): bool
    {

        $isValidate = $this->getServiceDossier()->getValidation($intervenant);

        return $this->asserts([
            $isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_DEVALIDATION),
        ]);
    }



    protected function assertCanEdit(Intervenant $intervenant): bool
    {

        $isValidate = $this->getServiceDossier()->getValidation($intervenant);

        return $this->asserts([
            !$isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
        ]);
    }



    protected function assertCanSupprime(Intervenant $intervenant): bool
    {

        $isValidate = $this->getServiceDossier()->getValidation($intervenant);

        return $this->asserts([
            !$isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_SUPPRESSION),
        ]);
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
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        switch ($controller) {
            case IntervenantDossierController::class:
                switch ($action) {
                    case 'index':
                        if (!$this->assertPriv(Privileges::DOSSIER_VISUALISATION)) return false;

                        return $this->assertDossierEdition($intervenant);
                    break;
                }
            break;
        }

        return true;
    }



    protected function assertDossierEdition(?Intervenant $intervenant = null): bool
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, ?Intervenant $intervenant = null): bool
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }



    protected function assertPriv($privilege): bool
    {
        $role = $this->getRole();
        if (!$role instanceof Role) return false;

        return $role->hasPrivilege($privilege);
    }
}