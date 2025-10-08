<?php

namespace Mission\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of MissionAssertion
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SuiviAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;


    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        $entity = $this->getServiceContext()->getIntervenant();
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

        $wfEtape = $feuilleDeRoute->get(WorkflowEtape::MISSION_SAISIE_REALISE);

        if (!$wfEtape) return false;

        return $wfEtape->isAllowed();
    }



    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        if ($entity instanceof Mission || $entity instanceof Intervenant || $entity instanceof VolumeHoraireMission) {
            if (!$this->assertWorkflow($entity)) {
                return false;
            }
        }

        switch (true) {
            case $entity instanceof VolumeHoraireMission:
                switch ($privilege) {
                    case Privileges::MISSION_EDITION_REALISE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
                        return $this->assertVolumeHoraireEdition($entity);
                    case Privileges::MISSION_VALIDATION_REALISE:
                        return $this->assertVolumeHoraireValidation($entity);
                    case Privileges::MISSION_DEVALIDATION_REALISE:
                        return $this->assertVolumeHoraireDevalidation($entity);
                }
                break;
        }

        return true;
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



    protected function assertMissionEditionRealise(Mission $mission): bool
    {
        $besoinContrat = $mission->getIntervenant()->getStatut()->getContrat();

        if (!$besoinContrat) {
            return true;
        }

        return $mission->hasContrat();
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