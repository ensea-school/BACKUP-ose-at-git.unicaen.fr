<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller;


use Application\Form\Periode\Traits\PeriodeSaisieFormAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenApp\Service\EntityManagerAwareTrait;


/**
 * Description of PeriodeController
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class PeriodeController extends AbstractController
{
    use EntityManagerAwareTrait;
    use PeriodeSaisieFormAwareTrait;
    use PeriodeServiceAwareTrait;

    public function indexAction()
    {
        $query    = $this->em()->createQuery('SELECT p FROM Application\Entity\Db\Periode p ORDER BY p.ordre');
        $periodes = $query->getResult();

        return compact('periodes');
    }



    public function saisieAction()
    {
        $periode = $this->getEvent()->getParam('periode');
        $form    = $this->getFormPeriodeSaisie();

        if (empty($periode)) {
            $title   = "Création d'une nouvelle periode";
            $periode = $this->getServicePeriode()->newEntity();
        } else {
            $title = "Edition d'une periode";
        }

        $form->bindRequestSave($periode, $this->getRequest(), function () use ($periode, $form) {
            $this->getServicePeriode()->save($periode);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussis"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $periode = $this->getEvent()->getParam('periode');
        $this->getServicePeriode()->delete($periode, false);

        return new MessengerViewModel();
    }



    public function trierAction()
    {
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = 1;

        foreach ($champsIds as $champId) {
            $sp = $this->getServicePeriode()->get($champId);
            if ($sp) {
                $sp->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServicePeriode()->save($sp);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($this->translate($e));
                }
            }
        }

        $json = new JsonModel();
        $json->setVariable('msg', 'Tri des champs effectué');

        return $json;
    }
}