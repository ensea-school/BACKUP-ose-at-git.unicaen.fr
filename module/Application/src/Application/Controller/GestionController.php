<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Entity\Db\Role;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Privilege;
use Application\Exception\DbException;

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
        $qb = $this->getServiceRole()->finderByHistorique();
        $roles = $this->getServiceRole()->getList( $qb );

        return compact('roles');
    }

    public function roleEditionAction()
    {
        $role = $this->context()->roleFromRoute();
        $errors = [];

        $form = $this->getFormRole();
        if (empty($role)){
            $title = 'Création d\'un nouveau rôle';
            $role = $this->getServiceRole()->newEntity();
            $form->setObject($role);
        }else{
            $title = 'Édition du rôle';
            $form->bind($role);
        }
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceRole()->save($role);
                    $form->get('id')->setValue($role->getId()); // transmet le nouvel ID
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }

    public function roleSuppressionAction()
    {
        $role = $this->context()->mandatory()->roleFromRoute();

        $title     = "Suppression du rôle";
        $form      = new \Application\Form\Supprimer('suppr');
        $errors = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        if ($this->getRequest()->isPost()) {
            try {
                $this->getServiceRole()->delete($role);
            }catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }
        return compact('role', 'title', 'form', 'errors');
    }

    public function privilegesAction()
    {
        $ps = $this->getServicePrivilege()->getList();
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

        $qb = $this->getServiceRole()->finderByHistorique();
        $roles = $this->getServiceRole()->getList( $qb );

        $qb = $this->getServiceStatutIntervenant()->finderByHistorique();
        $statuts = $this->getServiceStatutIntervenant()->getList( $qb );
        return compact('privileges', 'roles', 'statuts');
    }


    public function privilegesModifierAction()
    {
        $role      = $this->context()->roleFromPost();
        $statut    = $this->context()->statutIntervenantFromPost('statut');
        $privilege = $this->context()->mandatory()->privilegeFromPost();
        $action    = $this->params()->fromPost('action');
        
        switch($action){
        case 'accorder':
            if ($role  ) $this->roleAddPrivilege      ($role  , $privilege);
            if ($statut) $this->statutAddPrivilege    ($statut, $privilege);
        break;
        case 'refuser':
            if ($role  ) $this->roleRemovePrivilege   ($role  , $privilege);
            if ($statut) $this->statutRemovePrivilege ($statut, $privilege);
        break;
        }
        return compact('role', 'statut', 'privilege');
    }

    /* fonctions pour pallier un pb dans l'enregistrement via doctrine... (à revoir) */
    private function roleAddPrivilege( Role $role, Privilege $privilege )
    {
        $sql = "INSERT INTO ROLE_PRIVILEGE (role_id, privilege_id) VALUES (".$role->getId().", ".$privilege->getId().")";
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($role);
    }
    
    private function roleRemovePrivilege( Role $role, Privilege $privilege )
    {
        $sql = "DELETE ROLE_PRIVILEGE WHERE role_id = ".$role->getId()." AND privilege_id = ".$privilege->getId();
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($role);
    }

    private function statutAddPrivilege( StatutIntervenant $statut, Privilege $privilege )
    {
        $sql = "INSERT INTO STATUT_PRIVILEGE (statut_id, privilege_id) VALUES (".$statut->getId().", ".$privilege->getId().")";
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($statut);
    }

    private function statutRemovePrivilege( StatutIntervenant $statut, Privilege $privilege )
    {
        $sql = "DELETE STATUT_PRIVILEGE WHERE statut_id = ".$statut->getId()." AND privilege_id = ".$privilege->getId();
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($statut);
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

        $qb = $this->getServiceRole()->finderByHistorique();
        $roles = $this->getServiceRole()->getList( $qb );
        foreach( $roles as $role ){
            $options['roles']['options']['r-'.$role->getCode()] = (string)$role;
        }

        $qb = $this->getServiceStatutIntervenant()->finderByHistorique();
        $statuts = $this->getServiceStatutIntervenant()->getList( $qb );
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
     * @return \Application\Form\Gestion\RoleForm
     */
    public function getFormRole()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('GestionRoleForm');
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