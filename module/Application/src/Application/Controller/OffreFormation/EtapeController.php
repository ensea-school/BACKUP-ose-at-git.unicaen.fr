<?php

namespace Application\Controller\OffreFormation;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Service\Etape as EtapeService;
use Application\Exception\DbException;

/**
 * Description of EtapeController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeController extends AbstractActionController
{
    use \Application\Service\Traits\LocalContextAwareTrait;

    public function ajouterAction()
    {
        return $this->saisirAction();
    }

    public function modifierAction()
    {
        return $this->saisirAction();
    }

    protected function saisirAction()
    {
        $structure = $this->context()->mandatory()->structureFromRoute();
        $niveau    = $this->context()->niveauFromRoute();
        $id        = $this->params()->fromRoute('id');
        $service   = $this->getServiceEtape();
        $title     = $id ? "Modification d'une formation" : "Création d'une nouvelle formation";
        $form      = $this->getFormAjouterModifier();
        $errors    = [];

        // persiste les filtres dans le contexte local
        $localContext = $this->getServiceLocalContext();

        $localContext
                ->setStructure($structure)
                ->setNiveau($niveau ? $this->getServiceNiveauEtape()->get($niveau) : null);

        $service->canAdd(true);

        if ($id) {
            $entity = $service->get($id);
            $form->bind($entity);
        }
        else {
            $entity = $service->newEntity();
            $entity->setStructure($structure);
            $form->setObject($entity);
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $service->save($entity);
                    $form->get('id')->setValue($entity->getId()); // transmet le nouvel ID
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/offre-formation/etape/saisie')
                ->setVariables(compact('form', 'title', 'errors'));

        return $viewModel;
    }

    public function supprimerAction()
    {
        if (!($id = $this->params()->fromRoute('id'))) {
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        $service   = $this->getServiceEtape();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression de formation";
        $form      = new \Application\Form\Supprimer('suppr');
        $errors = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $service->canAdd(true);

        if ($this->getRequest()->isPost()) {
            try {
                $service->delete($entity);
            }catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }
        return compact('entity', 'title', 'form', 'errors');
    }

    public function apercevoirAction()
    {
        $etape       = $this->context()->mandatory()->etapeFromRoute('id');
        $import      = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->etapeGetDifferentiel($etape);
        $title       = "Aperçu d'une formation";
        $short       = $this->params()->fromQuery('short', false);

        return compact('etape','short','title','changements');
    }

    /**
     * Retourne au format JSON les étapes distincts des éléments pédagogiques
     * pour la structure et le niveau éventuellement spécifiés en GET.
     *
     * @return \Zend\View\Model\JsonModel
     *
    public function searchAction()
    {
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();

        $params = [];
        $params['structure'] = $structure instanceof \Application\Entity\Db\Structure ? $structure : null;
        $params['niveau']    = $niveau;

        $result = $this->getServiceElementPedagogique()->finderDistinctEtapes($params)->getQuery()->getResult();

        return new \Zend\View\Model\JsonModel(\UnicaenApp\Util::collectionAsOptions($result));
    }*/

    /**
     * Retourne le formulaire d'ajout/modif d'Etape.
     *
     * @return \Application\Form\OffreFormation\EtapeSaisie
     */
    protected function getFormAjouterModifier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('EtapeSaisie');
    }

    /**
     * Retourne le service ElementPedagogique.
     *
     * @return ElementPedagogiqueService
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }

    /**
     * Retourne le service Etape
     *
     * @return EtapeService
     */
    protected function getServiceEtape()
    {
        return $this->getServiceLocator()->get('applicationEtape');
    }

    /**
     * @return \Application\Service\NiveauEtape
     */
    protected function getServiceNiveauEtape()
    {
        return $this->getServiceLocator()->get('applicationNiveauEtape');
    }
}
