<?php

namespace Application\Controller\OffreFormation;

use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Exception\DbException;

/**
 * Description of EtapeController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 */
class EtapeController extends AbstractActionController
{
    use ContextAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use EtapeAwareTrait;
    use NiveauEtapeAwareTrait;



    protected function saisirAction()
    {
        $etape   = $this->getEvent()->getParam('etape');
        $service = $this->getServiceEtape();
        $title   = $etape ? "Modification d'une formation" : "Création d'une nouvelle formation";
        $form    = $this->getFormSaisie();
        $errors  = [];

        $service->canAdd(true);

        if ($etape) {
            $form->bind($etape);
        } else {
            $etape = $service->newEntity();
            $form->setObject($etape);
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $service->save($etape);
                    $form->get('id')->setValue($etape->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        if (!($etape = $this->getEvent()->getParam('etape'))) {
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        $service = $this->getServiceEtape();
        $title   = "Suppression de formation";
        $form    = new \Application\Form\Supprimer('suppr');
        $errors  = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $service->canAdd(true);

        if ($this->getRequest()->isPost()) {
            try {
                $service->delete($etape);
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }

        return compact('etape', 'title', 'form', 'errors');
    }



    public function voirAction()
    {
        $etape        = $this->getEvent()->getParam('etape');
        $title        = 'Formation';
        $serviceEtape = $this->getServiceEtape();

        return compact('etape', 'title', 'serviceEtape');
    }



    /**
     * Retourne le formulaire d'ajout/modif d'Etape.
     *
     * @return \Application\Form\OffreFormation\EtapeSaisie
     */
    protected function getFormSaisie()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('EtapeSaisie');
    }

}
