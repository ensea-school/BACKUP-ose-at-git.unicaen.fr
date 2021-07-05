<?php

namespace ExportRh\Assertion;


use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Description of ExportRhAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class ExportRhAssertion extends AbstractAssertion
{

    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        $config        = $this->getMvcEvent()->getApplication()->getServiceManager()->get('Config');
        $exportRhActif = $config['export-rh']['actif'];
        //Si le module export rh n'est pas activé alors on renvoie toujours false
        if (!$exportRhActif) {
            return false;
        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {

        $role = $this->getRole();

        $config        = $this->getMvcEvent()->getApplication()->getServiceManager()->get('Config');
        $exportRhActif = $config['export-rh']['actif'];
        //Si le module export rh n'est pas activé alors on renvoie toujours false
        if (!$exportRhActif) {
            return false;
        }

        switch ($action) {
            case 'exporter':
                return $this->asserts([
                    $this->assertPrivilege(Privileges::EXPORT_RH_SYNC),
                ]);
            break;
        }

        return true;
    }
}