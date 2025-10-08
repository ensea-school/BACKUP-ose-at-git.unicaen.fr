<?php

namespace Agrement\Assertion;

use Agrement\Entity\Db\Agrement;
use Agrement\Entity\Db\TblAgrement;
use Agrement\Entity\Db\TypeAgrement;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Contrat\Service\TblContratServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Utilisateur\Acl\Role;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of AgrementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementAssertion extends AbstractAssertion
{
    use TblContratServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;

    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof TblAgrement:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                        return $this->assertTblAgrementSaisie($entity);
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertTblAgrementSuppression($entity);

                }
                break;
            case $entity instanceof Agrement:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                        return $this->assertAgrementSaisie($entity);
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertAgrementSuppression($entity);
                }
                break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION:
                    case Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION:
                    case Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION:
                        return $this->assertStructureSaisie($entity);
                }
                break;
        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        $typeAgrement = $this->getMvcEvent()->getParam('typeAgrement');
        /* @var $typeAgrement TypeAgrement */

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch ($action) {
            case 'lister':
            case 'voir':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeVisualisation());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
                break;

            case 'ajouter':
            case 'modifier':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeEdition());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
                break;

            case 'index':
            case 'saisir-lot':
                if ($this->getServiceContext()->getIntervenant()) return false; // un intervenant ne peut pas voir ça...
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeVisualisation());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
                break;

            case 'supprimer':
                if ($typeAgrement) {
                    $resource = Privileges::getResourceId($typeAgrement->getPrivilegeSuppression());
                    if (!$this->isAllowed($resource)) return false;
                    if ($intervenant && !$this->assertTypeAgrementVisualisation($typeAgrement, $intervenant)) return false;
                }
                break;
        }

        return true;
    }



    protected function assertPage(array $page): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        if ($this->getServiceContext()->getIntervenant() && str_starts_with($page['route'], 'gestion/')) {
            return false;
        }

        if ($page['route'] == 'gestion/agrement'){
            return $this->authorize->isAllowedPrivilege(Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION)
                || $this->authorize->isAllowedPrivilege(Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION);
        }

        if ($page['route'] == 'gestion/agrement/conseil-restreint'){
            return $this->authorize->isAllowedPrivilege(Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION);
        }

        if ($page['route'] == 'gestion/agrement/conseil-academique'){
            return $this->authorize->isAllowedPrivilege(Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION);
        }

        if (!str_starts_with($page['route'], 'intervenant/agrement')){
            return false; // page inconnue => on bloque par sécurité
        }

        if (!$intervenant){
            return false;
        }

        $wfEtape   = null;
        $privilege = null;
        if (str_contains($page['route'], 'conseil-restreint')) {
            $wfEtape   = WorkflowEtape::CONSEIL_RESTREINT;
            $privilege = Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION;
        } elseif (str_contains($page['route'], 'conseil-academique')) {
            $wfEtape   = WorkflowEtape::CONSEIL_ACADEMIQUE;
            $privilege = Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION;
        }

        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        return $feuilleDeRoute->get($wfEtape)?->isAllowed() ?? false;
    }



    protected function assertTblAgrementSaisie(TblAgrement $entity): bool
    {
        /* Si c'est pour agréer et que le workflow l'interdit alors non! */
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($entity->getIntervenant());
        $wfEtape = $feuilleDeRoute->get($entity->getTypeAgrement()->getCode());

        if (!$wfEtape->isAllowed()){
            return false;
        }

        if ($structure = $entity->getStructure()) {
            return $this->assertStructureSaisie($structure);
        }
        return true;
    }



    protected function assertTypeAgrementVisualisation(TypeAgrement $typeAgrement, Intervenant $intervenant): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $wfEtape = $feuilleDeRoute->get($typeAgrement->getWorkflowEtapeCode());

        return $wfEtape && $wfEtape->isAllowed();
    }



    protected function assertAgrementSaisie(Agrement $entity): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($entity->getIntervenant());
        $wfEtape = $feuilleDeRoute->get($entity->getType()->getWorkflowEtapeCode());

        if (!$wfEtape->isAllowed()) {
            return false;
        }

        if ($structure = $entity->getStructure()) {
            return $this->assertStructureSaisie($structure);
        }

        return true;
    }



    protected function assertStructureSaisie(Structure $entity): bool
    {
        if ($roleStructure = $this->getServiceContext()->getStructure()) {
            if (!$entity->inStructure($roleStructure)) return false; // pas d'édition pour les copains
        }

        return true;
    }



    private function assertTblAgrementSuppression(TblAgrement $entity): bool
    {
        if (!$entity->getAgrement()){
            return false;
        }

        return $this->assertAgrementSuppression($entity->getAgrement());
    }



    private function assertAgrementSuppression(Agrement $entity): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($entity->getIntervenant());
        $wfEtape = $feuilleDeRoute->get($entity->getType()->getWorkflowEtapeCode());

        if (!$wfEtape->isAllowed()){
            return false;
        }

        $tblContrat              = $this->getServiceTblContrat();
        $structureContractualise = $tblContrat->getStructureContractualise($entity->getIntervenant());
        $ids                     = array_column($structureContractualise, 'id');

        if ($entity->getStructure() != NULL && in_array($entity->getStructure()->getId(), $ids)) {
            return false;
        } else {
            if ($structure = $entity->getStructure()) {
                return $this->assertStructureSaisie($structure);
            }
        }

        return true;
    }

}