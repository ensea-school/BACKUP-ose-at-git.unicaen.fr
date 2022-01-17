<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Laminas\View\Model\JsonModel;
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
        $title = 'Gestion des plafonds';
        $form  = $this->getFormPlafondConfig();
        $annee = $this->getServiceContext()->getAnnee();

        $dql = "
        SELECT
          p, prm, pa
        FROM
          " . Plafond::class . " p
          JOIN p.plafondPerimetre prm
          LEFT JOIN p.plafondApplication pa WITH pa.annee = :annee AND pa.histoDestruction IS NULL
        ORDER BY
            prm.libelle, p.libelle
        ";

        /* @var $plafonds Plafond[] */
        $query = $this->em()->createQuery($dql)->setParameter('annee', $annee);
        $form->setPlafonds($query->getResult());

        return compact('title', 'form');
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



    public function editerApplicationAction()
    {
        /** @var Plafond $plafond */
        $plafond = $this->em()->find(Plafond::class, $this->params()->fromPost('plafond'));
        $name    = $this->params()->fromPost('name');
        $value   = $this->params()->fromPost('value');

        $application = $plafond->getPlafondApplication();

        switch ($name) {
            case 'plafondEtatPrevu':
                $application->setEtatPrevu($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'plafondEtatRealise':
                $application->setEtatRealise($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'heures':
                $application->setHeures(stringToFloat($value));
            break;
        }
        $this->getServicePlafond()->saveConfig($application);

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