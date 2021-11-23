<?php

namespace Application\Controller;

use Application\Entity\Db\DomaineFonctionnel;
use Application\Service\Traits\DomaineFonctionnelServiceAwareTrait;
use Application\Form\DomaineFonctionnel\Traits\DomaineFonctionnelSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\SourceServiceAwareTrait;

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

        $form = $this->getFormDomaineFonctionnelSaisie();
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
                $e = DbException::translate($e);
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
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel(compact('domaineFonctionnel'));
    }
}
