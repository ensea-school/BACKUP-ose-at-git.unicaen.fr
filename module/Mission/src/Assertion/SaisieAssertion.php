<?php

namespace Mission\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
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
    use ContextServiceAwareTrait;


    protected function assertController(string $controller, ?string $action): bool
    {
        $entity = $this->getServiceContext()->getIntervenant();
        if (!$entity) {
            $entity = $this->getParam(Intervenant::class);
        }
        if (!$entity) {
            $entity = $this->getParam(Mission::class);
        }
        if (!$entity) {
            $entity = $this->getParam(VolumeHoraireMission::class);
        }
        if (!$entity) {
            return false;
        }
        return $this->assertWorkflow($entity);
    }



    protected function assertWorkflow(Mission|Intervenant|VolumeHoraireMission $entity): bool
    {
        if ($entity instanceof Intervenant) {
            $intervenant = $entity;
            $structure = $this->getServiceContext()->getStructure();
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
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Mission:
                switch ($privilege) {
                    case self::CAN_ADD_HEURES: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertMissionAddHeures($entity);
                    case Privileges::MISSION_EDITION: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertMissionEdition($entity);
                    case Privileges::MISSION_VALIDATION:
                        return $this->assertMissionValidation($entity);
                    case Privileges::MISSION_DEVALIDATION:
                        return $this->assertMissionDevalidation($entity);
                }
                break;

            case $entity instanceof VolumeHoraireMission:
                switch ($privilege) {
                    case Privileges::MISSION_EDITION: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertVolumeHoraireEdition($entity);
                    case Privileges::MISSION_VALIDATION:
                        return $this->assertVolumeHoraireValidation($entity);
                    case Privileges::MISSION_DEVALIDATION:
                        return $this->assertVolumeHoraireDevalidation($entity);
                }
                break;
        }

        return true;
    }



    protected function assertMissionAddHeures(Mission $mission): bool
    {
        return $this->asserts([
            $mission->canAddHeures(),
            $this->assertMission($mission),
        ]);
    }



    protected function assertMissionEdition(Mission $mission): bool
    {
        return $this->asserts([
            $mission->canSaisie(),
            $this->assertMission($mission),
        ]);
    }



    protected function assertMissionValidation(Mission $mission): bool
    {
        return $this->asserts([
            $mission->canValider(),
            $this->assertMission($mission),
        ]);
    }



    protected function assertMissionDevalidation(Mission $mission): bool
    {
        return $this->asserts([
            $mission->canDevalider(),
            $this->assertMission($mission),
        ]);
    }



    protected function assertVolumeHoraireEdition(VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canEdit(),
            $this->assertVolumeHoraire($vhm),
        ]);
    }



    protected function assertVolumeHoraireValidation(VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canValider(),
            $this->assertVolumeHoraire($vhm),
        ]);
    }



    protected function assertVolumeHoraireDevalidation(VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $vhm->canDevalider(),
            $this->assertVolumeHoraire($vhm),
        ]);
    }



    protected function assertVolumeHoraire(VolumeHoraireMission $vhm): bool
    {
        return $this->asserts([
            $this->assertMission($vhm->getMission())
        ]);
    }



    protected function assertMission(Mission $mission): bool
    {
        return $this->asserts([
            $this->assertStructure($mission->getStructure())
        ]);
    }



    protected function assertStructure(?Structure $structure): bool
    {
        // Pas de structure => mission en cours de saisie => OK
        if (!$structure) {
            return true;
        }

        // Pas de périmètre structure => OK
        if (!$this->getServiceContext()->getStructure()) {
            return true;
        }

        // OK si =
        return $structure->inStructure($this->getServiceContext()->getStructure());
    }
}