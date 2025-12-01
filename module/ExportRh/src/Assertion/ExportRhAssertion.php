<?php

namespace ExportRh\Assertion;


use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use ExportRh\Controller\ExportRhController;
use ExportRh\Service\ExportRhServiceAwareTrait;
use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of ExportRhAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class ExportRhAssertion extends AbstractAssertion
{
    use ExportRhServiceAwareTrait;
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;


    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);
        if (!$intervenant) {
            return false;
        }

        switch ($controller . '.' . $action) {
            case ExportRhController::class . '.exporter':
            case ExportRhController::class . '.priseEnCharge':
            case ExportRhController::class . '.renouvellement':
            case ExportRhController::class . '.synchroniser':
                return $this->assertIntervenantExportRh($intervenant);
        }

        throw new UnAuthorizedException('Action de contrôleur ' . $controller . ':' . $action . ' non traitée');
    }



    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::INTERVENANT_EXPORTER:
                        return $this->assertIntervenantExportRh($entity);
                }
                break;
        }

        throw new UnAuthorizedException('Action interdite pour la resource ' . $entity->getResourceId() . ', privilège ' . $privilege);
    }



    protected function assertIntervenantExportRh(Intervenant $intervenant): bool
    {
        //Si le module export rh n'est pas activé alors on renvoie toujours false
        $config        = Application::getInstance()->container()->get('config');
        $exportRhActif = $config['export-rh']['actif'];
        if (!$exportRhActif) {
            return false;
        }

        //Si nous ne sommes dans l'année universitaire en cours le module export reste inactif
        $anneeUniversitaireEnCours = $this->getServiceExportRh()->getAnneeUniversitaireEnCours();
        $anneeContexte             = $this->getServiceContext()->getAnnee();
        if (!($anneeContexte->getId() == $anneeUniversitaireEnCours->getId() || $anneeContexte->getId() == $anneeUniversitaireEnCours->getId() - 1)) {
            return false;
        }

        // on teste au niveau du workflow
        $wfEtape = $this
            ->getServiceWorkflow()
            ->getFeuilleDeRoute($intervenant)
            ->get(WorkflowEtape::EXPORT_RH);

        return $wfEtape?->isAllowed() ?? false;
    }

}