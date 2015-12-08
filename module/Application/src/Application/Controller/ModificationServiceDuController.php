<?php

namespace Application\Controller;

use Application\Entity\Db\ModificationServiceDu;
use Application\Form\Intervenant\Traits\ModificationServiceDuFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Doctrine\DBAL\DBALException;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Common\Exception\RuntimeException;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModificationServiceDuController extends AbstractActionController
{
    use ContextAwareTrait;
    use IntervenantAwareTrait;
    use ModificationServiceDuFormAwareTrait;


    public function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            ModificationServiceDu::class,
        ]);

        $intervenant = $this->getEvent()->getParam('intervenant');
        $canEdit     = $this->isAllowed( $intervenant, Privileges::MODIF_SERVICE_DU_EDITION );

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
        $fs = $form->getFieldsets()['fs'];
        $form->setAttribute('action', $this->getRequest()->getRequestUri());
        $form->bind($intervenant);

        $variables = [
            'form' => $form,
            'intervenant' => $intervenant,
            'title' => "Modifications de service dû <small>$intervenant</small>",
            'canEdit' => $canEdit
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
                    $this->flashMessenger()->addSuccessMessage(sprintf("Modifications de service dû de $intervenant enregistrées avec succès."));
                    $this->redirect()->toRoute(null, [], [], true);
                }
                catch (DBALException $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les modifications de service dû.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
                }
            }
        }
        return $variables;
    }
}