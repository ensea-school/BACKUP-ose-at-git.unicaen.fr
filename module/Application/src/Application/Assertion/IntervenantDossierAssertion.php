<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


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
    //Constantes utiles
    const CODE_TYPE_PERMANENT = 'P';

    use WorkflowServiceAwareTrait;
    use DossierServiceAwareTrait;

    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
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
                        return $this->assertViewIdentite($entity);
                    case self::PRIV_EDIT_ADRESSE:
                        return $this->assertEditAdresse($entity);
                    case self::PRIV_VIEW_ADRESSE:
                        return $this->assertViewAdresse($entity);
                    case self::PRIV_EDIT_CONTACT:
                        return $this->assertEditContact($entity);
                    case self::PRIV_VIEW_CONTACT:
                        return $this->assertViewContact($entity);
                    case self::PRIV_EDIT_INSEE:
                        return $this->assertEditInsee($entity);
                    case self::PRIV_VIEW_INSEE:
                        return $this->assertViewInsee($entity);
                    case self::PRIV_EDIT_IBAN:
                        return $this->assertEditIban($entity);
                    case self::PRIV_VIEW_IBAN:
                        return $this->assertViewIban($entity);
                    case self::PRIV_EDIT_EMPLOYEUR:
                        return $this->assertEditEmployeur($entity);
                    case self::PRIV_VIEW_EMPLOYEUR:
                        return $this->assertViewEmployeur($entity);
                    case self::PRIV_EDIT_AUTRE1:
                        return $this->assertEditAutre1($entity);
                    case self::PRIV_VIEW_AUTRE1:
                        return $this->assertViewAutre1($entity);
                    case self::PRIV_EDIT_AUTRE2:
                        return $this->assertEditAutre2($entity);
                    case self::PRIV_VIEW_AUTRE2:
                        return $this->assertViewAutre2($entity);
                    case self::PRIV_EDIT_AUTRE3:
                        return $this->assertEditAutre3($entity);
                    case self::PRIV_VIEW_AUTRE3:
                        return $this->assertViewAutre3($entity);
                    case self::PRIV_EDIT_AUTRE4:
                        return $this->assertEditAutre4($entity);
                    case self::PRIV_VIEW_AUTRE4:
                        return $this->assertViewAutre4($entity);
                    case self::PRIV_EDIT_AUTRE5:
                        return $this->assertEditAutre5($entity);
                    case self::PRIV_VIEW_AUTRE5:
                        return $this->assertViewAutre5($entity);
                    case self::PRIV_CAN_VALIDE:
                        return $this->assertCanValidate($entity);
                    case self::PRIV_CAN_DEVALIDE:
                        return $this->assertCanDevalidate($entity);
                }
            break;
        }
    }



    public function assertPrivilege($privilege, $subPrivilege = null)
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        switch ($privilege) {
            case self::PRIV_VIEW_IDENTITE:
                return $this->assertViewIdentite();
            break;
        }
    }



    protected function assertEditIdentite(Intervenant $intervenant)
    {

        //rajouter test si dossier valider ou non
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_SUITE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_SUITE_EDITION),
        ]);
    }



    protected function assertViewIdentite()
    {
        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_SUITE_VISUALISATION),
        ]);
    }



    protected function assertEditAdresse(Intervenant $intervenant)
    {

        //rajouter test si dossier valider ou non
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_EDITION),
        ]);
    }



    protected function assertViewAdresse()
    {
        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION),
        ]);
    }



    protected function assertEditContact(Intervenant $intervenant)
    {

        //rajouter test si dossier valider ou non
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_EDITION),
        ]);
    }



    protected function assertViewContact()
    {
        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CONTACT_VISUALISATION),
        ]);
    }



    protected function assertEditInsee(Intervenant $intervenant)
    {
        $statut              = $intervenant->getStatut();
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();

        //On affiche le fieldset INSEE uniquement si on a le droit visualisation et que l'on est vacataire
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_EDITION)
            //($typeIntervenantCode != self::CODE_TYPE_PERMANENT)
        ]);
    }



    protected function assertViewInsee(Intervenant $intervenant)
    {
        $statut              = $intervenant->getStatut();
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();

        //On affiche le fieldset INSEE uniquement si on a le droit visualisation et que l'on est vacataire
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),
            //($typeIntervenantCode != self::CODE_TYPE_PERMANENT)
        ]);
    }



    protected function assertEditIban(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_EDITION)
            //($typeIntervenantCode != self::CODE_TYPE_PERMANENT)
        ]);
    }



    protected function assertViewIban(Intervenant $intervenant)
    {
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();

        ////On affiche le fieldset IBAN uniquement si on a le droit visualisation et que l'on est vacataire
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
            ($typeIntervenantCode != self::CODE_TYPE_PERMANENT),
        ]);
    }



    protected function assertEditEmployeur(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_EDITION),
        ]);
    }



    protected function assertViewEmployeur(Intervenant $intervenant)
    {
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();

        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EMPLOYEUR_VISUALISATION),
        ]);
    }



    protected function assertEditAutre1(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_EDITION),
        ]);
    }



    protected function assertViewAutre1(Intervenant $intervenant)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_1_VISUALISATION),
        ]);
    }



    protected function assertEditAutre2(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_EDITION),
        ]);
    }



    protected function assertViewAutre2(Intervenant $intervenant)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_2_VISUALISATION),
        ]);
    }



    protected function assertEditAutre3(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_EDITION),
        ]);
    }



    protected function assertViewAutre3(Intervenant $intervenant)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_3_VISUALISATION),
        ]);
    }



    protected function assertEditAutre4(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_EDITION),
        ]);
    }



    protected function assertViewAutre4(Intervenant $intervenant)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_4_VISUALISATION),
        ]);
    }



    protected function assertEditAutre5(Intervenant $intervenant)
    {
        return $this->asserts([
            !$this->getServiceDossier()->getValidation($intervenant),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_EDITION),
        ]);
    }



    protected function assertViewAutre5(Intervenant $intervenant)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_CHAMP_AUTRE_5_VISUALISATION),
        ]);
    }



    protected function assertCanValidate(Intervenant $intervenant)
    {

        $completudeDossier = $this->getServiceDossier()->isComplete($intervenant);
        $isValidate        = $this->getServiceDossier()->getValidation($intervenant);


        return $this->asserts([
            $completudeDossier['dossier'],
            !$isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VALIDATION),
        ]);

        return $isComplete;
    }



    protected function assertCanDevalidate(Intervenant $intervenant)
    {

        $isComplete = $this->getServiceDossier()->isComplete($intervenant);
        $isValidate = $this->getServiceDossier()->getValidation($intervenant);

        return $this->asserts([
            $isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VALIDATION),
        ]);

        return $isComplete;
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
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        switch ($controller) {
            case "Application\Controller\IntervenantDossier":
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



    protected function assertDossierEdition(Intervenant $intervenant = null)
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant)) {
            return false;
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



    protected function assertPriv($privilege)
    {
        $role = $this->getRole();
        if (!$role instanceof Role) return false;

        return $role->hasPrivilege($privilege);
    }
}