<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of GestionController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionController extends AbstractActionController
{
    use \Application\Service\Traits\RoleAwareTrait,
        \Application\Service\Traits\StatutIntervenantAwareTrait,
        \Application\Service\Traits\PrivilegeAwareTrait
    ;

    /**
     *
     * @return type
     */
    public function droitsAction()
    {
        $form = $this->getFormDroitsSelection();

        $roleStatutCode = $this->params()->fromRoute('role');

        if (0 === strpos($roleStatutCode, 'r-')){
            $roleCode = substr($roleStatutCode, 2);
            $rs = $this->getServiceRole()->getRepo()->findOneBy(['code' => $roleCode]);
            /* @var $rs \Application\Entity\Db\Role */
        }elseif(0 === strpos($roleStatutCode, 's-')){
            $roleCode = substr($roleStatutCode, 2);
            $rs = $this->getServiceStatutIntervenant()->getRepo()->findOneBy(['sourceCode' => $roleCode]);
            /* @var $rs \Application\Entity\Db\StatutIntervenant */
        }else{
            $rs = null;
            $authorized = [];
        }

        if ($rs){
            $form->get('role')->setValue( $roleStatutCode );
            $formPrivileges = $this->getFormPrivileges();
        }

        return compact('privileges', 'rs', 'form');
    }



    /**
     *
     * @return \Zend\Form\Form
     */
    public function getFormDroitsSelection()
    {
        $options = [
            'roles'     => ['label' => 'Rôles (personnel)'     , 'options' => []],
            'statuts'   => ['label' => 'Statuts (intervenants)', 'options' => []],
        ];

        $roles = $this->getServiceRole()->getList();
        foreach( $roles as $role ){
            $options['roles']['options']['r-'.$role->getCode()] = (string)$role;
        }

        $statuts = $this->getServiceStatutIntervenant()->getList();
        foreach( $statuts as $statut ){
            $options['statuts']['options']['s-'.$statut->getSourceCode()] = (string)$statut;
        }

        $form = new \Zend\Form\Form;
        $form->add([
            'name'          => 'role',
            'type'          => 'Zend\Form\Element\Select',
            'attributes'    => ['onchange'=>'document.location.href=$(this).parents("form").attr("action")+"/"+$(this).val();'],
            'options'       => [
                'label'=>'Choix du rôle ou du statut à paramétrer :',
                'value_options'=> $options,
                'empty_options'=>'Sélectionner un rôle...'
            ],
        ]);
        $form->setAttribute('action', $this->url()->fromRoute(null, []));
        return $form;
    }


    public function getFormPrivileges()
    {

    }
}