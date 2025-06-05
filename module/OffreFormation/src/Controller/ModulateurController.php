<?php

namespace OffreFormation\Controller;

use Application\Controller\AbstractController;
use OffreFormation\Form\Traits\EtapeModulateursSaisieAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of ModulateurController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurController extends AbstractController
{
    use EtapeServiceAwareTrait;
    use EtapeModulateursSaisieAwareTrait;
    use BddAwareTrait;



    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \OffreFormation\Entity\Db\ElementModulateur::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \OffreFormation\Entity\Db\ElementPedagogique::class,
        ]);

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape \OffreFormation\Entity\Db\Etape */

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

        $this->getBdd()->materializedView()->refresh('MV_MODULATEUR');

        return compact('title', 'form', 'errors');
    }
}