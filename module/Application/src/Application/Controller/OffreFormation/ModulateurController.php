<?php

namespace Application\Controller\OffreFormation;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Exception\DbException;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of ModulateurController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModulateurController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    protected function saisirAction()
    {
        $etape = $this->context()->etapeFromRoute('id');
        /* @var $etape \Application\Entity\Db\Etape */

        if (! $etape){
            throw new \Common\Exception\RuntimeException('La formation n\'a pas été spécifiée ou bien elle est invalide.');
        }

        $form    = $this->getFormSaisie();
        $errors  = array();

        $form->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $elements = $etape->getElementPedagogique();
                    foreach( $elements as $element ){
                        if ($element->getHasChanged()){
                            $this->getServiceElementPedagogique()->save($element);
                        }
                    }
                }
                catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        $title = "Saisie des modulateurs <br /><small>$etape</small>";
        
        return compact('title', 'form', 'errors');
    }

    /**
     * Retourne le service ElementPedagogique.
     *
     * @return ElementPedagogiqueService
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
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