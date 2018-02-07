<?php

namespace Application\Controller;

use Application\Entity\Db\StatutIntervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Exception\DbException;
use Application\Form\StatutIntervenant\Traits\StatutIntervenantSaisieFormAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;

class StatutIntervenantController extends AbstractController
{
    use StatutIntervenantAwareTrait;
    use StatutIntervenantSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            StatutIntervenant::class,
        ]);

        $statutsIntervenants = $this->getServiceStatutIntervenant()->getList();

        return compact('statutsIntervenants');
    }


    public function saisieAction()
    {
        /* @var $statutIntervenant StatutIntervenant */

        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');
        $form = $this->getFormStatutIntervenantSaisie();
        if (empty($statutIntervenant)) {
            //$title = 'Création d\'un nouveau statut d\'intervenant';
            $statutIntervenant = $this->getServiceStatutIntervenant()->newEntity();
            $statutIntervenant->setOrdre(9999);
        } else {
            $title = 'Édition d\'un statut d\'intervenant';
        }

        if ($this->isAllowed(Privileges::getResourceId(Privileges::INTERVENANT_STATUT_EDITION))) {
            $form->bindRequestSave($statutIntervenant, $this->getRequest(), function (StatutIntervenant $si) {
                try {
                    $this->getServiceStatutIntervenant()->save($si);
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $si->getId());
                }
            });
        }else{
            $form->bind($statutIntervenant);
            $form->readOnly();
        }


        return compact('form', 'title');
    }

    public function deleteAction()
    {
        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');

        try {
            $this->getServiceStatutIntervenant()->delete($statutIntervenant);
            $this->flashMessenger()->addSuccessMessage("Statut d'Intervenant supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }
        return new MessengerViewModel(compact('statutIntervenant'));
    }

    public function statutIntervenantTrierAction()
    {
        /* @var $si StatutIntervenant */
        $txt='result=';
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $this->flashMessenger(var_dump($champsIds));
        $ordre = 1;
        foreach ($champsIds as $champId) {
            $txt.=$champId.'=>';
            $si = $this->getServiceStatutIntervenant()->get($champId);
            if ($si) {
                $txt .= ';' . $si->getOrdre();
                $si->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceStatutIntervenant()->save($si);
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $txt .= ':' . $e->getMessage();
                }
            }
        }
        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }

    public function cloneAction()
    {
        /* @var $statutIntervenant StatutIntervenant */
        /* @var $statutIntervenantNew StatutIntervenant */

        $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');
        $form = $this->getFormStatutIntervenantSaisie();
        if (empty($statutIntervenant)) {
            $this->flashMessenger()->addErrorMessage($e->getMessage() . ':' . $statutIntervenant->getId());
            return;
        }
        $title = 'Dupplication d\'un statut d\'intervenant';
        $statutIntervenantNew = $this->getServiceStatutIntervenant()->newEntity();
        $statutIntervenantNew->setLibelle($statutIntervenant->getLibelle());
        $statutIntervenantNew->setDepassement($statutIntervenant->getDepassement());
        $statutIntervenantNew->setPlafondReferentiel($statutIntervenant->getPlafondReferentiel());
        $statutIntervenantNew->setServiceStatutaire($statutIntervenant->getServiceStatutaire());
        $statutIntervenantNew->setTypeIntervenant($this->getServiceTypeIntervenant()->get($statutIntervenant->getTypeIntervenant()->getId()));
        $statutIntervenantNew->setNonAutorise($statutIntervenant->getNonAutorise());
        $statutIntervenantNew->setPeutSaisirService($statutIntervenant->getPeutSaisirService());
        $statutIntervenantNew->setPeutSaisirDossier($statutIntervenant->getPeutSaisirDossier());
        $statutIntervenantNew->setPeutSaisirReferentiel($statutIntervenant->getPeutSaisirReferentiel());
        $statutIntervenantNew->setPeutSaisirMotifNonPaiement($statutIntervenant->getPeutSaisirMotifNonPaiement());
        $statutIntervenantNew->setPeutAvoirContrat($statutIntervenant->getPeutAvoirContrat());
        $statutIntervenantNew->setPeutCloturerSaisie($statutIntervenant->getPeutCloturerSaisie());
        $statutIntervenantNew->setPeutSaisirServiceExt($statutIntervenant->getPeutSaisirServiceExt());
        $statutIntervenantNew->setTemAtv($statutIntervenant->getTemAtv());
        $statutIntervenantNew->setTemBiatss($statutIntervenant->getTemBiatss());
        $statutIntervenantNew->setSourceCode($statutIntervenant->getSourceCode());
        $statutIntervenantNew->setPlafondHcHorsRemuFc($statutIntervenant->getPlafondHcHorsRemuFc());
        $statutIntervenantNew->setPlafondHcRemuFc($statutIntervenant->getPlafondHcRemuFc());
        $statutIntervenantNew->setPeutChoisirDansDossier($statutIntervenant->getPeutChoisirDansDossier());
        $statutIntervenantNew->setMaximumHETD($statutIntervenant->getMaximumHETD());
        $statutIntervenantNew->setDepassementSDSHC($statutIntervenant->getDepassementSDSHC());
        $statutIntervenantNew->setOrdre($statutIntervenant->getOrdre());
        $statutIntervenantNew->setSourceCode($statutIntervenant->getSourceCode());
        $form->bind($statutIntervenantNew);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceStatutIntervenant()->save($statutIntervenantNew);
                    $form->get('id')->setValue($statutIntervenantNew->getId()); // transmet le nouvel ID
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title');
    }
}

