<?php

namespace Application\Controller;

use Application\Form\VolumeHoraire\Traits\SaisieAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Exception\DbException;

/**
 * Description of VolumeHoraireController
 *
 * @method \Doctrine\ORM\EntityManager em() Description
 * @method \Application\Controller\Plugin\Context context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireController extends AbstractActionController
{
    use ContextAwareTrait;
    use VolumeHoraireAwareTrait;
    use ServiceAwareTrait;
    use SaisieAwareTrait;

    public function listeAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraire::class
        ]);
        $service = $this->context()->serviceFromRoute('id');
        if (! $service) throw new RuntimeException("Service non spécifié ou introuvable.");

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $readOnly           = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $volumeHoraireListe = $service->getVolumeHoraireListe()->setTypeVolumehoraire( $typeVolumeHoraire );
        return compact('volumeHoraireListe', 'readOnly');
    }

    public function saisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraire::class,
            \Application\Entity\Db\MotifNonPaiement::class
        ]);

        $service            = $this->context()->serviceFromRoute();
        $typeVolumehoraire  = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $periode            = $this->context()->periodeFromQueryPost();
        $typeIntervention   = $this->context()->typeInterventionFromQueryPost('type-intervention');
        $errors = [];
        if ($this->getServiceService()->canHaveMotifNonPaiement($service)){
            $tousMotifsNonPaiement = $this->params()->fromQuery('tous-motifs-non-paiement');
            if ($tousMotifsNonPaiement == '1'){
                $motifNonPaiement   = false;
            }else{
                $motifNonPaiement   = $this->context()->motifNonPaiementFromQueryPost('motif-non-paiement');
            }
            $ancienMotifNonPaiement = $this->context()->motifNonPaiementFromQueryPost('ancien-motif-non-paiement', $motifNonPaiement);
        }else{
            $motifNonPaiement   = false;
            $ancienMotifNonPaiement = false;
        }



        /* @var $service \Application\Entity\Db\Service */
        $service->setTypeVolumeHoraire( $typeVolumehoraire );
        $volumeHoraireList = $service->getVolumeHoraireListe($periode, $typeIntervention);
        $volumeHoraireList->setMotifNonPaiement($motifNonPaiement);

        $form = $this->getFormVolumeHoraireSaisie();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()){
            $heures = (float)str_replace(',','.',$request->getPost()['heures']);
            try{
                $volumeHoraireList->setHeures($heures, $motifNonPaiement, $ancienMotifNonPaiement);
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }
        $form->bind( $volumeHoraireList );
        if ($request->isPost()){
            if ($form->isValid()){
                try{
                    $this->getServiceService()->save($service);
                }catch(\Exception $e){
                    $e = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/volume-horaire/saisie')
                ->setVariables(compact('form', 'errors', 'ancienMotifNonPaiement'));
        if ($terminal) {
            return $this->popoverInnerViewModel($viewModel, "Saisie d'heures d'enseignement", false);
        }
        return $viewModel;
    }

}