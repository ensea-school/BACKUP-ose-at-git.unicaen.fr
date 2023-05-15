<?php

namespace Paiement\Controller;


use Application\Controller\AbstractController;
use Paiement\Entity\Db\TypeRessource;
use Paiement\Form\TypeRessource\Traits\TypeRessourceSaisieFormAwareTrait;
use Paiement\Service\TypeRessourceServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

class TypeRessourceController extends AbstractController
{
    use TypeRessourceServiceAwareTrait;
    use TypeRessourceSaisieFormAwareTrait;

    public function indexAction()
    {
        $title = 'Gestion des types de ressources';

        $this->em()->getFilters()->enable('historique')->init([
            TypeRessource::class,
        ]);

        $listTypesRessources = $this->getServiceTypeRessource()->getList();

        return compact('listTypesRessources', 'title');
    }



    public function saisieAction()
    {
        $typeRessource = $this->getEvent()->getParam('typeRessource');
        $form          = $this->getFormTypeRessourceTypeRessourceSaisie();
        if (empty($typeRessource)) {
            $title         = 'Création d\'un nouveau type de ressource';
            $typeRessource = $this->getServiceTypeRessource()->newEntity();
        } else {
            $title = 'Édition d\'un type de ressource';
        }

        $form->bindRequestSave($typeRessource, $this->getRequest(), function (TypeRessource $type) {
            try {
                $this->getServiceTypeRessource()->save($type);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        $typeRessource = $this->getEvent()->getParam('typeRessource');

        try {
            $this->getServiceTypeRessource()->delete($typeRessource);
            $this->flashMessenger()->addSuccessMessage("Type de ressource supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel(compact('typeRessource'));
    }

}
