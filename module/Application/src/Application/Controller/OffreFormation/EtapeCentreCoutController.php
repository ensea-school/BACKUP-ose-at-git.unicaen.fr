<?php

namespace Application\Controller\OffreFormation;

use Application\Controller\AbstractController;
use Application\Entity\Db\Etape;
use Application\Form\OffreFormation\EtapeCentreCout\Traits\EtapeCentreCoutFormAwareTrait;
use RuntimeException;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeCentreCoutController extends AbstractController
{
    use ElementPedagogiqueServiceAwareTrait;
    use ContextServiceAwareTrait;
    use EtapeCentreCoutFormAwareTrait;



    /**
     *
     * @return type
     * @throws RuntimeException
     */
    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\ElementPedagogique::class,
            \Application\Entity\Db\CentreCout::class,
            \Application\Entity\Db\CentreCoutEp::class
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
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