<?php

namespace Application\Controller\OffreFormation;

use Application\Entity\Db\Etape;
use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutSaisieForm;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Common\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeCentreCoutController extends AbstractActionController
{
    use \Application\Service\Traits\ElementPedagogiqueAwareTrait
    ;

    /**
     *
     * @return type
     * @throws RuntimeException
     */
    protected function saisirAction()
    {
        $etape  = $this->context()->mandatory()->etapeFromRoute('id'); /* @var $etape Etape */
        $form   = $this->getFormSaisie();
        $errors = [];

        $form
                ->setAttribute('action', $this->url()->fromRoute(null, [], [], true))
                ->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->em()->flush();
            }
            else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Paramétrage des centres de coûts <br /><small>$etape</small>";

        return compact('etape', 'title', 'form', 'errors');
    }

    /**
     *
     * @return EtapeCentreCoutSaisieForm
     */
    protected function getFormSaisie()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('EtapeCentreCoutSaisieForm');
    }
}