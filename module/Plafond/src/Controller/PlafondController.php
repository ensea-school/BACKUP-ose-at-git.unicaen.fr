<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Statut;
use Application\Entity\Db\Structure;
use Application\Provider\Privilege\Privileges;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondEtat;
use Application\Service\Traits\ContextServiceAwareTrait;
use Plafond\Form\PlafondConfigFormAwareTrait;
use Plafond\Form\PlafondFormAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of PlafondController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondController extends AbstractController
{
    use PlafondServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondFormAwareTrait;
    use PlafondConfigFormAwareTrait;


    public function indexAction()
    {
        $title    = 'Gestion des plafonds';
        $plafonds = $this->getServicePlafond()->getPlafondsConfig();

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
            try {
                $this->getServicePlafond()->save($p);
                $this->construireAction();

                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');

                $this->redirect()->toRoute('plafond');
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('title', 'form');
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



    public function configApplicationAction()
    {
        return $this->configAction(null);
    }



    public function configStructureAction()
    {
        $entityId = $this->params()->fromPost('entityId');
        $entity   = $this->em()->find(Structure::class, (int)$entityId);

        return $this->configAction($entity);
    }



    public function configStatutAction()
    {
        $entityId = $this->params()->fromPost('entityId');
        $entity   = $this->em()->find(Statut::class, (int)$entityId);

        return $this->configAction($entity);
    }



    public function configReferentielAction()
    {
        $entityId = $this->params()->fromPost('entityId');
        $entity   = $this->em()->find(FonctionReferentiel::class, (int)$entityId);

        return $this->configAction($entity);
    }



    private function configAction($entity)
    {
        /** @var Plafond $plafond */
        $plafondId = $this->params()->fromPost('plafond');
        $name      = $this->params()->fromPost('name');
        $value     = $this->params()->fromPost('value');

        $config = $this->getServicePlafond()->getPlafondConfig($plafondId, $entity);

        switch ($name) {
            case 'plafondEtatPrevu':
                $config->setEtatPrevu($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'plafondEtatRealise':
                $config->setEtatRealise($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'heures':
                $config->setHeures(stringToFloat($value));
            break;
        }
        $this->getServicePlafond()->saveConfig($config);

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