<?php

namespace EtatSortie\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Entity\Db\Fichier;
use EtatSortie\Entity\Db\EtatSortie;
use EtatSortie\Form\EtatSortieFormAwareTrait;
use EtatSortie\Service\EtatSortieServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of EtatSortieController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtatSortieController extends AbstractController
{
    use EtatSortieServiceAwareTrait;
    use EtatSortieFormAwareTrait;
    use ParametresServiceAwareTrait;



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
        $activationSignatureElectronique = $this->getServiceParametres()->get('signature_electronique_parapheur');


        $form->bindRequestSave($etatSortie, $this->getRequest(), function (EtatSortie $es) {
            try {
                $this->getServiceEtatSortie()->save($es);
                $this->flashMessenger()->addSuccessMessage('État de sortie bien enregistré');
                return $this->redirect()->toRoute('etat-sortie');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title', 'activationSignatureElectronique');
    }



    public function supprimerAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        try {
            $this->getServiceEtatSortie()->delete($etatSortie);
            $this->flashMessenger()->addSuccessMessage("État de sortie supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
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
            $fichier->setContenu($etatSortie->getFichier());
        }
        $this->uploader()->download($fichier);
    }



    public function genererPdfAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $filtres = $this->params()->fromPost() + $this->params()->fromQuery();

        $document = $this->getServiceEtatSortie()->genererPdf($etatSortie, $filtres);
        if (headers_sent()){
            throw new \Exception("Fin du script : en-têtes déjà envoyées");
        }else {
            $document->download($etatSortie->getLibelle() . '.pdf');
        }
    }



    public function genererCsvAction()
    {
        /* @var $etatSortie EtatSortie */
        $etatSortie = $this->getEvent()->getParam('etatSortie');

        $filtres = $this->params()->fromPost() + $this->params()->fromQuery();

        $csvModel = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filtres);

        return $csvModel;
    }
}
