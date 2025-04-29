<?php

namespace Formule\Controller;


use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Entity\Db\Annee;
use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Model\FormuleCalcul;
use Formule\Service\FormulatorServiceAwareTrait;
use Formule\Service\TestServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenVue\Util;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;

/**
 * Description of TestController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TestController extends AbstractController
{
    use TestServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use FormulatorServiceAwareTrait;


    public function indexAction()
    {
        $vm = new VueModel();
        $vm->setTemplate('formule/test/index');
        return $vm;
    }



    public function indexDataAction()
    {
        $sql = "
        SELECT 
          fti.id      id, 
          fti.libelle libelle,
          f.libelle   formule,
          a.libelle   annee
        FROM 
          formule_test_intervenant fti
          JOIN formule f ON f.id = fti.formule_id
          JOIN annee a ON a.id = fti.annee_id
        WHERE
          lower(fti.libelle || ' ' || f.libelle || ' ' || a.libelle) like :search
        ";

        return Util::tableAjaxData($this->em(), $this->axios()->fromPost(), $sql);
    }



    public function saisirAction()
    {
        $annee = $this->getServiceContext()->getAnnee();

        $fSql = "
        SELECT 
          f.id, 
          f.libelle, 
          f.iParam1Libelle,  
          f.iParam2Libelle,
          f.iParam3Libelle,
          f.iParam4Libelle,
          f.iParam5Libelle,
          f.vhParam1Libelle,
          f.vhParam2Libelle,
          f.vhParam3Libelle,
          f.vhParam4Libelle,
          f.vhParam5Libelle
        FROM " . Formule::class . " f 
        INDEX BY f.id
        ORDER BY f.libelle
        ";

        $formules = $this->em()->createQuery($fSql)->getArrayResult();
        $annees = $this->em()->createQuery("SELECT a.id, a.libelle FROM " . Annee::class . " a WHERE a.id BETWEEN 2013 AND " . ($annee->getId() + 5) . " ORDER BY a.id")->getArrayResult();
        $typesIntervenants = $this->em()->createQuery("SELECT ti.id, ti.libelle FROM " . TypeIntervenant::class . " ti ORDER BY ti.id")->getArrayResult();
        $typesVh = $this->em()->createQuery("SELECT t.id, t.libelle FROM " . TypeVolumeHoraire::class . " t ORDER BY t.id")->getArrayResult();
        $etatsVh = $this->em()->createQuery("SELECT t.id, t.libelle FROM " . EtatVolumeHoraire::class . " t ORDER BY t.id")->getArrayResult();
        $formuleId = $this->getServiceParametres()->get('formule');

        $variables = [
            'id'                   => (int)$this->params()->fromRoute('formuleTestIntervenant'),
            'formules'             => $formules,
            'annees'               => $annees,
            'typesIntervenants'    => $typesIntervenants,
            'typesVolumesHoraires' => $typesVh,
            'etatsVolumesHoraires' => $etatsVh,
            'defaultFormule'       => (int)$formuleId,
        ];

        $vm = new VueModel($variables);
        $vm->setTemplate('formule/test/test');
        return $vm;
    }



    public function saisirDataAction()
    {
        $formuleTestIntervenantId = (int)$this->params()->fromRoute('formuleTestIntervenant');
        $formuleTestIntervenant = $this->getServiceTest()->get($formuleTestIntervenantId);
        $data = $this->getServiceTest()->toJson($formuleTestIntervenant);

        return new AxiosModel($data);
    }



    public function enregistrerAction()
    {
        $formuleTestIntervenantId = (int)$this->params()->fromRoute('formuleTestIntervenant');
        $formuleTestIntervenant = $this->getServiceTest()->get($formuleTestIntervenantId);

        $intervenantData = (array)$this->axios()->fromPost('intervenant');
        $volumesHorairesData = (array)$this->axios()->fromPost('volumesHoraires');
        $simpleCalcul = $this->axios()->fromPost('simpleCalcul', false);

        $this->getServiceTest()->fromJson($formuleTestIntervenant, $intervenantData, $volumesHorairesData);

        try {
            $this->getServiceFormulator()->calculer($formuleTestIntervenant, $formuleTestIntervenant->getFormule());
            $debug = $formuleTestIntervenant->getDebugTrace();

            if (!$simpleCalcul) {
                $this->getServiceTest()->save($formuleTestIntervenant);
            }
            $data = $this->getServiceTest()->toJson($formuleTestIntervenant);
            $data['debug'] = $debug;

            return new AxiosModel($data);
        }catch(\Exception $e){
            $this->flashMessenger()->addErrorMessage($e->getMessage());
            return new AxiosModel();
        }
    }



    public function supprimerAction()
    {
        $formuleTestIntervenantId = (int)$this->params()->fromRoute('formuleTestIntervenant');
        $formuleTestIntervenant = $this->getServiceTest()->get($formuleTestIntervenantId);

        try {
            $this->getServiceTest()->delete($formuleTestIntervenant);
            $this->flashMessenger()->addSuccessMessage("Test de formule supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new AxiosModel();
    }



    public function importAction()
    {
        if (!isset($_FILES['fichier'])) {
            throw new  \Exception('Fichier tableau non transmis');
        }

        $file = $_FILES['fichier']['tmp_name'];
        $filename = $_FILES['fichier']['name'];

        $tableur = $this->getServiceFormulator()->charger($file);
        $test = $tableur->formuleIntervenant();
        $test->setLibelle($filename);

        $this->getServiceTest()->save($test);

        $url = $this->url()->fromRoute('formule-test/saisir', ['formuleTestIntervenant' => $test->getId()]);

        return $this->redirect()->toUrl($url);
    }



    public function creerFromReelAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /** @var $intervenant Intervenant */

        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        /** @var $typeVolumeHoraire TypeVolumeHoraire */

        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');
        /** @var $etatVolumeHoraire EtatVolumeHoraire */


        $formuleTestIntervenant = $this->getServiceTest()->creerDepuisIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $url = $this->url()->fromRoute('formule-test/saisir', ['formuleTestIntervenant' => $formuleTestIntervenant->getId()]);

        return $this->redirect()->toUrl($url);
    }

}