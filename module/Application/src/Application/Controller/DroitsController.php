<?php

namespace Application\Controller;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Role;
use Application\Form\Droits\Traits\AffectationFormAwareTrait;
use Application\Service\Traits\AffectationAwareTrait;
use Application\Service\Traits\PersonnelAwareTrait;
use Application\Service\Traits\RoleAwareTrait;
use Application\Service\Traits\SourceAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Form\Droits\Traits\RoleFormAwareTrait;
use UnicaenAuth\Service\Traits\PrivilegeServiceAwareTrait;
use Application\Entity\Db\StatutIntervenant;
use UnicaenAuth\Entity\Db\Privilege;
use Application\Exception\DbException;

/**
 * Description of DroitsController
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DroitsController extends AbstractController
{
    use RoleAwareTrait;
    use StatutIntervenantAwareTrait;
    use PrivilegeServiceAwareTrait;
    use AffectationAwareTrait;
    use StructureAwareTrait;
    use PersonnelAwareTrait;
    use SourceAwareTrait;
    use RoleFormAwareTrait;
    use AffectationFormAwareTrait;



    /**
     *
     * @return type
     */
    public function indexAction()
    {
        return [];
    }



    public function rolesAction()
    {
        $qb    = $this->getServiceRole()->finderByHistorique();
        $roles = $this->getServiceRole()->getList($qb);

        return compact('roles');
    }



    public function roleEditionAction()
    {
        $role   = $this->context()->roleFromRoute();
        $errors = [];

        $form = $this->getFormDroitsRole();
        if (empty($role)) {
            $title = 'Création d\'un nouveau rôle';
            $role  = $this->getServiceRole()->newEntity();
            $form->setObject($role);
        } else {
            $title = 'Édition du rôle';
            $form->bind($role);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceRole()->save($role);
                    $form->get('id')->setValue($role->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
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

        $title  = "Suppression du rôle";
        $form   = $this->makeFormSupprimer( function() use ($role){
            $this->getServiceRole()->delete($role);
        } );

        return compact('role', 'title', 'form');
    }



    public function privilegesAction()
    {
        $filters = [];
        if ($categorieFilter = $this->params()->fromQuery('cat')) {
            $filters['cat'] = $categorieFilter;
        }
        if ($rsFilter = $this->params()->fromQuery('rs')){
            $filters['rs'] = $rsFilter;
        }

        $ps         = $this->getServicePrivilege()->getList();
        $privileges = [];
        foreach ($ps as $privilege) {
            $categorie = $privilege->getCategorie();

            $ok = true;
            if ($categorieFilter && $categorieFilter != $categorie->getCode()) $ok = false;

            if ($ok) {
                if (!isset($privileges[$categorie->getCode()])) {
                    $privileges[$categorie->getCode()] = [
                        'categorie'     => $categorie,
                        'categorieLink' => $this->url()->fromRoute(null, [], ['query' => $filters+['cat'=>$categorie->getCode()]], true),
                        'privileges'    => [],
                    ];
                }
                $privileges[$categorie->getCode()]['privileges'][] = $privilege;
            }
        }

        if ($rsFilter == 'r' || !$rsFilter) {
            $qb    = $this->getServiceRole()->finderByHistorique();
            $roles = $this->getServiceRole()->getList($qb);
        }else{
            $roles = [];
        }

        if ($rsFilter == 's' || !$rsFilter) {
            $qb      = $this->getServiceStatutIntervenant()->finderByHistorique();
            $statuts = $this->getServiceStatutIntervenant()->getList($qb);
        }else{
            $statuts = [];
        }

        return compact('privileges', 'roles', 'statuts', 'filters');
    }



    public function privilegesModifierAction()
    {
        $role      = $this->context()->roleFromPost();
        $statut    = $this->context()->statutIntervenantFromPost('statut');
        $privilege = $this->getServicePrivilege()->get($this->params()->fromPost('privilege'));
        $action    = $this->params()->fromPost('action');

        switch ($action) {
            case 'accorder':
                if ($role) $this->roleAddPrivilege($role, $privilege);
                if ($statut) $this->statutAddPrivilege($statut, $privilege);
                break;
            case 'refuser':
                if ($role) $this->roleRemovePrivilege($role, $privilege);
                if ($statut) $this->statutRemovePrivilege($statut, $privilege);
                break;
        }

        return compact('role', 'statut', 'privilege');
    }



    /* fonctions pour pallier un pb dans l'enregistrement via doctrine... (à revoir) */
    private function roleAddPrivilege(Role $role, Privilege $privilege)
    {
        $sql = "INSERT INTO ROLE_PRIVILEGE (role_id, privilege_id) VALUES (" . $role->getId() . ", " . $privilege->getId() . ")";
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($role);
    }



    private function roleRemovePrivilege(Role $role, Privilege $privilege)
    {
        $sql = "DELETE ROLE_PRIVILEGE WHERE role_id = " . $role->getId() . " AND privilege_id = " . $privilege->getId();
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($role);
    }



    private function statutAddPrivilege(StatutIntervenant $statut, Privilege $privilege)
    {
        $sql = "INSERT INTO STATUT_PRIVILEGE (statut_id, privilege_id) VALUES (" . $statut->getId() . ", " . $privilege->getId() . ")";
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($statut);
    }



    private function statutRemovePrivilege(StatutIntervenant $statut, Privilege $privilege)
    {
        $sql = "DELETE STATUT_PRIVILEGE WHERE statut_id = " . $statut->getId() . " AND privilege_id = " . $privilege->getId();
        $this->em()->getConnection()->exec($sql);
        $this->em()->refresh($privilege);
        $this->em()->refresh($statut);
    }



    public function affectationsAction()
    {
        $tri = $this->params()->fromQuery('tri', 'structure');

        $serviceAffectations = $this->getServiceAffectation();

        list($qb, $alias) = $serviceAffectations->initQuery();

        $serviceAffectations->join($this->getServiceRole(), $qb, 'role', true);
        $serviceAffectations->join($this->getServicePersonnel(), $qb, 'personnel', true);
        $serviceAffectations->join($this->getServiceSource(), $qb, 'source', true);
        $serviceAffectations->leftJoin($this->getServiceStructure(), $qb, 'structure', true);
        $serviceAffectations->finderByHistorique($qb);

        /* @var $qb \Doctrine\ORM\QueryBuilder */

        switch ($tri) {
            case 'structure':
                $this->getServiceStructure()->orderBy($qb);
                $this->getServiceRole()->orderBy($qb);
                $this->getServicePersonnel()->orderBy($qb);
                $this->getServiceSource()->orderBy($qb);
                break;
            case 'role':
                $this->getServiceRole()->orderBy($qb);
                $this->getServicePersonnel()->orderBy($qb);
                $this->getServiceStructure()->orderBy($qb);
                $this->getServiceSource()->orderBy($qb);
                break;
            case 'personnel':
                $this->getServicePersonnel()->orderBy($qb);
                $this->getServiceStructure()->orderBy($qb);
                $this->getServiceRole()->orderBy($qb);
                $this->getServiceSource()->orderBy($qb);
                break;
            case 'source':
                $this->getServiceSource()->orderBy($qb);
                $this->getServiceStructure()->orderBy($qb);
                $this->getServiceRole()->orderBy($qb);
                $this->getServicePersonnel()->orderBy($qb);
                break;
        }

        $affectations = $serviceAffectations->getList($qb);

        return compact('affectations', 'tri');
    }



    public function affectationEditionAction()
    {
        $affectation = $this->context()->affectationFromRoute();
        /* @var $affectation Affectation */
        $errors = [];

        $form = $this->getFormDroitsAffectation();
        if (empty($affectation)) {
            $title       = 'Création d\'une nouvelle affectation';
            $affectation = $this->getServiceAffectation()->newEntity();
            $form->setObject($affectation);
        } else {
            $title = 'Édition de l\'affectation';
            $form->bind($affectation);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    if (!$affectation->getRole()->getPerimetre()->isComposante()) {
                        $affectation->setStructure(null);
                    }
                    $this->getServiceAffectation()->save($affectation);
                    $form->get('id')->setValue($affectation->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function affectationSuppressionAction()
    {
        $affectation = $this->getEvent()->getParam('affectation');

        $title  = "Suppression de l'affectation";

        $form = $this->makeFormSupprimer(function()use($affectation){
            $this->getServiceAffectation()->delete($affectation);
        });

        return compact('affectation', 'title', 'form');
    }



    /**
     * @param string $roleStatutCode
     *
     * @return \Zend\Form\Form
     */
    public function getFormDroitsSelection($roleStatutCode)
    {
        $options = [];
        if (empty($roleStatutCode)) {
            $options['null'] = ['label' => 'Sélection du rôle', 'options' => ['' => 'Veuillez sélectionner un rôle...']];
        }

        $options['roles']   = ['label' => 'Rôles (personnel)', 'options' => []];
        $options['statuts'] = ['label' => 'Statuts (intervenants)', 'options' => []];

        $qb    = $this->getServiceRole()->finderByHistorique();
        $roles = $this->getServiceRole()->getList($qb);
        foreach ($roles as $role) {
            $options['roles']['options']['r-' . $role->getCode()] = (string)$role;
        }

        $qb      = $this->getServiceStatutIntervenant()->finderByHistorique();
        $statuts = $this->getServiceStatutIntervenant()->getList($qb);
        foreach ($statuts as $statut) {
            $options['statuts']['options']['s-' . $statut->getSourceCode()] = (string)$statut;
        }

        $form = new \Zend\Form\Form;
        $form->add([
            'name'       => 'role',
            'type'       => 'Zend\Form\Element\Select',
            'attributes' => ['onchange' => 'document.location.href=$(this).parents("form").attr("action")+"/"+$(this).val();'],
            'options'    => [
                'label'         => 'Choix du rôle ou du statut à paramétrer :',
                'value_options' => $options,
                'empty_options' => 'Sélectionner un rôle...',
            ],
        ]);
        $form->setAttribute('action', $this->url()->fromRoute(null, []));

        $form->get('role')->setValue($roleStatutCode ?: '');

        return $form;
    }

}