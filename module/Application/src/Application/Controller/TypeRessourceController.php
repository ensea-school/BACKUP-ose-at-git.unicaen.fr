<?php

namespace Application\Controller;


use Application\Entity\Db\TypeRessource;
use Application\Form\TypeRessource\Traits\TypeRessourceSaisieFormAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;

class TypeRessourceController extends AbstractController
{
    use TypeRessourceServiceAwareTrait;
    use TypeRessourceSaisieFormAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeRessource::class,
        ]);

        $listTypesRessources = $this->getServiceTypeRessource()->getList();

        return compact('listTypesRessources');
    }

    public function saisieAction()
    {
        $typ = $this->getEvent()->getParam('typeIntervention');
        $form             = $this->getFormTypeRessourceSaisie();
        $title = 'Édition d\'un type de ressource';

        $typeRessource = $this->getEvent()->getParam('typeRessource');
        $form = $this->getFormTypeRessourceSaisie();

        $form->bindRequestSave($typeRessource, $this->getRequest(), function (TypeRessource $type) {
            try {
                $this->getServiceTypeRessource()->save($type);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');


        return compact();
    }

    public function deleteAction()
    {
        return compact();
    }

}
