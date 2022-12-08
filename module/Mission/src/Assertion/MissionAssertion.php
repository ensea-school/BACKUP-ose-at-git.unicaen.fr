<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MissionAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionAssertion extends AbstractAssertion
{

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
            /*
                        if (
                            $intervenant
                            && $role
                            && $role->getStructure()
            //                && (WfEtape::CODE_SERVICE_VALIDATION == $etape || WfEtape::CODE_SERVICE_VALIDATION_REALISE == $etape)
                        ) { // dans ce cas ce n'est pas le WF qui agit, mais on voit la validation dès qu'on a des services directement,
                            // car on peut très bien avoir à visualiser cette page sans pour autant avoir de services à soi à valider!!
                            return $this->assertHasMission($intervenant, $role->getStructure(), $etape, $role);
                        } else {
                            if (!$this->getAssertionService()->assertEtapeAtteignable($etape, $intervenant)) {
                                return false;
                            }
                        }*/
        }

        /*
                if ($intervenant && isset($page['route'])) {
                    switch ($page['route']) {
                        case 'intervenant/validation/enseignement/prevu':
                            return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_PREVU_VISUALISATION);
                        case 'intervenant/validation/enseignement/realise':
                            return $this->assertEntity($intervenant, Privileges::ENSEIGNEMENT_REALISE_VISUALISATION);
                        case 'intervenant/enseignement-prevu':
                            return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_PREVU);
                        break;
                        case 'intervenant/enseignement-realise':
                            return $this->assertPageEnseignements($role, $intervenant, TypeVolumeHoraire::CODE_REALISE);
                        break;
                    }
                }
        */

        return true;
    }



    /**
     * Exemple
     */
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {

//        switch (true) {
//            case $entity instanceof VotreEntite:
//                switch ($privilege) {
//                    case Privileges::VOTRE_PRIVILEGE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
//                        return $this->assertVotreAssertion($role, $entity);
//                }
//                break;
//        }

        return true;
    }

    /* Vos autres tests */

}