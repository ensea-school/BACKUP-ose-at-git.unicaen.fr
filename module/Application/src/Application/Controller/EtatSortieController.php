<?php

namespace Application\Controller;


use Application\Entity\Db\EtatSortie;
use Application\Entity\Db\Fichier;
use Application\Exception\DbException;
use Application\Form\Traits\EtatSortieFormAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\CsvModel;

/**
 * Description of EtatSortieController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtatSortieController extends AbstractController
{
    use EtatSortieServiceAwareTrait;
    use EtatSortieFormAwareTrait;



    public function indexAction()
    {
        $etatsSortie = $this->getServiceEtatSortie()->getList();

        return compact('etatsSortie');
    }



    public function saisirAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $form = $this->getFormEtatSortie();

        if (!$etatSortie) {
            $title      = 'Ajout d\'un état de sortie';
            $etatSortie = new EtatSortie();
        } else {
            $title = 'Modification d\'un état de sortie';
        }

        $form->bindRequestSave($etatSortie, $this->getRequest(), function (EtatSortie $es) {
            try {
                $this->getServiceEtatSortie()->save($es);
                $this->flashMessenger()->addSuccessMessage('État de sortie bien enregistré');
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        try {
            $this->getServiceEtatSortie()->delete($etatSortie);
            $this->flashMessenger()->addSuccessMessage("État de sortie supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }



    public function telechargerAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $fichier = new Fichier();
        $fichier->setNom(Util::reduce($etatSortie->getLibelle()) . '.odt');
        $fichier->setTypeMime('application/vnd.oasis.opendocument.text');
        if ($etatSortie->hasFichier()) {
            $fichier->setContenu(stream_get_contents($etatSortie->getFichier(), -1, 0));
        }
        $this->uploader()->download($fichier);
    }



    public function genererPdfAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $filtres = $this->params()->fromPost() + $this->params()->fromQuery();

        $this->getServiceEtatSortie()->generer($etatSortie, $filtres);
    }



    public function genererCsvAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $filtres = $this->params()->fromPost() + $this->params()->fromQuery();

        $data = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filtres);
        if (isset($data[0])){
            $head = array_keys($data[0]);
        }else{
            $head = [];
        }

        $csvModel = new CsvModel();
        $csvModel->setHeader($head);
        $csvModel->addLines($data);
        $csvModel->setFilename($etatSortie->getLibelle().'.csv');

        return $csvModel;
    }
}
