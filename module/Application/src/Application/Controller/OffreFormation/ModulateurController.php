<?php

namespace Application\Controller\OffreFormation;

use Application\Controller\AbstractController;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Exception\DbException;

/**
 * Description of ModulateurController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurController extends AbstractController
{
    use EtapeAwareTrait;



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

        $form   = $this->getFormSaisie();
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
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            } else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Saisie des modulateurs <br /><small>$etape</small>";

        return compact('title', 'form', 'errors');
    }



    /**
     *
     * @return \Application\Form\Service\Saisie
     */
    protected function getFormSaisie()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('EtapeModulateursSaisie');
    }
}