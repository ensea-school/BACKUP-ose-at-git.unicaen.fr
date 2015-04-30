<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutSaisieForm;
use Application\Entity\Db\ElementPedagogique;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Select;

/**
 * Dessine le formulaire de type EtapeCentreCoutSaisieForm.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see EtapeCentreCoutSaisieForm
 */
class FormEtapeCentreCoutSaisieHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\ElementPedagogiqueAwareTrait
    ;

    /**
     * @var EtapeCentreCoutSaisieForm
     */
    protected $form;

    /**
     *
     * @param EtapeCentreCoutSaisieForm $form
     * @return self|string
     */
    public function __invoke(EtapeCentreCoutSaisieForm $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }

    /**
     * Rendu du formulaire
     *
     * @param EtapeCentreCoutSaisieForm $form
     * @return string
     */
    public function render(EtapeCentreCoutSaisieForm $form)
    {
        $elements    = $this->getServiceElementPedagogique()->getList($this->getServiceElementPedagogique()->finderByEtape($form->getEtape())); /* @var $elements ElementPedagogique[] */
        $typesHeures = $form->getTypesHeures();

        if (empty($elements)) {
            return 'Aucun enseignement n\'est associé à cette formation. Il est donc impossible de paramétrer les centres de coûts.';
        }
        if (empty($typesHeures)) {
            return "Aucun des éléments de cette formation n'est associé au moindre type d'heures éligible.";
        }

        $form->prepare();
        $res = $this->getView()->form()->openTag($form);
        $res .= '<style>';
        $res .= '.modal-dialog { width: 60%; }';
        $res .= '/*select.type-heures { display: inline; width: 75%; }*/';
        $res .= 'th.element-pedagogique, td.element-pedagogique { width: 50%; }';
        $res .= '</style>';
        $res .= '<script type="text/javascript">';
        $res .= ' $(function() { EtapeCentreCout.init(); });';
        $res .= '</script>';
        
        $res .= '<table class="table table-bordered table-extra-condensed">';
        
        $res .= '<tr>';
        $res .= '<th rowspan="2" class="element-pedagogique">Elément</th>';
        foreach ($typesHeures as $th) {
            $res .= '<th>';
            $res .= $th->getLibelleCourt();
            $res .= '</th>';
        }
        $res .= '</tr>';
        
        $res .= '<tr>';
        foreach ($typesHeures as $th) {
            $thElement = new Select($th->getCode());
            $thElement
                    ->setValueOptions(['' => '(Aucun)'] + $form->getCentresCoutsToArray($th))
                    ->setAttribute('class', 'form-control type-heures header-select selectpicker');
            
            $res .= '<th>';
            $res .= $this->getView()->formSelect($thElement);
            $res .= ' <button type="button" class="btn btn-default btn-sm form-set-value pull-right" data-code="' . $th->getCode() . '" title="Appliquer à tous"><span class="glyphicon glyphicon-arrow-down"></span></button>';
            $res .= '</th>';
        }
        $res .= '</tr>';
        
        foreach ($elements as $element) {
            $res .= '<tr>';
            $res .= '<th class="element-pedagogique">' . $element . '</th>';
            $formElement = $form->get('EL' . $element->getId());
            $res .= $this->getView()->fieldsetElementCentreCoutSaisie()->render($formElement, $typesHeures, true);
            $res .= '</tr>';
        }
        
        $res .= '</table>';
        
        $res .= $this->getView()->formHidden($form->get('id'));
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->form()->closeTag();
        
        return $res;
    }
}