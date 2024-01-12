<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Intervenant\Controller;


use Application\Controller\AbstractController;
use Intervenant\Entity\Db\Corps;
use Intervenant\Form\CorpsSaisieFormAwareTrait;
use Intervenant\Service\CorpsServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of CorpsController
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class CorpsController extends AbstractController
{
    use EntityManagerAwareTrait;
    use CorpsSaisieFormAwareTrait;
    use CorpsServiceAwareTrait;

    public function indexAction()
    {

        $dql    = "SELECT c FROM " . Corps::class . " c 
            WHERE c.histoDestruction is null";
        $query  = $this->em()->createQuery($dql);
        $corpss = $query->getResult();

        return compact('corpss');
    }



    public function saisieAction()
    {

        $corps = $this->getEvent()->getParam('corps');
        $form  = $this->getFormCorpsCorpsSaisie();

        if (empty($corps)) {
            $title = "Création d'un nouveau corps";
            $corps = $this->getServiceCorps()->newEntity();
        } else {
            $title = "Édition d'un corps";
        }

        $form->bindRequestSave($corps, $this->getRequest(), function () use ($corps, $form) {

            $this->getServiceCorps()->save($corps);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $corps = $this->getEvent()->getParam('corps');
        $this->getServiceCorps()->delete($corps, true);

        return new MessengerViewModel();
    }

}