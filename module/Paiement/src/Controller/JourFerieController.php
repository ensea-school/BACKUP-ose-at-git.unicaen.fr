<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Entity\Db\JourFerie;
use Paiement\Form\JourFerieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of JourFerieController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class JourFerieController extends AbstractController
{
    use ContextServiceAwareTrait;
    use JourFerieFormAwareTrait;

    public function indexAction()
    {
        $dql = "
        SELECT 
            jf 
        FROM 
            " . JourFerie::class . " jf
        WHERE 
            jf.dateJour >= :dateDebut AND jf.dateJour <= :dateFin
        ORDER BY
            jf.dateJour
        ";

        $annee = $this->getServiceContext()->getAnnee();

        $parameters = [
            'dateDebut' => $annee->getDateDebut(),
            'dateFin'   => $annee->getDateFin(),
        ];

        $joursFeries = $this->em()->createQuery($dql)->setParameters($parameters)->getResult();

        return compact('annee', 'joursFeries');
    }



    public function saisieAction()
    {
        /** @var JourFerie $jourFerie */
        $jourFerie = $this->getEvent()->getParam('jourFerie');
        $form = $this->getFormJourFerie();

        if (empty($jourFerie)) {
            $title = "Création d'un nouveau jour férié";
            $jourFerie = new JourFerie();
        } else {
            $title = "Modification d'un jour férié";
        }

        $form->bindRequestSave($jourFerie, $this->getRequest(), function () use ($jourFerie, $form) {
            $this->em()->persist($jourFerie);
            $this->em()->flush($jourFerie);
            $this->flashMessenger()->addSuccessMessage("Modification réussie");
        });

        return compact('form', 'title');
    }



    public function deleteAction()
    {
        /** @var JourFerie $jourFerie */
        $jourFerie = $this->getEvent()->getParam('jourFerie');

        try {
            $this->em()->remove($jourFerie);
            $this->em()->flush($jourFerie);
            $this->flashMessenger()->addSuccessMessage("Suppression du jour férié bien effectuée");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }

}