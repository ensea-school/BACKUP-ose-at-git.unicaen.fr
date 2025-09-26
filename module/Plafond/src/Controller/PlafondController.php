<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Intervenant\Entity\Db\Statut;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\TypeMission;
use Plafond\Entity\Db\Plafond;
use Plafond\Form\PlafondConfigFormAwareTrait;
use Plafond\Form\PlafondFormAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use Referentiel\Entity\Db\FonctionReferentiel;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of PlafondController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondController extends AbstractController
{
    use PlafondServiceAwareTrait;
    use PlafondFormAwareTrait;
    use PlafondConfigFormAwareTrait;


    public function indexAction()
    {
        $title    = 'Gestion des plafonds';
        $plafonds = $this->getServicePlafond()->getList();

        return compact('title', 'plafonds');
    }



    public function editerAction()
    {
        $plafond = $this->getEvent()->getParam('plafond');
        if ($plafond) {
            $title = 'Modification du plafond';
        } else {
            $title   = 'Création d\'un nouveau plafond';
            $plafond = $this->getServicePlafond()->newEntity();
        }

        $form = $this->getFormPlafond();
        $form->bindRequestSave($plafond, $this->getRequest(), function (Plafond $p) {
            $this->getServicePlafond()->save($p);
            $this->construireAction();
            $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            $this->redirect()->toRoute('plafond');
        });

        return compact('title', 'form');
    }



    public function plafondsAction()
    {
        $id                  = (int)$this->params()->fromRoute('id');
        $class               = str_replace( '_', '\\', $this->params()->fromRoute('class'));
        $typeVolumeHoraireId = (int)$this->params()->fromRoute('typeVolumeHoraire');

        $entity            = $this->em()->find($class, $id);
        $typeVolumeHoraire = $this->em()->find(TypeVolumeHoraire::class, $typeVolumeHoraireId);

        return compact('entity', 'typeVolumeHoraire');
    }



    public function supprimerAction()
    {
        $plafond = $this->getEvent()->getParam('plafond');
        try {
            $this->getServicePlafond()->delete($plafond);
            $this->construireAction();

            $this->flashMessenger()->addSuccessMessage("Plafond supprimé avec succès");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    public function indexStructureAction()
    {
        $title   = 'Gestion des plafonds';
        $entity  = $this->getEvent()->getParam('structure');
        $configs = $this->getServicePlafond()->getPlafondsConfig($entity);
        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PLAFONDS_CONFIG_STRUCTURE));

        $vh = new ViewModel();
        $vh->setTemplate('plafond/plafond/config');
        $vh->setVariables(compact('title', 'configs', 'canEdit', 'entity'));

        return $vh;
    }



    public function indexReferentielAction()
    {
        $title   = 'Gestion des plafonds';
        $entity  = $this->getEvent()->getParam('fonctionReferentiel');
        $configs = $this->getServicePlafond()->getPlafondsConfig($entity);
        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PLAFONDS_CONFIG_REFERENTIEL));

        $vh = new ViewModel();
        $vh->setTemplate('plafond/plafond/config');
        $vh->setVariables(compact('title', 'configs', 'canEdit', 'entity'));

        return $vh;
    }



    public function indexMissionAction()
    {
        $title   = 'Gestion des plafonds';
        $entity  = $this->getEvent()->getParam('typeMission');
        $configs = $this->getServicePlafond()->getPlafondsConfig($entity);
        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PLAFONDS_CONFIG_MISSION));

        $vh = new ViewModel();
        $vh->setTemplate('plafond/plafond/config');
        $vh->setVariables(compact('title', 'configs', 'canEdit', 'entity'));

        return $vh;
    }



    public function configStructureAction()
    {
        $entityId  = (int)$this->params()->fromPost('entityId');
        $plafondId = (int)$this->params()->fromPost('plafond');

        $entity        = $this->em()->find(Structure::class, $entityId);
        $plafondConfig = $this->getServicePlafond()->getPlafondConfig($plafondId, $entity);

        $this->getFormPlafondConfig()->requestSaveConfig($plafondConfig, $this->getRequest());

        return new JsonModel([]);
    }



    public function configStatutAction()
    {
        $entityId  = (int)$this->params()->fromPost('entityId');
        $plafondId = (int)$this->params()->fromPost('plafond');

        $entity        = $this->em()->find(Statut::class, $entityId);
        $plafondConfig = $this->getServicePlafond()->getPlafondConfig($plafondId, $entity);

        $this->getFormPlafondConfig()->requestSaveConfig($plafondConfig, $this->getRequest());

        return new JsonModel([]);
    }



    public function configReferentielAction()
    {
        $entityId  = (int)$this->params()->fromPost('entityId');
        $plafondId = (int)$this->params()->fromPost('plafond');

        $entity        = $this->em()->find(FonctionReferentiel::class, $entityId);
        $plafondConfig = $this->getServicePlafond()->getPlafondConfig($plafondId, $entity);

        $this->getFormPlafondConfig()->requestSaveConfig($plafondConfig, $this->getRequest());

        return new JsonModel([]);
    }



    public function configMissionAction()
    {
        $entityId  = (int)$this->params()->fromPost('entityId');
        $plafondId = (int)$this->params()->fromPost('plafond');

        $entity        = $this->em()->find(TypeMission::class, $entityId);
        $plafondConfig = $this->getServicePlafond()->getPlafondConfig($plafondId, $entity);

        $this->getFormPlafondConfig()->requestSaveConfig($plafondConfig, $this->getRequest());

        return new JsonModel([]);
    }



    public function construireCalculerAction()
    {
        $this->construireAction();
        $this->calculerAction();

        $this->flashMessenger()->addSuccessMessage("Tous les plafonds ont été construits et calculés");

        return new MessengerViewModel();
    }



    public function construireAction()
    {
        $this->getServicePlafond()->construire();
    }



    public function calculerAction()
    {
        $perimetres = $this->getServicePlafond()->getPerimetres();
        foreach ($perimetres as $perimetre) {
            $this->getServicePlafond()->calculer($perimetre);
        }
    }
}