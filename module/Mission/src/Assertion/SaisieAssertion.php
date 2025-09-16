<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of SaisieAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SaisieAssertion extends AbstractAssertion
{
    const CAN_ADD_HEURES = 'can-add-heures';

    use WorkflowServiceAwareTrait;


    protected function assertController($controller, $action = null, $privilege = null): bool
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
            $entity = $this->getMvcEvent()->getParam('mission');
        }
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('volumeHoraireMission');
        }
        if (!$entity) {
            return false;
        }
        return $this->assertWorkflow($entity);
    }



    protected function assertWorkflow(Mission|Intervenant|VolumeHoraireMission $entity): bool
    {
        if ($entity instanceof Intervenant) {
            $role = $this->getRole();
            $intervenant = $entity;
            $structure = $role->getStructure();
        }
        if ($entity instanceof VolumeHoraireMission) {
            $entity = $entity->getMission();
            $intervenant = $entity->getIntervenant();
            $structure = $entity->getStructure();
        }
        if ($entity instanceof Mission) {
            $intervenant = $entity->getIntervenant();
            $structure = $entity->getStructure();
        }

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant, $structure);

        $wfEtape = $feuilleDeRoute->get(WorkflowEtape::MISSION_SAISIE);

        if (!$wfEtape) return false;

        return $wfEtape->isAllowed();
    }



    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        /** @var Role $role */
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Mission:
                switch ($privilege) {
                    case self::CAN_ADD_HEURES: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertMissionAddHeures($role, $entity);
                    case Privileges::MISSION_EDITION: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertMissionEdition($role, $entity);
                    case Privileges::MISSION_VALIDATION:
                        return $this->assertMissionValidation($role, $entity);
                    case Privileges::MISSION_DEVALIDATION:
                        return $this->assertMissionDevalidation($role, $entity);
                }
                break;

            case $entity instanceof VolumeHoraireMission:
                switch ($privilege) {
                    case Privileges::MISSION_EDITION: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertVolumeHoraireEdition($role, $entity);
                    case Privileges::MISSION_VALIDATION:
                        return $this->assertVolumeHoraireValidation($role, $entity);
                    case Privileges::MISSION_DEVALIDATION:
                        return $this->assertVolumeHoraireDevalidation($role, $entity);
                }
                break;
        }

        return true;
    }



    protected function assertMissionAddHeures(Role $role, Mission $mission): bool
    {
        return $this->asserts([
            $mission->canAddHeures(),
            $this->assertMission($role, $mission),
        ]);
    }



    protected function assertMissionEdition(Role $role, Mission $mission): bool
    {
        return $this->asserts([
            $mission->canSaisie(),
            $this->assertMission($role, $mission),
        ]);
    }



    protected function assertMissionValidation(Role $role, Mission $mission): bool
    {
        return $this->asserts([
            $mission->canValider(),
            $this->assertMission($role, $mission),
        ]);
    }



    protected function assertMissionDevalidation(Role $role, Mission $mission): bool
    {
        return $this->asserts([
            $mission->canDevalider(),
            $this->assertMission($role, $mission),
        ]);
    }



    protected function assertVolumeHoraireEdition(Role $role, VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canEdit(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraireValidation(Role $role, VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canValider(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraireDevalidation(Role $role, VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canDevalider(),
            $this->assertVolumeHoraire($role, $vhm),
        ]);
    }



    protected function assertVolumeHoraire(Role $role, VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $this->assertMission($role, $vhm->getMission())
        ]);
    }



    protected function assertMission(Role $role, Mission $mission): bool
    {
        return $this->asserts([
            $this->assertStructure($role, $mission->getStructure())
        ]);
    }



    protected function assertStructure(Role $role, ?Structure $structure): bool
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