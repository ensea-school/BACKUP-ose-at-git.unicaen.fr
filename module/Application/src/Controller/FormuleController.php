<?php

namespace Application\Controller;


use Application\Entity\Db\Annee;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Formule;
use Application\Entity\Db\FormuleTestIntervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\FormuleTestIntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Laminas\View\Model\JsonModel;

/**
 * Description of FormuleController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleController extends AbstractController
{
    use FormuleTestIntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;


    public function testAction()
    {
        $fti = $this->getServiceFormuleTestIntervenant()->getList();

        return compact('fti');
    }



    public function testSaisirAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');

        $formules          = $this->em()->createQuery("SELECT f FROM " . Formule::class . " f ORDER BY f.id")->execute();
        $annees            = $this->em()->createQuery("SELECT a FROM " . Annee::class . " a WHERE a.id BETWEEN 2013 AND 2030 ORDER BY a.id")->execute();
        $typesIntervenants = $this->em()->createQuery("SELECT ti FROM " . TypeIntervenant::class . " ti ORDER BY ti.id")->execute();
        $typesVh           = $this->em()->createQuery("SELECT t FROM " . TypeVolumeHoraire::class . " t ORDER BY t.id")->execute();
        $etatsVh           = $this->em()->createQuery("SELECT t FROM " . EtatVolumeHoraire::class . " t ORDER BY t.id")->execute();
        $annee             = $this->getServiceContext()->getAnnee();
        $formuleId         = $this->getServiceParametres()->get('formule');

        if (!$formuleTestIntervenant) {
            $title                  = 'Ajout d\'un test de formule';
            $formuleTestIntervenant = new FormuleTestIntervenant();
        } else {
            $title = 'Modification d\'un test de formule';
            try {
                $this->getServiceFormuleTestIntervenant()->calculer($formuleTestIntervenant);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        }

        $structures                      = $formuleTestIntervenant->getStructures();
        $structures['__UNIV__']          = 'Université (établissement))'; // Établissement
        $structures['__EXTERIEUR__']     = 'Extérieur (autre établissement))'; // Autre établissement
        $structures['__new_structure__'] = '- Ajout d\'une nouvelle structure -'; // Pour pouvoir ajouter une structure

        return compact('formuleTestIntervenant', 'title', 'annee', 'formuleId', 'structures', 'formules', 'annees', 'typesIntervenants', 'typesVh', 'etatsVh');
    }



    public function testSupprimerAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');

        try {
            $this->getServiceFormuleTestIntervenant()->delete($formuleTestIntervenant);
            $this->flashMessenger()->addSuccessMessage("Test de formule supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    public function testEnregistrementAction()
    {
        /* @var $formuleTestIntervenant FormuleTestIntervenant */
        $formuleTestIntervenant = $this->getEvent()->getParam('formuleTestIntervenant');
        if (!$formuleTestIntervenant) {
            $formuleTestIntervenant = new FormuleTestIntervenant();
        }

        $result = ['errors' => [], 'data' => []];
        $data   = json_decode($this->params()->fromPost('data'), true);
        $formuleTestIntervenant->fromArray($data);

        $passed = true;
        if (!$formuleTestIntervenant->getLibelle()) {
            $result['errors'][] = 'Libellé manquant';
            $passed             = false;
        }
        if (!$formuleTestIntervenant->getFormule()) {
            $result['errors'][] = 'La formule à utiliser n\'est pas précisée';
            $passed             = false;
        }
        if (!$formuleTestIntervenant->getAnnee()) {
            $result['errors'][] = 'L\'année doit être renseignée';
            $passed             = false;
        }
        if (!$formuleTestIntervenant->getTypeIntervenant()) {
            $result['errors'][] = 'Le type d\'intervenant (permanent, vacataire) doit être renseigné';
            $passed             = false;
        }
        if ($formuleTestIntervenant->getTypeIntervenant()->getCode() == TypeIntervenant::CODE_PERMANENT
            && !$formuleTestIntervenant->getStructureCode()
        ) {
            $result['errors'][] = 'La structure doit être renseignée';
            $passed             = false;
        }
        if (!$formuleTestIntervenant->getTypeVolumeHoraire()) {
            $result['errors'][] = 'Le type de volume horaire (prévu ou réalisé) doit être renseigné';
            $passed             = false;
        }
        if (!$formuleTestIntervenant->getEtatVolumeHoraire()) {
            $result['errors'][] = 'L\'état de volume horaire (saisi, validé, etc) doit être renseigné';
            $passed             = false;
        }
        if ($passed) {
            $this->getServiceFormuleTestIntervenant()->save($formuleTestIntervenant);
            try {
                $this->getServiceFormuleTestIntervenant()->calculer($formuleTestIntervenant);
            } catch (\Exception $e) {
                $result['errors'][] = $this->translate($e);
            }
        }
        $result['data'] = $formuleTestIntervenant->toArray();

        return new JsonModel($result);
    }



    public function testCreerFromReelAction()
    {
        $intervenant       = $this->getEvent()->getParam('intervenant');
        $typeVolumeHoraire = $this->getEvent()->getParam('typeVolumeHoraire');
        $etatVolumeHoraire = $this->getEvent()->getParam('etatVolumeHoraire');

        $formuleTestIntervenant = $this->getServiceFormuleTestIntervenant()->creerDepuisIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $url = $this->url()->fromRoute('formule-calcul/test/saisir', ['formuleTestIntervenant' => $formuleTestIntervenant->getId()]);

        return $this->redirect()->toUrl($url);
    }



    public function calculerToutAction()
    {
        $this->em()->getConnection()->executeStatement('BEGIN OSE_FORMULE.CALCULER_TOUT; END;');
    }

}