<?php

namespace Formule\Controller;


use Application\Controller\AbstractController;
use Application\Entity\Db\Annee;
use Application\Hydrator\GenericHydrator;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\Query;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\Db\FormuleTestVolumeHoraire;
use Formule\Model\FormuleCalcul;
use Formule\Service\TestServiceAwareTrait;
use Intervenant\Entity\Db\TypeIntervenant;
use Laminas\View\Model\JsonModel;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenVue\Util;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;

/**
 * Description of FormuleController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TestController extends AbstractController
{
    use TestServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;


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

        return Util::tableAjaxData($this->em(), $this->axios()->fromPOst(), $sql);
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



    public function testDataAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');

        $intervenantHydrator = new GenericHydrator($this->em());
        $intervenantHydrator->spec($formuleTestIntervenant);
        $intervenantHydrator->setExtractType($intervenantHydrator::EXTRACT_TYPE_JSON);

        $volumeHoraireHydrator = new GenericHydrator($this->em());
        $volumeHoraireHydrator->spec(FormuleTestVolumeHoraire::class);
        $volumeHoraireHydrator->setExtractType($volumeHoraireHydrator::EXTRACT_TYPE_JSON);

        $iData = $intervenantHydrator->extract($formuleTestIntervenant);
        $iData['serviceDu'] = $formuleTestIntervenant->getServiceDu();
        $iData['heuresServiceFi'] = $formuleTestIntervenant->getHeuresServiceFi();
        $iData['heuresServiceFa'] = $formuleTestIntervenant->getHeuresServiceFa();
        $iData['heuresServiceFc'] = $formuleTestIntervenant->getHeuresServiceFc();
        $iData['heuresServiceReferentiel'] = $formuleTestIntervenant->getHeuresServiceReferentiel();
        $iData['heuresComplFi'] = $formuleTestIntervenant->getHeuresComplFi();
        $iData['heuresComplFa'] = $formuleTestIntervenant->getHeuresComplFa();
        $iData['heuresComplFc'] = $formuleTestIntervenant->getHeuresComplFc();
        $iData['heuresComplReferentiel'] = $formuleTestIntervenant->getHeuresComplReferentiel();
        $iData['heuresPrimes'] = $formuleTestIntervenant->getHeuresPrimes();
        $iData['heuresService'] = $formuleTestIntervenant->getHeuresServiceFi() + $formuleTestIntervenant->getHeuresServiceFa() + $formuleTestIntervenant->getHeuresServiceFc() + $formuleTestIntervenant->getHeuresServiceReferentiel();
        $iData['heuresCompl'] = $formuleTestIntervenant->getHeuresComplFi() + $formuleTestIntervenant->getHeuresComplFa() + $formuleTestIntervenant->getHeuresComplFc() + $formuleTestIntervenant->getHeuresComplReferentiel();

        $data = [
            'intervenant'     => $iData,
            'volumesHoraires' => [],
        ];

        $i = 1;
        foreach ($formuleTestIntervenant->getVolumesHoraires() as $volumeHoraire) {
            $data['volumesHoraires'][$i] = $volumeHoraireHydrator->extract($volumeHoraire);
            $i++;
        }

        return new AxiosModel($data);
    }



    public function supprimerAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');

        try {
            $this->getServiceTest()->delete($formuleTestIntervenant);
            $this->flashMessenger()->addSuccessMessage("Test de formule supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new AxiosModel();
    }



    public function enregistrementAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');
        if (!$formuleTestIntervenant) {
            $formuleTestIntervenant = new FormuleTestIntervenant();
        }

        $result = ['errors' => [], 'data' => []];
        $data = json_decode($this->params()->fromPost('data'), true);
        $formuleTestIntervenant->fromArray($data);

        $passed = true;
        if (!$formuleTestIntervenant->getLibelle()) {
            $result['errors'][] = 'Libellé manquant';
            $passed = false;
        }
        if (!$formuleTestIntervenant->getFormule()) {
            $result['errors'][] = 'La formule à utiliser n\'est pas précisée';
            $passed = false;
        }
        if (!$formuleTestIntervenant->getAnnee()) {
            $result['errors'][] = 'L\'année doit être renseignée';
            $passed = false;
        }
        if (!$formuleTestIntervenant->getTypeIntervenant()) {
            $result['errors'][] = 'Le type d\'intervenant (permanent, vacataire) doit être renseigné';
            $passed = false;
        }
        if ($formuleTestIntervenant->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_PERMANENT
            && !$formuleTestIntervenant->getStructureCode()
        ) {
            $result['errors'][] = 'La structure doit être renseignée';
            $passed = false;
        }
        if (!$formuleTestIntervenant->getTypeVolumeHoraire()) {
            $result['errors'][] = 'Le type de volume horaire (prévu ou réalisé) doit être renseigné';
            $passed = false;
        }
        if (!$formuleTestIntervenant->getEtatVolumeHoraire()) {
            $result['errors'][] = 'L\'état de volume horaire (saisi, validé, etc) doit être renseigné';
            $passed = false;
        }
        if ($passed) {
            $this->getServiceTest()->save($formuleTestIntervenant);
            try {
                $this->getServiceTest()->calculer($formuleTestIntervenant);
            } catch (\Exception $e) {
                $result['errors'][] = $this->translate($e);
            }
        }
        $result['data'] = $formuleTestIntervenant->toArray();

        return new JsonModel($result);
    }



    public function importAction()
    {
        if (!isset($_FILES['fichier'])) {
            throw new  \Exception('Fichier tableau non transmis');
        }

        $file = $_FILES['fichier']['tmp_name'];
        $filename = $_FILES['fichier']['name'];

        $formuleId = $this->params()->fromPost('formule');
        $formule = $this->em()->find(Formule::class, $formuleId);

        $fc = new FormuleCalcul($file);

        $fti = $this->getServiceTest()->creerDepuisTableur($fc, $formule, $filename);

        $url = $this->url()->fromRoute('formule-test/saisir', ['formuleTestIntervenant' => $fti->getId()]);

        return $this->redirect()->toUrl($url);
    }



    public function creerFromReelAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');

        $formuleTestIntervenant = $this->getServiceTest()->creerDepuisIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $url = $this->url()->fromRoute('formule-test/saisir', ['formuleTestIntervenant' => $formuleTestIntervenant->getId()]);

        return $this->redirect()->toUrl($url);
    }

}