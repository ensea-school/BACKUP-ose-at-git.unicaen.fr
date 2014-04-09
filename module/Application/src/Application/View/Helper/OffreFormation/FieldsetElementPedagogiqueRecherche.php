<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset;
use Zend\View\Helper\AbstractHelper;

/**
 * Description of FieldsetElementPedagogiqueRecherche
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FieldsetElementPedagogiqueRecherche extends AbstractHelper
{
    /**
     * @var ElementPedagogiqueRechercheFieldset 
     */
    protected $fieldset;
    
    protected $structureElement;
    protected $niveauElement;
    protected $etapeElement;
    protected $elementElement;
    
    /**
     * 
     * @param ElementPedagogiqueRechercheFieldset $fieldset
     * @return FieldsetElementPedagogiqueRecherche|string
     */
    public function __invoke(ElementPedagogiqueRechercheFieldset $fieldset = null)
    {
        if (null === $fieldset) {
            return $this;
        }
        
        return $this->render($fieldset);
    }
    
    /**
     * 
     * @param ElementPedagogiqueRechercheFieldset $fieldset
     */
    public function render(ElementPedagogiqueRechercheFieldset $fieldset)
    {
        $this->fieldset         = $fieldset;
        $this->structureElement = $fieldset->get('structure');
        $this->niveauElement    = $fieldset->get('niveau');
        $this->etapeElement     = $fieldset->get('etape');
        $this->elementElement   = $fieldset->get('element');
        
        $this->structureElement->setAttribute('id', uniqid('structure'));
        $this->niveauElement   ->setAttribute('id', uniqid('niveau'));
        $this->etapeElement    ->setAttribute('id', uniqid('etape'));
        //NB: $this->elementElement possède déjà un id DOM
        
        $helper = $this->getView()->formControlGroup();
        
        $rowTemplate = $rowArgs = array();
        if ($this->fieldset->getStructureEnabled()) {
            $rowTemplate[] = '<div class="col-md-2">%s</div>';
            $rowArgs[]     = $helper($this->structureElement);
        }
        if ($this->fieldset->getNiveauEnabled()) {
            $rowTemplate[] = '<div class="col-md-2">%s</div>';
            $rowArgs[]     = $helper($this->niveauElement);
        }
        if ($this->fieldset->getEtapeEnabled()) {
            $rowTemplate[] = '<div class="col-md-3">%s</div>';
            $rowArgs[]     = $helper($this->etapeElement);
        }
        $rowTemplate[] = '<div class="col-md-5">%s</div>';
        $rowArgs[]     = $helper($this->elementElement);

        $html = '';
        $html .= vsprintf('<div class="row">' . implode(PHP_EOL, $rowTemplate) . '</div>', $rowArgs);
        
        $html .= '<script>' . $this->getJs() . '</script>';
        
        return $html;
    }
    
    /**
     * 
     * @return string
     */
    protected function getJs()
    {
        $js = <<<EOS
$(function() {
    var str      = $("#{$this->structureElement->getAttribute('id')}");
    var niv      = $("#{$this->niveauElement->getAttribute('id')}");
    var eta      = $("#{$this->etapeElement->getAttribute('id')}");
    var ele      = $("#{$this->elementElement->getAttribute('id')}-autocomplete");
    var elements = new Array();

    if (str.length) {
        elements.push(str.data({ 
            updateUrl: '{$this->fieldset->getStructuresSourceUrl()}',
            paramName: "{$this->fieldset->getStructureName()}",
            initValue: "{$this->structureElement->getValue()}"
        }));
    }
    if (niv.length) {
        elements.push(niv.data({ 
            updateUrl: '{$this->fieldset->getNiveauxSourceUrl()}',
            paramName: "{$this->fieldset->getNiveauName()}",
            initValue: "{$this->niveauElement->getValue()}"
        }));
    }
    if (eta.length) {
        elements.push(eta.data({ 
            updateUrl: '{$this->fieldset->getEtapesSourceUrl()}',
            paramName: "{$this->fieldset->getEtapeName()}",
            initValue: "{$this->etapeElement->getValue()}"
        }));
    }
    if (ele.length) {
        elements.push(ele.data({ 
            updateUrl: '{$this->fieldset->getElementsSourceUrl()}',
        }));
    }
        
    $.each(elements, function (index, element) {
        element.change(function() {
            var next = elements[index+1];
            if (next && next.length) updateElement(next);
        });
    });
    
    updateElement(elements[0]);
    
    
    function updateElement(element)
    {
        element.is("select") ? updateSelect(element) : updateAutocomplete(element);
    }
    
    function updateSelect(element)
    {
        var url       = getUrl(element.data('updateUrl'));
        var value     = element.data('initValue');
        var selection = value ? value : element.val();
        element.css('opacity', '0.5').append($("<option/>").attr("value", 'temp').text("Patientez, svp...")).val('temp');
        $.get(url, function(data) {
            updateSelectOptions(element, data); 
            element.val(selection).css('opacity', '1').change();
        });
    }
    
    function updateAutocomplete(element)
    {
        var url = getUrl(element.data('updateUrl'));
        element.autocomplete("option", "source", url);
//        element.autocomplete("search");
//        element.change(); // inutile en fait
    }
    
    function getUrl(urlTemplate)
    {
        var pattern;
        var url = urlTemplate;
        $.each(elements, function (index, element) {
            if (element.data('paramName')) {
                pattern = new RegExp(element.data('paramName') + "=(\\\w+)", "g");
                url = url.replace(pattern, element.data('paramName') + "=" + element.val());
            }
        });
        pattern = new RegExp("(\\\w+)=__(\\\w+)__", "g"); 
        url = url.replace(pattern, "$1=");
        return url;
    }
});

function updateSelectOptions(select, options)
{
    $("option[value!='']", select).remove();
    $.each(options, function(key, value) {
        select.append($("<option/>").attr("value", key).text(value));
    });
}
EOS;
        return $js;
    }
}