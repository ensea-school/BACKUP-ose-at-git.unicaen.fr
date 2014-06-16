<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of EtapeModulateursSaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisieForm extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;


    /**
     *
     * @param EtapeModulateursSaisie $form
     * @return self|string
     */
    public function __invoke(EtapeModulateursSaisie $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }

    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     * @return string
     */
    public function render(EtapeModulateursSaisie $form)
    {
        $sel = $this->getServiceLocator()->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $sel \Application\Service\ElementPedagogique */

        $elements = $sel->getList( $sel->finderByEtape($form->getEtape() ) );
        $typesModulateurs = $form->getTypesModulateurs();

        if (empty($elements)){
            return 'Aucun élément n\'est associé à cette étape. Il est donc impossible d\'y associer des modulateurs';
        }
        if (0 == $form->countModulateurs()){
            return 'Aucun modulateur ne correspond aux éléments de cette étape';
        }
        if (empty($typesModulateurs)){
            return 'Aucun modulateur ne peut être associé à cette étape car ils ne sont pas activés pour la structure d\'enseignement correspondante.';
        }

        $form->prepare();
        $res = $this->getView()->form()->openTag($form);
        $res .= '<style>';
        $res .= '.modal-dialog { width: 60%; }';
        $res .= '</style>';
        $res .= '<table class="table table-bordered table-condensed">';
        $res .= '<tr>';
        $res .= '<th>Elément</th>';
        foreach( $typesModulateurs as $typeModulateur ){
            $res .= '<th>';
            $res .= $typeModulateur->getLibelle();
            $res .= '</th>';
        }
        $res .= '</tr>';
        foreach( $elements as $element ){
            $res .= '<tr>';
            $res .= '<th>'.$element.'</th>';
            $formElement = $form->get('EL'.$element->getId());
            $res .= $this->getView()->elementModulateursSaisieFieldset()->render( $formElement, $typesModulateurs, true );
            $res .= '</tr>';
        }
        $res .= '</table>';
        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();
        return $res;
    }
}