<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
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
    const PRIV_CAN_VALIDE     = 'dossier-peut-valider';
    const PRIV_CAN_DEVALIDE   = 'dossier-peut-devalider';
    //Constantes utiles
    const CODE_TYPE_PERMANENT = 'P';

    use WorkflowServiceAwareTrait;
    use IntervenantDossierServiceAwareTrait;

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
            !$this->getServiceIntervenantDossier()->getValidation($intervenant),
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
            !$this->getServiceIntervenantDossier()->getValidation($intervenant),
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
            !$this->getServiceIntervenantDossier()->getValidation($intervenant),
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
            !$this->getServiceIntervenantDossier()->getValidation($intervenant),
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
            !$this->getServiceIntervenantDossier()->getValidation($intervenant),
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



    protected function assertCanValidate(Intervenant $intervenant)
    {

        $isComplete = $this->getServiceIntervenantDossier()->isComplete($intervenant);
        $isValidate = $this->getServiceIntervenantDossier()->getValidation($intervenant);

        return $this->asserts([
            $isComplete,
            !$isValidate,
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VALIDATION),
        ]);

        return $isComplete;
    }



    protected function assertCanDevalidate(Intervenant $intervenant)
    {

        $isComplete = $this->getServiceIntervenantDossier()->isComplete($intervenant);
        $isValidate = $this->getServiceIntervenantDossier()->getValidation($intervenant);

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