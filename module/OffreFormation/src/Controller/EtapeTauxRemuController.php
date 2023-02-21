<?php

namespace OffreFormation\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use OffreFormation\Form\EtapeTauxRemu\EtapeTauxRemuFormAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Entity\Db\Etape;

/**
 *
 *
 */
class EtapeTauxRemuController extends AbstractController
{
    use ElementPedagogiqueServiceAwareTrait;
    use ContextServiceAwareTrait;
    use EtapeTauxRemuFormAwareTrait;



    /**
     *
     * @return array
     * @throws RuntimeException
     */
    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \OffreFormation\Entity\Db\ElementPedagogique::class,
            \Paiement\Entity\Db\TauxRemu::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \OffreFormation\Entity\Db\ElementPedagogique::class,
        ]);

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape Etape */
        $form = $this->getFormEtapeTauxRemuEtapeTauxRemu();
        $errors = [];

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->em()->flush();
                $form->bind($etape); // on re-binde pour forcer la MAJ
            } else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Paramétrage des taux de rémunérations <br /><small>$etape</small>";

        return compact('etape', 'title', 'form', 'errors');
    }

}