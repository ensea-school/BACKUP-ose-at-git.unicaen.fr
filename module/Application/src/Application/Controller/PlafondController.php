<?php

namespace Application\Controller;

use Application\Entity\Db\PlafondApplication;
use Application\Exception\DbException;
use Application\Form\Plafond\Traits\PlafondApplicationFormAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PlafondApplicationServiceAwareTrait;
use Application\Service\Traits\PlafondServiceAwareTrait;
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



    public function saisirAction()
    {
        $plafondApplication = $this->getEvent()->getParam('plafondApplication');

        if ($plafondApplication) {
            $title = 'Modification d\'une règle de plafond';
        } else {
            $title = 'Création d \'une nouvelle règle de plafond';

            $plafond           = $this->context()->plafondFromQueryPost();
            $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQueryPost();

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



    public function supprimerAction()
    {
        $plafondApplication = $this->getEvent()->getParam('plafondApplication');

        try {
            $this->getServicePlafondApplication()->delete($plafondApplication);
            $this->flashMessenger()->addSuccessMessage("Règle de plafond supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }
}