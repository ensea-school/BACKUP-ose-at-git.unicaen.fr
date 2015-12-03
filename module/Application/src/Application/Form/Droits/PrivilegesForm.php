<?php

namespace Application\Form\Gestion;

use Zend\Form;
use UnicaenAuth\Entity\Db\Privilege;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\Role;
use Application\Entity\Db\StatutIntervenant;

/**
 * Description of PrivilegesForm
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegesForm extends Form\Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait
    ;


    public function init()
    {
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value'  => 'Enregistrer',
                'class'  => 'btn btn-primary',
            ],
        ]);
    }

    /**
     *
     * @param Privilege[] $privileges
     * @param \Application\Entity\Db\Role|\Application\Entity\Db\StatutIntervenant $statutRole
     *
     */
    public function addPrivileges( $privileges, $statutRole )
    {
        $this->privileges = $privileges;

        foreach ($privileges as $privilege){
            $this->add([
                'name'       => $privilege->getCode(),
                'type'       => 'Checkbox',
                'options'    => [
                    'label'  => $privilege->getLibelle()
                ],
                'attributes' => ['id' => $privilege->getId()],
            ]);

            if ($statutRole instanceof Role && $privilege->getRole()->contains($statutRole)){
                $this->get($privilege->getCode())->setValue(true);
            }elseif($statutRole instanceof StatutIntervenant && $privilege->getStatut()->contains($statutRole)){
                $this->get($privilege->getCode())->setValue(true);
            }
        }
        return $this;
    }

}
