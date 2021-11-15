<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondApplication;
use Plafond\Form\PlafondApplicationFormAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Plafond\Form\PlafondFormAwareTrait;
use Plafond\Service\PlafondApplicationServiceAwareTrait;
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
    use PlafondApplicationServiceAwareTrait;
    use PlafondApplicationFormAwareTrait;
    use PlafondServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondFormAwareTrait;


    public function indexAction()
    {
        $title = 'Gestion des plafonds';

        $anneeId = $this->getServiceContext()->getAnnee()->getId();

        $plafonds             = $this->getServicePlafond()->getList();
        $typesVolumesHoraires = $this->getServiceTypeVolumeHoraire()->getList();

        $dql = "
        SELECT
          plapp, adeb, afin
        FROM
          " . PlafondApplication::class . " plapp
          LEFT JOIN plapp.anneeDebut adeb
          LEFT JOIN plapp.anneeFin afin
        WHERE
          COALESCE($anneeId,$anneeId) BETWEEN COALESCE(adeb.id,$anneeId) AND COALESCE(afin.id,$anneeId)
        "; // COALESCE($anneeId,$anneeId) bizarre mais c'est pour contourner un bug de doctrine!!!!!!

        /* @var $plapps PlafondApplication[] */
        $plapps = $this->em()->createQuery($dql)->getResult();
        $regles = [];
        foreach ($plapps as $plapp) {
            $plaId                  = $plapp->getPlafond()->getId();
            $tvhId                  = $plapp->getTypeVolumeHoraire()->getId();
            $regles[$plaId][$tvhId] = $plapp;
        }


        return compact('title', 'plafonds', 'typesVolumesHoraires', 'regles');
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
        $plafondApplication = $this->getEvent()->getParam('plafondApplication');

        if ($plafondApplication) {
            $title = 'Modification d\'une règle d\'application de plafond';
        } else {
            $title = 'Création d\'une nouvelle règle d\'application de plafond';

            $plafondId = $this->params()->fromPost('plafond', $this->params()->fromQuery('plafond'));
            $plafond   = $this->getServicePlafond()->get($plafondId);

            $typeVolumeHoraireId = $this->params()->fromPost('typeVolumeHoraire', $this->params()->fromQuery('typeVolumeHoraire'));
            $typeVolumeHoraire   = $this->getServiceTypeVolumeHoraire()->get($typeVolumeHoraireId);

            if (!$plafond) {
                throw new \Exception('Le plafond n\'est pas spécifié');
            }

            if (!$typeVolumeHoraire && !$plafondApplication) {
                throw new \Exception('Vous ne précisez pas si la règle de gestion du plafond s\'applique à du service prévisionnel ou à du service réalisé');
            }

            $plafondApplication = $this->getServicePlafondApplication()->newEntity();
            $plafondApplication->setPlafond($plafond);
            $plafondApplication->setTypeVolumeHoraire($typeVolumeHoraire);
        }

        $form = $this->getFormPlafondPlafondApplication();
        $form->buildAnnees($plafondApplication);
        $form->bindRequestSave($plafondApplication, $this->getRequest(), $this->getServicePlafondApplication());

        return compact('title', 'form');
    }



    public function supprimerApplicationAction()
    {
        $plafondApplication = $this->getEvent()->getParam('plafondApplication');

        try {
            $this->getServicePlafondApplication()->delete($plafondApplication);
            $this->flashMessenger()->addSuccessMessage("Règle de plafond supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
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