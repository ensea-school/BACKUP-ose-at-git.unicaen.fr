<?php

namespace Lieu\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Lieu\Entity\Db\Structure;
use Lieu\Form\StructureSaisieFormAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use RuntimeException;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of StructureController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureController extends AbstractController
{
    use StructureServiceAwareTrait;
    use StructureSaisieFormAwareTrait;

    protected array $structures = [];



    public function indexAction ()
    {
        $canAdd = $this->isAllowed(Privileges::getResourceId(Privileges::STRUCTURES_ADMINISTRATION_EDITION));

        $vm = new VueModel;
        $vm->setTemplate('lieu/structures-admin');
        $vm->setVariable('canAdd', $canAdd);

        return $vm;
    }



    public function listeAction ()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
        ]);

        $treeArray = $this->getServiceStructure()->getTreeArray();

        return new AxiosModel($treeArray);
    }



    public function saisieAction ()
    {
        /* @var $structure Structure */
        $structure = $this->getEvent()->getParam('structure');


        if (empty($structure)) {
            $title     = 'Création d\'une nouvelle Structure';
            $structure = $this->getServiceStructure()->newEntity();
        } else {
            $title = 'Édition d\'une Structure';
        }

        $form = $this->getFormStructureSaisie($structure);
        
        $form->bindRequestSave($structure, $this->getRequest(), function (Structure $structure) {

            try {
                if (empty($structure->getSourceCode()) || !$structure->getSource()->getImportable()) {
                    $structure->setSourceCode($structure->getCode());
                }
                $this->getServiceStructure()->save($structure);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function deleteAction ()
    {
        /** @var Structure $structure */
        $structure = $this->getEvent()->getParam('structure');

        if ($structure->getSource()->getImportable()) {
            throw new \LogicException('Une structure importée ne peut pas être supprimée dans l\'application');
        }

        try {
            $this->getServiceStructure()->delete($structure);
            $this->flashMessenger()->addSuccessMessage("Structure supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new AxiosModel();
    }



    public function voirAction ()
    {
        $structure = $this->getEvent()->getParam('structure');
        $tab       = $this->params()->fromQuery('tab', 'fiche');

        if (!$structure) {
            throw new RuntimeException("Structure non spécifiée ou introuvable.");
        }

        $title = (string)$structure;

        return compact('structure', 'title', 'tab');
    }

}
