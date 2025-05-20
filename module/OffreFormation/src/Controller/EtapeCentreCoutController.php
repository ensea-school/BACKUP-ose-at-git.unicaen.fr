<?php

namespace OffreFormation\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Form\EtapeCentreCout\Traits\EtapeCentreCoutFormAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;

/**
 *
 *
 */
class EtapeCentreCoutController extends AbstractController
{
    use ElementPedagogiqueServiceAwareTrait;
    use ContextServiceAwareTrait;
    use EtapeCentreCoutFormAwareTrait;



    /**
     *
     * @return array
     * @throws RuntimeException
     */
    protected function saisirAction()
    {
        $this->em()->getFilters()
            ->enable('historique')
            ->init([
                       \OffreFormation\Entity\Db\ElementPedagogique::class,
                       \Paiement\Entity\Db\CentreCout::class,
                       \Paiement\Entity\Db\CentreCoutStructure::class,
                       \OffreFormation\Entity\Db\CentreCoutEp::class,
                   ]);
        $this->em()->getFilters()->enable('annee')->init([
            \OffreFormation\Entity\Db\ElementPedagogique::class,
        ]);

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape Etape */
        $form = $this->getFormOffreFormationEtapeCentreCoutEtapeCentreCout();
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
        $title = "Paramétrage des centres de coûts <br /><small>$etape</small>";

        return compact('etape', 'title', 'form', 'errors');
    }

}