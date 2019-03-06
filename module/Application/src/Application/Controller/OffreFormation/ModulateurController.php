<?php

namespace Application\Controller\OffreFormation;

use Application\Controller\AbstractController;
use Application\Form\OffreFormation\Traits\EtapeModulateursSaisieAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;

/**
 * Description of ModulateurController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurController extends AbstractController
{
    use EtapeServiceAwareTrait;
    use EtapeModulateursSaisieAwareTrait;



    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\ElementModulateur::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape \Application\Entity\Db\Etape */

        if (!$etape) {
            throw new \RuntimeException('La formation n\'a pas été spécifiée ou bien elle est invalide.');
        }

        $form   = $this->getFormOffreFormationEtapeModulateursSaisie();
        $errors = [];

        $form->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceEtape()->saveModulateurs($etape);
                    $form->bind($etape); // forçage de rafraichissement de formulaire, je ne sais pas pouquoi il faut faire çà!!
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            } else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Saisie des modulateurs <br /><small>$etape</small>";

        return compact('title', 'form', 'errors');
    }
}