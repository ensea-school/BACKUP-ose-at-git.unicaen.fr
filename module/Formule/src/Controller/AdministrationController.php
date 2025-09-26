<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Formule\Entity\Db\Formule;
use Formule\Service\FormulatorServiceAwareTrait;
use Formule\Service\TestServiceAwareTrait;
use UnicaenCode\Util;
use UnicaenVue\Axios\AxiosExtractor;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of AdministrationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use FormulatorServiceAwareTrait;
    use TestServiceAwareTrait;


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



    public function detailsAction()
    {
        /** @var Formule $formule */
        $formule = $this->getEvent()->getParam('formule');

        $variables = [
            'formule'  => AxiosExtractor::extract($formule,['code', 'libelle']),
            'phpClass' => Util::highlight($formule->getPhpClass(), 'php', false, ['show-line-numbers' => true]),
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/administration/details');
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

        $variables = [];
        try{
            $this->getServiceFormulator()->implanter($file);
            $variables['phpClass'] = null;
        }catch(\Exception $e){
            $variables['error'] = $e->getMessage();
        }

        /* On crée un test de formule automatiquement */
        $tableur = $this->getServiceFormulator()->charger($file);
        $test = $tableur->formuleIntervenant();
        $test->setLibelle($filename);
        $this->getServiceTest()->save($test);
        $variables['test'] = $test->getId();

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/administration/formulator');
        $vueModel->setVariables($variables);
        return $vueModel;
    }

}