<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Formule\Entity\Db\Formule;
use Formule\Service\FormulatorServiceAwareTrait;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of AdministrationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use FormulatorServiceAwareTrait;


    public function indexAction()
    {
        $dql = "
        SELECT
          f
        FROM
          " . Formule::class . " f
        ORDER BY
            f.libelle
        ";

        $formules = $this->em()->createQuery($dql)->getArrayResult();


        $variables = [
            'canEdit'  => $this->isAllowed(Privileges::getResourceId(Privileges::FORMULE_ADMINISTRATION_EDITION)),
            'formules' => $formules,
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/administration/index');
        $vueModel->setVariables($variables);
        return $vueModel;
    }



    public function telechargerTableurAction()
    {
        /** @var Formule $formule */
        $formule = $this->getEvent()->getParam('formule');

        if (!$formule) {
            throw new \Exception('Formule de calcul introuvable');
        }

        $tableur = getcwd() . '/data/formules/' . $formule->getCode() . '.ods';

        if (!file_exists($tableur)) {
            throw new \Exception('Fichier de la formule de calcul introuvable');
        }

        header('Content-type: ' . 'application/application/vnd.oasis.opendocument.spreadsheet');
        header('Content-Disposition: attachment; filename="' . $formule->getCode() . '.ods' . '"');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        readfile($tableur);
        die();
    }



    public function televerserTableurAction()
    {
        if (!isset($_FILES['fichier'])) {
            throw new  \Exception('Fichier tableau non transmis');
        }

        $file     = $_FILES['fichier']['tmp_name'];
        $filename = $_FILES['fichier']['name'];

        $this->getServiceFormulator()->charger($file);

        //$fc = new FormuleCalcul($file);




        $variables = [

        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/administration/formulator');
        $vueModel->setVariables($variables);
        return $vueModel;
    }

}