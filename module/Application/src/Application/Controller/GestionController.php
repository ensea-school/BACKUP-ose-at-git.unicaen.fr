<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Entity\Db\Role;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Privilege;

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

    public function droitsAction()
    {
        return [];
    }

    public function rolesAction()
    {
        $roles = $this->getServiceRole()->getList();

        return compact('roles');
    }

    /**
     *
     * @return type
     */
    public function privilegesAction()
    {
        $roleStatutCode = $this->params()->fromRoute('role');

        $formDroitsSelection = $this->getFormDroitsSelection( $roleStatutCode );
        $formPrivileges = null;

        if (0 === strpos($roleStatutCode, 'r-')){
            $rs = $this->getServiceRole()->getRepo()->findOneBy(['code' => substr($roleStatutCode, 2)]);
            /* @var $rs Role */
        }elseif(0 === strpos($roleStatutCode, 's-')){
            $rs = $this->getServiceStatutIntervenant()->getRepo()->findOneBy(['sourceCode' => substr($roleStatutCode, 2)]);
            /* @var $rs StatutIntervenant */
        }else{
            $rs = null;
        }

        if ($rs){
            $ps = $this->getServicePrivilege()->getList();
            /* @var $ps Privilege[] */

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                foreach( $ps as $privilege ){
                    if (isset($post[$privilege->getCode()])){
                        if ('1' === $post[$privilege->getCode()]){ // privilège coché
                            if (! $rs->getPrivilege()->contains($privilege)){
                                if ($rs instanceof Role){
                                    $sql = "INSERT INTO ROLE_PRIVILEGE (role_id, privilege_id) VALUES (".$rs->getId().", ".$privilege->getId().")";
                                }elseif($rs instanceof StatutIntervenant){
                                    $sql = "INSERT INTO STATUT_PRIVILEGE (statut_id, privilege_id) VALUES (".$rs->getId().", ".$privilege->getId().")";
                                }
                                $this->em()->getConnection()->exec($sql);
                                $this->em()->refresh($privilege);
                                $this->em()->refresh($rs);
                            }
                        }else{ // privilège décoché
                            if ($rs->getPrivilege()->contains($privilege)){
                                if ($rs instanceof Role){
                                    $sql = "DELETE ROLE_PRIVILEGE WHERE role_id = ".$rs->getId()." AND privilege_id = ".$privilege->getId();
                                }elseif($rs instanceof StatutIntervenant){
                                    $sql = "DELETE STATUT_PRIVILEGE WHERE statut_id = ".$rs->getId()." AND privilege_id = ".$privilege->getId();
                                }
                                $this->em()->getConnection()->exec($sql);
                                $this->em()->refresh($privilege);
                                $this->em()->refresh($rs);
                            }
                        }
                    }
                }
            }

            $formPrivileges = $this->getFormPrivileges()
                                   ->setAttribute('action', $this->url()->fromRoute(null, ['role' => $roleStatutCode]))
                                   ->addPrivileges( $ps, $rs );

            $privileges = [];
            foreach( $ps as $privilege ){
                $categorie = $privilege->getCategorie();
                if (! isset($privileges[$categorie->getCode()])){
                    $privileges[$categorie->getCode()] = [
                        'categorie' => $categorie,
                        'privileges' => [],
                    ];
                }
                $privileges[$categorie->getCode()]['privileges'][] = $privilege;
            }

            return compact('formDroitsSelection', 'formPrivileges', 'privileges');
        }else{
            return compact('formDroitsSelection', 'formPrivileges');
        }
    }



    /**
     * @param string $roleStatutCode
     * @return \Zend\Form\Form
     */
    public function getFormDroitsSelection( $roleStatutCode )
    {
        $options = [];
        if (empty($roleStatutCode)){
            $options['null']= ['label' => 'Sélection du rôle'     , 'options' => ['' => 'Veuillez sélectionner un rôle...']];
        }

        $options['roles']   = ['label' => 'Rôles (personnel)'     , 'options' => []];
        $options['statuts'] = ['label' => 'Statuts (intervenants)', 'options' => []];

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

        $form->get('role')->setValue( $roleStatutCode ?: '' );

        return $form;
    }

    /**
     *
     * @return \Application\Form\Gestion\PrivilegesForm
     */
    public function getFormPrivileges()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('GestionPrivilegesForm');
    }
}