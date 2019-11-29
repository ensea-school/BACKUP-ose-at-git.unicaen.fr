<?php

namespace Application\Controller;

use RuntimeException;
use Application\Entity\Db\Structure;
use Application\Service\Traits\StructureServiceAwareTrait;
use Application\Form\Structure\Traits\StructureSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of StructureController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureController extends AbstractController
{
    use StructureServiceAwareTrait;
    use StructureSaisieFormAwareTrait;


    public function index2Action()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
        ]);

        $structures = $this->getServiceStructure()->getList();

        return compact('structures');
    }



    public function saisieAction()
    {
        /* @var $structure Structure */

        $structure = $this->getEvent()->getParam('structure');

        $form = $this->getFormStructureSaisie();
        if (empty($structure)) {
            $title = 'Création d\'une nouvelle Structure';
            $structure = $this->getServiceStructure()->newEntity();
        } else {
            $title = 'Édition d\'une Structure';
        }

        $form->bindRequestSave($structure, $this->getRequest(), function (Structure $fr) {
            try {
                $this->getServiceStructure()->save($fr);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }

    public function deleteAction()
    {
        $structure = $this->getEvent()->getParam('structure');

        try {
            $this->getServiceStructure()->delete($structure);
            $this->flashMessenger()->addSuccessMessage("Structure supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }
        return new MessengerViewModel(compact('structure'));
    }

    public function voirAction()
    {
        $structure = $this->getEvent()->getParam('structure');

        if (!$structure) {
            throw new RuntimeException("Structure non spécifiée ou introuvable.");
        }

        $title = (string)$structure;
        return compact('structure', 'title');
    }

}
