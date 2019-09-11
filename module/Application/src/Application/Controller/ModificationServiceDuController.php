<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ModificationServiceDu;
use Application\Form\Intervenant\Traits\ModificationServiceDuFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ModificationServiceDuServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Doctrine\DBAL\DBALException;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use RuntimeException;
use UnicaenApp\View\Model\CsvModel;

/**
 * Description of IntervenantController
 *
 */
class ModificationServiceDuController extends AbstractController
{
    use ContextServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ModificationServiceDuFormAwareTrait;
    use ModificationServiceDuServiceAwareTrait;
    use WorkflowServiceAwareTrait;



    public function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            ModificationServiceDu::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        if (!$intervenant){
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $canEdit     = $this->isAllowed($intervenant, Privileges::MODIF_SERVICE_DU_EDITION);

        // NB: patch pour permettre de vider toutes les modifs de service dû
        if ($canEdit && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            if (empty($data['fs']['modificationServiceDu'])) {
                foreach ($intervenant->getModificationServiceDu() as $sr) {
                    $sr->setHistoDestruction(new \DateTime());
                    $this->em()->persist($sr);
                    $this->em()->flush();
                }
                $this->em()->refresh($intervenant);
            }
        }

        $form = $this->getFormIntervenantModificationServiceDu();
        $fs   = $form->getFieldsets()['fs'];
        $form->setAttribute('action', $this->getRequest()->getRequestUri());
        $form->bind($intervenant);

        $variables = [
            'form'        => $form,
            'intervenant' => $intervenant,
            'title'       => "Modifications de service dû <small>$intervenant</small>",
            'canEdit'     => $canEdit,
        ];

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (empty($data['fs']['modificationServiceDu'])) {
                $data['fs']['modificationServiceDu'] = [];
            }

            $form->setData($data);
            if ($canEdit && $form->isValid()) {
                try {
                    $this->em()->flush();
                    $this->updateTableauxBord($intervenant);
                    $this->flashMessenger()->addSuccessMessage(sprintf("Modifications de service dû de $intervenant enregistrées avec succès."));
                    $this->redirect()->toRoute(null, [], [], true);
                } catch (DBALException $exc) {
                    $exception              = new RuntimeException("Impossible d'enregistrer les modifications de service dû.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
                }
            }
        }

        return $variables;
    }



    public function exportCsvAction()
    {
        $annee = $this->getServiceContext()->getAnnee();
        $role  = $this->getServiceContext()->getSelectedIdentityRole();

        $this->em()->getFilters()->enable('historique')->init([
            ModificationServiceDu::class,
            Intervenant::class,
        ]);

        $data = $this->getServiceModificationServiceDu()->getExportCsvData( $annee, $role->getStructure() );

        $csvModel = new CsvModel();
        $csvModel->setHeader($data['head']);
        $csvModel->addLines($data['data']);
        $csvModel->setFilename('modifications-service-du-' . $annee . '.csv');

        return $csvModel;
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'formule',
        ], $intervenant);
    }
}