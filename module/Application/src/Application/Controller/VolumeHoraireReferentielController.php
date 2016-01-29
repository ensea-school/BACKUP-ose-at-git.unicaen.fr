<?php

namespace Application\Controller;

use Application\Form\VolumeHoraireReferentiel\Traits\SaisieAwareTrait;
use RuntimeException;
use Application\Exception\DbException;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\VolumeHoraireReferentielAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentielController extends AbstractController
{
    use ContextAwareTrait;
    use VolumeHoraireReferentielAwareTrait;
    use ServiceReferentielAwareTrait;
    use SaisieAwareTrait;


    public function listeAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraireReferentiel::class
        ]);
        $service = $this->context()->serviceReferentielFromRoute('id');
        if (! $service) throw new RuntimeException("Service non spécifié ou introuvable.");

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $readOnly           = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $volumeHoraireListe = $service->getVolumeHoraireReferentielListe()->setTypeVolumeHoraire( $typeVolumeHoraire );
        return compact('volumeHoraireListe', 'readOnly');
    }

    public function saisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\VolumeHoraireReferentiel::class
        ]);
        $service           = $this->context()->serviceReferentielFromRoute(); /* @var $service \Application\Entity\Db\ServiceReferentiel */
        $typeVolumehoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $errors = [];

        /* @var $service \Application\Entity\Db\Service */
        $service->setTypeVolumeHoraire( $typeVolumehoraire );
        $volumeHoraireList = $service->getVolumeHoraireReferentielListe();

        $form = $this->getFormVolumeHoraireReferentielSaisie();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->get('type-volume-horaire')->setValue($typeVolumehoraire->getId());

        $request = $this->getRequest();
        if ($request->isPost()){
            $heures = (float)str_replace(',','.',$request->getPost()['heures']);
            try{
                $volumeHoraireList->setHeures($heures);
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }
        $form->bind( $volumeHoraireList );
        if ($request->isPost()){
            if ($form->isValid()){
                try{
                    $this->getServiceServiceReferentiel()->save($service);
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
                ->setTemplate('application/volume-horaire-referentiel/saisie')
                ->setVariables(compact('form', 'errors'));
        if ($terminal) {
            return $this->popoverInnerViewModel($viewModel, "Saisie d'heures de référentiel", false);
        }
        return $viewModel;
    }

}