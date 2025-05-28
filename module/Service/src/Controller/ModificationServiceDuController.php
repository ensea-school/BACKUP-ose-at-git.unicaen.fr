<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\DBAL\Exception;
use Intervenant\Entity\Db\Intervenant;
use Plafond\Processus\PlafondProcessusAwareTrait;
use RuntimeException;
use Service\Entity\Db\ModificationServiceDu;
use Service\Entity\Db\MotifModificationServiceDu;
use Service\Form\ModificationServiceDuFormAwareTrait;
use Service\Service\ModificationServiceDuServiceAwareTrait;
use Service\Service\MotifModificationServiceDuServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use Workflow\Service\WorkflowServiceAwareTrait;

class ModificationServiceDuController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ModificationServiceDuFormAwareTrait;
    use ModificationServiceDuServiceAwareTrait;
    use MotifModificationServiceDuServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use PlafondProcessusAwareTrait;


    public function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            ModificationServiceDu::class,
            MotifModificationServiceDu::class,
        ]);


        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $canEdit = $this->isAllowed($intervenant, Privileges::MODIF_SERVICE_DU_EDITION);

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
                $this->updateTableauxBord($intervenant);
            }
        }

        $form = $this->getFormIntervenantModificationServiceDu();
        $fs = $form->getFieldsets()['fs'];
        $form->setAttribute('action', $this->getRequest()->getRequestUri());
        $form->bind($intervenant);

        /** @var MotifModificationServiceDu[] $mds */
        $mds = $this->getServiceMotifModificationServiceDu()->getList();
        $multiplicateurs = [];
        foreach( $mds as $md ){
            $multiplicateurs[$md->getId()] = $md->getMultiplicateur();
        }

        $variables = [
            'form'            => $form,
            'intervenant'     => $intervenant,
            'title'           => "Modifications de service dû",
            'multiplicateurs' => $multiplicateurs,
            'canEdit'         => $canEdit,
        ];

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (empty($data['fs']['modificationServiceDu'])) {
                $data['fs']['modificationServiceDu'] = [];
            }else{
                foreach( $data['fs']['modificationServiceDu'] as $i => $msdData ){
                    if ('' == $msdData['commentaires']){
                        $data['fs']['modificationServiceDu'][$i]['commentaires'] = null;
                    }
                }
            }

            $form->setData($data);
            if ($canEdit && $form->isValid()) {
                try {
                    foreach ($intervenant->getModificationServiceDu() as $modificationServiceDu) {
                        $this->em()->persist($modificationServiceDu);
                        $this->em()->flush($modificationServiceDu);
                    }
                    $this->updateTableauxBord($intervenant);
                    // simple recalcul des plafonds de périmètre intervenant : pas de nécessitéc de contrôle de blocage ici
                    $this->getProcessusPlafond()->getServicePlafond()->calculer('intervenant', 'INTERVENANT_ID', $intervenant->getId());
                    $this->flashMessenger()->addSuccessMessage(sprintf("Modifications de service dû de $intervenant enregistrées avec succès."));
                    $this->redirect()->toRoute(null, [], [], true);
                } catch (Exception $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les modifications de service dû.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
                }
            }
        }

        return $variables;
    }



    public function exportCsvAction()
    {
        $annee = $this->getServiceContext()->getAnnee();
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $this->em()->getFilters()->enable('historique')->init([
            ModificationServiceDu::class,
            Intervenant::class,
        ]);

        $data = $this->getServiceModificationServiceDu()->getExportCsvData($annee, $role->getStructure());

        $csvModel = new CsvModel();
        $csvModel->setHeader($data['head']);
        $csvModel->addLines($data['data']);
        $csvModel->setFilename('modifications-service-du-' . $annee . '.csv');

        return $csvModel;
    }



    private function updateTableauxBord(Intervenant $intervenant)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            TblProvider::SERVICE_DU,
            TblProvider::PLAFOND_INTERVENANT,
            TblProvider::PLAFOND_VOLUME_HORAIRE,
        ], $intervenant);
    }
}