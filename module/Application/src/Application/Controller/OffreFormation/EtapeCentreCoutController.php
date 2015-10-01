<?php

namespace Application\Controller\OffreFormation;

use Application\Entity\Db\Etape;
use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;
use Common\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeCentreCoutController extends AbstractActionController
{
    use \Application\Service\Traits\ElementPedagogiqueAwareTrait,
        \Application\Service\Traits\ContextAwareTrait;



    /**
     *
     * @return type
     * @throws RuntimeException
     */
    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('annee')->init(
            [
                'Application\Entity\Db\ElementPedagogique',
                'Application\Entity\Db\CentreCout',
                'Application\Entity\Db\CentreCoutEp'
            ],
            $this->getServiceContext()->getAnnee()
        );

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape Etape */
        $form = $this->getForm();
        $errors = [];

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->em()->flush();
            } else {
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Paramétrage des centres de coûts <br /><small>$etape</small>";

        return compact('etape', 'title', 'form', 'errors');
    }



    /**
     *
     * @return EtapeCentreCoutForm
     */
    protected function getForm()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('EtapeCentreCoutForm');
    }
}