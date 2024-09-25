<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of MissionAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SuiviAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;



    protected function assertController($controller, $action = null, $privilege = null)
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
            $entity = $this->getMvcEvent()->getParam('volumeHoraireMission');
        }
        if (!$entity) {
            return false;
        }
        return $this->assertWorkflow($entity);
    }



    protected function assertWorkflow(Mission|Intervenant|VolumeHoraireMission $entity)
    {
        $codeEtape = WfEtape::CODE_MISSION_SAISIE_REALISE;

        $structure = null;
        if ($entity instanceof Intervenant) {
            /** @var Role $role */
            $role = $this->getRole();

            $structure = $role->getStructure();
        }
        if ($entity instanceof VolumeHoraireMission) {
            $entity = $entity->getMission();
        }
        if ($entity instanceof Mission) {
            $structure = $entity->getStructure();
            $entity = $entity->getIntervenant();
        }

        $wfEtape = $this->getServiceWorkflow()->getEtape($codeEtape, $entity, $structure);

        if (!$wfEtape) return false;

        return $wfEtape->isAtteignable();
    }



    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        /** @var Role $role */
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        if (!$this->assertWorkflow($entity)){
            return false;
        }

        switch (true) {
            case $entity instanceof VolumeHoraireMission:
                switch ($privilege) {
                    case Privileges::MISSION_EDITION_REALISE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertVolumeHoraireEdition($role, $entity);
                    case Privileges::MISSION_VALIDATION_REALISE:
                        return $this->assertVolumeHoraireValidation($role, $entity);
                    case Privileges::MISSION_DEVALIDATION_REALISE:
                        return $this->assertVolumeHoraireDevalidation($role, $entity);
                }
                break;
        }

        return true;
    }



    protected function assertVolumeHoraireEdition(Role $role, VolumeHoraireMission $vhm)
    {
        return $this->asserts([
            $vhm->canEdit(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraireValidation(Role $role, VolumeHoraireMission $vhm)
    {
        return $this->asserts([
            $vhm->canValider(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraireDevalidation(Role $role, VolumeHoraireMission $vhm)
    {
        return $this->asserts([
            $vhm->canDevalider(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraire(Role $role, VolumeHoraireMission $vhm)
    {
        return $this->asserts([
            $this->assertMission($role, $vhm->getMission())
        ]);
    }



    protected function assertMission(Role $role, Mission $mission)
    {
        return $this->asserts([
            $this->assertStructure($role, $mission->getStructure())
        ]);
    }



    protected function assertStructure(Role $role, ?Structure $structure)
    {
        // Pas de structure => mission en cours de saisie => OK
        if (!$structure) {
            return true;
        }

        // Pas de périmètre structure => OK
        if (!$role->getStructure()) {
            return true;
        }

        // OK si =
        return $structure->inStructure($role->getStructure());
    }

}