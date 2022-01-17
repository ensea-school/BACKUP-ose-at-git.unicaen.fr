<?php

namespace Plafond\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Structure;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Form\PlafondConfigFormAwareTrait;
use Plafond\Service\PlafondStructureServiceAwareTrait;


/**
 * Description of PlafondStructureController
 *
 * @author UnicaenCode
 */
class PlafondStructureController extends AbstractController
{
    use PlafondStructureServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondConfigFormAwareTrait;

    public function indexAction()
    {
        $title = 'Gestion des plafonds';
        $form  = $this->getFormPlafondConfig();
        $annee = $this->getServiceContext()->getAnnee();
        /* @var $structure Structure */
        $structure = $this->getEvent()->getParam('structure');

        $dql = "
        SELECT
          p, prm, ps
        FROM
          " . Plafond::class . " p
          JOIN p.plafondPerimetre prm WITH prm.code = '" . PlafondPerimetre::STRUCTURE . "'
          LEFT JOIN p.plafondStructure ps WITH ps.annee = :annee AND ps.histoDestruction IS NULL AND ps.structure = :structure
        ORDER BY
            p.libelle
        ";

        /* @var $plafonds Plafond[] */
        $query = $this->em()->createQuery($dql)->setParameters(['annee' => $annee->getId(), 'structure' => $structure->getId()]);
        $form->setPlafonds($query->getResult());

        return compact('title', 'structure', 'form');
    }



    public function editerAction()
    {
        /** @var Plafond $plafond */
        $plafond = $this->em()->find(Plafond::class, $this->params()->fromPost('plafond'));
        $name    = $this->params()->fromPost('name');
        $value   = $this->params()->fromPost('value');

        $ps = $plafond->getPlafondStructure();

        switch ($name) {
            case 'plafondEtatPrevu':
                $ps->setEtatPrevu($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'plafondEtatRealise':
                $ps->setEtatRealise($this->em()->find(PlafondEtat::class, $value));
            break;
            case 'heures':
                $ps->setHeures(stringToFloat($value));
            break;
        }
        $this->getServicePlafond()->saveConfig($ps);

        return new JsonModel([]);
    }

}