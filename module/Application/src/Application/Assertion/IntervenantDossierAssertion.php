<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges; // sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of IntervenantDossierAssertion
 */
class IntervenantDossierAssertion extends AbstractAssertion
{
    //Constantes privilieges personnalisés
    const PRIV_EDIT_IDENTITE = 'dossier-edit-identite';
    const PRIV_VIEW_IDENTITE = 'dossier-view-identite';
    const PRIV_EDIT_ADRESSE = 'dossier-edit-adresse';
    const PRIV_VIEW_ADRESSE = 'dossier-view-adresse';
    const PRIV_EDIT_CONTACT = 'dossier-edit-contact';
    const PRIV_VIEW_CONTACT = 'dossier-view-contact';
    const PRIV_VIEW_IBAN   = 'dossier-voir-iban';
    const PRIV_EDIT_IBAN  = 'dossier-edit-iban';
    const PRIV_EDIT_INSEE = 'dossier-edit-insee';
    const PRIV_VIEW_INSEE = 'dossier-voir-insee';
    const PRIV_EDIT_EMPLOYEUR = 'dossier-edit-employeur';
    const PRIV_VIEW_EMPLOYEUR = 'dossier-voir-employeur';
    //Constantes utiles
    const CODE_TYPE_PERMANENT = 'P';


    use WorkflowServiceAwareTrait;

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
        ];

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case self::PRIV_VIEW_IBAN:
                        return $this->assertViewIban($entity);
                    case self::PRIV_VIEW_INSEE:
                        return $this->assertViewInsee($entity);
                    case self::PRIV_EDIT_IDENTITE:
                        return $this->assertEditIdentite($entity);
                    case self::PRIV_VIEW_IDENTITE:
                        return $this->assertViewIdentite($entity);
                    case self::PRIV_EDIT_ADRESSE:
                        return $this->assertEditAdresse($entity);
                    case self::PRIV_VIEW_ADRESSE:
                        return $this->assertViewAdresse($entity);
                    case Privileges::DOSSIER_BANQUE_EDITION:
                        return $this->assertEditIban($entity);

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



    protected function assertViewIban(Intervenant $intervenant)
    {
        $statut = $intervenant->getStatut();
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();
        ////On affiche le fieldset IBAN uniquement si on a le droit visualisation et que l'on est vacataire
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_VISUALISATION),
            ($typeIntervenantCode != self::CODE_TYPE_PERMANENT)
        ]);
    }

    protected function assertEditIban()
    {
        return $this->getRole()->hasPrivilege(Privileges::DOSSIER_BANQUE_EDITION);
    }

    protected function assertViewInsee(Intervenant $intervenant)
    {
        $statut = $intervenant->getStatut();
        $typeIntervenantCode = $intervenant->getStatut()->getTypeIntervenant()->getCode();
        //On affiche le fieldset INSEE uniquement si on a le droit visualisation et que l'on est vacataire
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_INSEE_VISUALISATION),
            ($typeIntervenantCode != self::CODE_TYPE_PERMANENT)
        ]);
    }

    protected function assertEditIdentite()
    {

        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_SUITE_EDITION)
        ]);
    }
    protected function assertViewIdentite()
    {
        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_IDENTITE_SUITE_VISUALISATION)
        ]);
    }
    protected function assertEditAdresse()
    {

        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_EDITION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_EDITION)
        ]);
    }

    protected function assertViewAdresse()
    {
        //rajouter test si dossier valider ou non
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_VISUALISATION),
            $this->getRole()->hasPrivilege(Privileges::DOSSIER_ADRESSE_VISUALISATION)
        ]);
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