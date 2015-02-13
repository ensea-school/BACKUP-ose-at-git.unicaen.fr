<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Form\Element\Select;

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
            return 'Aucun enseignement n\'est associé à cette formation. Il est donc impossible d\'y associer des modulateurs';
        }
        if (0 == $form->countModulateurs()){
            return 'Aucun modulateur ne correspond aux enseignements de cette formation';
        }
        if (empty($typesModulateurs)){
            return 'Aucun modulateur ne peut être associé à cette formation car ils ne sont pas activés pour la structure d\'enseignement correspondante.';
        }

        $displayTypesModulateurs = array();
        foreach( $typesModulateurs as $typeModulateur ){
            if (0 < $form->countModulateurs($typeModulateur->getCode())){
                $displayTypesModulateurs[] = $typeModulateur;
            }
        }

        $form->prepare();
        $res = $this->getView()->form()->openTag($form);
        $res .= '<style>';
        $res .= '.modal-dialog { width: 60%; }';
        $res .= '</style>';
        $res .= '<script type="text/javascript">';
        $res .= ' $(function() { Modulateur.init(); });';
        $res .= '</script>';
        $res .= '<table class="table table-bordered table-extra-condensed">';
        $res .= '<tr>';
        $res .= '<th rowspan="2">Elément</th>';
        foreach( $displayTypesModulateurs as $typeModulateur ){
            $res .= '<th>';
            $res .= $typeModulateur->getLibelle();
            $res .= '</th>';
        }
        $res .= '</tr>';
        $res .= '<tr>';
        foreach( $displayTypesModulateurs as $typeModulateur ){
            $typeModulateurElement = new Select($typeModulateur->getCode());
            //$typeModulateurElement->setLabel($typeModulateur->getLibelle());
            $values = array('' => '');
            foreach( $typeModulateur->getModulateur() as $modulateur ){
                $values[$modulateur->getId()] = (string)$modulateur;
            }
            $typeModulateurElement->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $values ) );
            $typeModulateurElement->setAttribute('class', 'form-control');
            $res .= '<th>';
            $res .= $this->getView()->formSelect( $typeModulateurElement );
//href="javascript:return false;" onclick="Modulateur.setFormValues($(this).data(\'code\'), $($(this).data(\'code\')).val())
            $res .= ' <a class="btn btn-default btn-sm form-set-value" data-code="'.$typeModulateur->getCode().'" title="Appliquer à tous"><span class="glyphicon glyphicon-arrow-down"></span><a>';
            $res .= '</th>';
        }
        $res .= '</tr>';
        foreach( $elements as $element ){
            $res .= '<tr>';
            $res .= '<th>'.$element.'</th>';
            $formElement = $form->get('EL'.$element->getId());
            $res .= $this->getView()->elementModulateursSaisieFieldset()->render( $formElement, $displayTypesModulateurs, true );
            $res .= '</tr>';
        }
        $res .= '</table>';
        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();
        return $res;
    }
}