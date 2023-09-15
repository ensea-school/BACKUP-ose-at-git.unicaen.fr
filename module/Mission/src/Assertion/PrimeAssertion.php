<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Mission\Entity\Db\OffreEmploi;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeAssertion extends AbstractAssertion implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;

    /* ---- Routage général ---- */
    public function __invoke (array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    /*protected function assertPage (array $page)
    {
        switch ($page['route']) {
            case 'offre-emploi':
                $query = 'SELECT id FROM offre_emploi WHERE histo_destruction IS NULL AND validation_id IS NOT NULL';
                $conn  = $this->getEntityManager()->getConnection();

                if (false === $conn->executeQuery($query)->fetchOne()) {
                    // Aucune offre => pas de lien
                    return false;
                }

                $role = $this->getRole();
                if (!$role) {
                    // Visible si on n'est pas connecté
                    return true;
                }
                if (!$role->getIntervenant()) {
                    //Pas visible par les gestionnaires
                    return false;
                }

                return true;
        }

        return true;
    }*/


    protected function assertController ($controller, $action = null, $privilege = null)
    {
        /* @var $role Role */
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        $entity = $role->getIntervenant();
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('intervenant');
        }

        if (!$entity) {
            return false;
        }

        return $this->assertWorkflow($entity);
    }



    protected function assertWorkflow (Intervenant $entity)
    {
        $codeEtape = WfEtape::CODE_MISSION_PRIME;

        $structure = null;
        if ($entity instanceof Intervenant) {
            /** @var Role $role */
            $role = $this->getRole();

            $structure = $role->getStructure();
        }


        $wfEtape = $this->getServiceWorkflow()->getEtape($codeEtape, $entity, $structure);

        if (!$wfEtape) return false;

        return $wfEtape->isAtteignable();
    }



    protected function haveRole ()
    {
        $role = $this->getRole();

        if ($role instanceof Role) {
            return true;
        }

        return false;
    }

}