<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Exception\DbException;

/**
 * 
 *
 * @method \Doctrine\ORM\EntityManager em() Description
 * @method \Application\Controller\Plugin\Context context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentielController extends AbstractActionController
{
    /**
     * @return \Application\Service\VolumeHoraireReferentiel
     */
    public function getServiceVolumeHoraireReferentiel()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraireReferentiel');
    }

    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de volume horaire spécifié.");
        }
        if (!($volumeHoraire = $this->getServiceVolumeHoraireReferentiel()->getRepo()->find($id))) {
            throw new RuntimeException("Volume horaire '$id' spécifié introuvable.");
        }

        return compact('volumeHoraire');
    }

    public function listeAction()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\VolumeHoraireReferentiel'
            ],
            $this->context()->getGlobalContext()->getDateObservation()
        );
        $service = $this->context()->serviceReferentielFromRoute('id');
        if (! $service) throw new RuntimeException("Service non spécifié ou introuvable.");

        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $readOnly           = 1 == (int)$this->params()->fromQuery('read-only', 0);

        $volumeHoraireListe = $service->getVolumeHoraireReferentielListe()->setTypeVolumeHoraire( $typeVolumeHoraire );
        return compact('volumeHoraireListe', 'readOnly');
    }

    public function saisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\VolumeHoraireReferentiel'
            ],
            $this->context()->getGlobalContext()->getDateObservation()
        );
        $service           = $this->context()->serviceReferentielFromRoute(); /* @var $service \Application\Entity\Db\ServiceReferentiel */
        $typeVolumehoraire = $this->context()->typeVolumeHoraireFromQueryPost('type-volume-horaire');
        $errors = array();

        /* @var $service \Application\Entity\Db\Service */
        $service->setTypeVolumeHoraire( $typeVolumehoraire );
        $volumeHoraireList = $service->getVolumeHoraireReferentielListe();

        $form = $this->getForm();
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
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

    /**
     * Retourne le formulaire de modif de Volume Horaire.
     * 
     * @return \Application\Form\VolumeHoraireReferentiel\Saisie
     */
    protected function getForm()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('VolumeHoraireReferentielSaisie');
    }

    /**
     * @return \Application\Service\ServiceReferentiel
     */
    protected function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->get('applicationServiceReferentiel');
    }
}