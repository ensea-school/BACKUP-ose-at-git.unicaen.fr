<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Service\Service\TagServiceAwareTrait;
use Service\Entity\Db\Tag;
use Service\Form\TagSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class TagController extends AbstractController
{
    use TagServiceAwareTrait;
    use TagSaisieFormAwareTrait;


    public function indexAction ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Tag::class,
        ]);

        $tags = $this->getServiceTag()->getList();

        return compact('tags');
    }



    public function saisirAction ()
    {
        /* @var $tag Tag */
        $tag  = $this->getEvent()->getParam('tag');
        $form = $this->getFormTag();

        if (empty($tag)) {
            $title = 'Création d\'un nouveau tag';
            $tag   = $this->getServiceTag()->newEntity();
        } else {
            $title = 'Édition d\'un tag';
        }


        $form->bindRequestSave($tag, $this->getRequest(), function (Tag $tag) {
            try {
                $this->getServiceTag()->save($tag);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function supprimerAction ()
    {
        $tag = $this->getEvent()->getParam('tag');

        try {
            $this->getServiceTag()->delete($tag);
            $this->flashMessenger()->addSuccessMessage("Tag supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }
}
