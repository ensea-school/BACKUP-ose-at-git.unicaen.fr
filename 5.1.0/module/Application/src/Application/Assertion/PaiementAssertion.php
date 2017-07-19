<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\Structure;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Application\Acl\Role;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;

/**
 * Description of PaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementAssertion extends AbstractAssertion
{
    use TypeValidationAwareTrait;
    use ValidationAwareTrait;
    use ServiceAwareTrait;
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use WorkflowServiceAwareTrait;


    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }


    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof MiseEnPaiement:
                switch ($privilege) {
                    case Privileges::MISE_EN_PAIEMENT_DEMANDE:
                        return $this->assertMiseEnPaiementDemande($role, $entity);
                }
            break;
            case $entity instanceof ServiceAPayerInterface:
                switch ($privilege) {
                    case Privileges::MISE_EN_PAIEMENT_DEMANDE:
                        return $this->assertServiceAPayerDemande($role, $entity);
                }
            break;
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        // Si c'est bon alors on affine...
        switch ($action) {
            case 'demandemiseenpaiement':
                return $this->assertEtapeAtteignable(WfEtape::CODE_DEMANDE_MEP, $intervenant);
            break;
            case 'visualisationmiseenpaiement':

            break;
            case 'editionmiseenpaiement':

            break;
            case 'etatpaiement':
                if ($role->getIntervenant()) return false; // pas pour les intervenants
            break;
            case  'miseenpaiement':

            break;
        }

        return true;
    }



    protected function assertPage(array $page)
    {
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertMiseEnPaiementDemande(Role $role, MiseEnPaiement $miseEnPaiement)
    {
        if (! $this->asserts([
            !$miseEnPaiement->getValidation(),
        ])) return false;

        if ($serviceAPayer = $miseEnPaiement->getServiceAPayer()) {
            return $this->assertServiceAPayerDemande($role, $serviceAPayer);
        } else {
            return true; // pas assez d'éléments pour statuer
        }
    }



    protected function assertServiceAPayerDemande(Role $role, ServiceAPayerInterface $serviceAPayer)
    {
        $oriStructure  = $role->getStructure();
        $destStructure = $serviceAPayer->getStructure();

        return $this->asserts([
            $this->assertEtapeAtteignable(WfEtape::CODE_DEMANDE_MEP, $serviceAPayer->getIntervenant(), $destStructure),
            empty($oriStructure) || empty($destStructure) || $oriStructure === $destStructure
        ]);
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant = null, Structure $structure = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant, $structure);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}