<?php

namespace Paiement\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Form\TauxFormAwareTrait;
use Paiement\Form\TauxValeurFormAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenVue\View\Model\AxiosModel;

/**
 * Description of TauxRemuController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxRemuController extends AbstractController
{
    use TauxRemuServiceAwareTrait;
    use ContextServiceAwareTrait;
    use TauxFormAwareTrait;
    use TauxValeurFormAwareTrait;

    public function indexAction()
    {
        $annee = $this->getServiceContext()->getAnnee();

        return compact('annee');
    }



    public function getListeTauxAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TauxRemu::class,
        ]);


        $tauxListe = $this->getServiceTauxRemu()->getTauxRemusAnneeWithValeur();


        $tauxRemuProp = ['id',
                       'code',
                       'libelle',
                       ['tauxRemuValeurs', ['id', 'dateEffet', 'valeur']],
                       'tauxRemuValeursIndex',
        ];
        $properties = $tauxRemuProp;
        $properties[] = ['tauxRemu', $tauxRemuProp];

        $triggers = [
            // '/' signifie que nous agirons sur les données de premier niveau, qui sont ici des Personne. Le trigger agira pour chaque personne
            '/' => function ($original, $extracted) {
                // $original contiendra l'objet correspondant à l'entité Personne
                // $extracted contiendra le tableau de données déjà extrait
                $extracted['canEdit']         = $this->isAllowed($original, Privileges::TAUX_EDITION); // On ajoute ici une propriété en extraction qui n'a pas été générée avant.
                $extracted['canDeleteValeur'] = $this->isAllowed($original, Privileges::TAUX_SUPPRESSION);
                $extracted['canDelete']       = $extracted['canDeleteValeur'] && !$original->hasChildren();

                // Nous pourrions tout aussi bien retirer une donnée, ou bien en changer le type ou la valeur.
                return $extracted;
            },
        ];

        return new AxiosModel($tauxListe, $properties, $triggers);
    }



    public function saisirAction()
    {

        $tauxRemu = $this->getEvent()->getParam('tauxRemu');
        $form     = $this->getFormTaux();
        if (empty($tauxRemu)) {
            $title    = "Création d'un nouveau taux";
            $tauxRemu = $this->getServiceTauxRemu()->newEntity();
        } else {
            $title = "Édition d'un taux";
        }
        $form->bindRequestSave($tauxRemu, $this->getRequest(), function () use ($tauxRemu, $form) {

            $this->em()->persist($tauxRemu);
            $tauxRemuValeurs = $tauxRemu->getTauxRemuValeurs();
            foreach ($tauxRemuValeurs as $tauxRemuValeur) {
                $tauxRemuValeur->setTauxRemu($tauxRemu);
                $this->em()->persist($tauxRemuValeur);
            }
            $this->em()->flush($tauxRemu);
            foreach ($tauxRemuValeurs as $tauxRemuValeur) {
                $this->em()->flush($tauxRemuValeur);
            }
            $this->getServiceTauxRemu()->clearCache();
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function saisirValeurAction(): array
    {

        $tauxRemuValeurId = $this->params()->fromRoute('tauxRemuValeur');
        $form             = $this->getFormTauxValeur();

        if (empty($tauxRemuValeurId)) {
            $title          = "Création d'une nouvelle valeur";
            $tauxRemuValeur = $this->getServiceTauxRemu()->newEntityValeur();
        } else {
            $tauxRemuValeur = $this->getServiceTauxRemu()->getTauxRemuValeur($tauxRemuValeurId);
            $title          = "Édition d'une valeur";
        }

        if ($tauxRemuValeur->getTauxRemu() == null) {
            $tauxRemu = $this->getEvent()->getParam('tauxRemu');
            $tauxRemuValeur->setTauxRemu($tauxRemu);
        }


        $form->bindRequestSave($tauxRemuValeur, $this->getRequest(), function () use ($tauxRemuValeur, $form) {
            $this->em()->persist($tauxRemuValeur);
            $this->em()->flush($tauxRemuValeur);
            $this->getServiceTauxRemu()->clearCache();
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction(): AxiosModel
    {
        $tauxRemu = $this->getEvent()->getParam('tauxRemu');
        $this->getServiceTauxRemu()->delete($tauxRemu, true);
        $this->getServiceTauxRemu()->clearCache();

        $this->flashMessenger()->addSuccessMessage("Taux supprimée avec succès.");

        return new AxiosModel([]);
    }



    /**
     * Retourne les données pour un taux
     *
     * @return AxiosModel
     */
    public function getAction()
    {
        $tauxRemu = $this->getEvent()->getParam('tauxRemu');

        return new AxiosModel($tauxRemu);
    }



    public function supprimerValeurAction(): MessengerViewModel
    {
        $tauxRemuValeurId = $this->params()->fromRoute('tauxRemuValeur');
        $tauxRemuValeur   = $this->getServiceTauxRemu()->getTauxRemuValeur($tauxRemuValeurId);
        $this->em()->remove($tauxRemuValeur);
        $this->em()->flush();
        $this->getServiceTauxRemu()->clearCache();


        return new MessengerViewModel();
    }
}

