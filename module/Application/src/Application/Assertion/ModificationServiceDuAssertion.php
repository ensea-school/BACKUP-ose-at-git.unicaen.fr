<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Acl\Role;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;



/**
 * Description of ModificationServiceDuAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDuAssertion extends AbstractAssertion
{
    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct()
    {
        var_dump('coucou');
    }



    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role = $this->getRole();
        if (! $role instanceof Role) return false;

        if ($entity instanceof Intervenant){
            switch ($privilege){
                case Privileges::MODIF_SERVICE_DU_EDITION:
                    return $this->assertIntervenant($entity);
                case Privileges::MODIF_SERVICE_DU_VISUALISATION:
                    return $this->assertIntervenant($entity);
            }
        }
        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {
        if ($controller == 'Application\Controller\ModificationServiceDu' && $action == 'saisir'){
            $intervenant = $this->getMvcEvent()->getParam('intervenant');
            if ($intervenant){
                return $this->assertIntervenant($intervenant);
            }
        }
        return true;
    }



    protected function assertIntervenant( Intervenant $intervenant )
    {
        return $intervenant->getStatut()->hasPrivilege(Privileges::MODIF_SERVICE_DU_ASSOCIATION);
    }
}