<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\SourceServiceAwareTrait;
use Paiement\Entity\Db\DomaineFonctionnel;
use Paiement\Form\DomaineFonctionnel\DomaineFonctionnelSaisieFormAwareTrait;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class DomaineFonctionnelController extends AbstractController
{
    use DomaineFonctionnelServiceAwareTrait;
    use DomaineFonctionnelSaisieFormAwareTrait;
    use SourceServiceAwareTrait;



    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            DomaineFonctionnel::class,
        ]);

        $domaineFonctionnels = $this->getServiceDomaineFonctionnel()->getList();

        return compact('domaineFonctionnels');
    }



    public function saisieAction()
    {
        /* @var $domaineFonctionnel DomaineFonctionnel */

        $domaineFonctionnel = $this->getEvent()->getParam('domaineFonctionnel');

        $form = $this->getFormDomaineFonctionnelDomaineFonctionnelSaisie();
        if (empty($domaineFonctionnel)) {
            $title              = 'Création d\'un nouveau domaine fonctionnel';
            $domaineFonctionnel = $this->getServiceDomaineFonctionnel()->newEntity()
                ->setSource($this->getServiceSource()->getOse());
        } else {
            $title = 'Édition d\'un domaine fonctionnel';
        }

        $form->bindRequestSave($domaineFonctionnel, $this->getRequest(), function (DomaineFonctionnel $fr) {
            try {
                $this->getServiceDomaineFonctionnel()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $domaineFonctionnel = $this->getEvent()->getParam('domaineFonctionnel');

        try {
            $this->getServiceDomaineFonctionnel()->delete($domaineFonctionnel);
            $this->flashMessenger()->addSuccessMessage("Domaine Fonctionnel supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('domaineFonctionnel'));
    }
}
