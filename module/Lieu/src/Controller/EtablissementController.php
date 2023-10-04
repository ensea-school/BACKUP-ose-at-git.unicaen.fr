<?php

namespace Lieu\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Exception;
use Laminas\View\Model\JsonModel;
use Lieu\Form\EtablissementSaisieFormAwareTrait;
use Lieu\Service\EtablissementServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of EtablissementController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementController extends AbstractController
{
    use ContextServiceAwareTrait;
    use EtablissementSaisieFormAwareTrait;
    use EtablissementServiceAwareTrait;


    public function indexAction()
    {
        $query          = $this->em()->createQuery('SELECT e FROM Lieu\Entity\Db\Etablissement e WHERE e.histoDestruction is null');
        $etablissements = $query->getResult();

        return compact('etablissements');
    }



    public function rechercheAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Lieu\Entity\Db\Etablissement::class,
        ]);

        if (!($term = $this->params()->fromQuery('term'))) {
            return new JsonModel([]);
        }

        $entities = $this->getServiceEtablissement()->finderByLibelle($term)->getQuery()->execute();
        $result   = [];

        foreach ($entities as $item) {
            /* @var $item \Lieu\Entity\Db\Etablissement */
            $result[] = [
                'id'    => $item->getId(),  // identifiant unique de l'item
                'label' => (string)$item,   // libellé de l'item
                'extra' => $item->getDepartement() ? '( département ' . $item->getDepartement() . ')' : '', // infos complémentaires (facultatives) sur l'item
            ];
        };

        return new JsonModel($result);
    }



    public function saisieAction()
    {
        $etablissement = $this->getEvent()->getParam('etablissement');
        $form          = $this->getFormEtablissementSaisie();

        if (empty($etablissement)) {
            $title         = "Création d'un nouvel établissement";
            $etablissement = $this->getServiceEtablissement()->newEntity();
        } else {
            $title = "Edition d'un établissement";
        }
        $form->bindRequestSave($etablissement, $this->getRequest(), function () use ($etablissement, $form) {
            try {
                $this->getServiceEtablissement()->save($etablissement);
                $this->flashMessenger()->addSuccessMessage(
                    "Ajout réussi"
                );
            } catch (Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $etablissement = $this->getEvent()->getParam('etablissement');
        $this->getServiceEtablissement()->delete($etablissement, true);

        return new MessengerViewModel();
    }
}