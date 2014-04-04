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
        
        $template = <<<EOS

<div class="row">
    <div class="col-md-2">
        %s
    </div>
    <div class="col-md-2">
        %s
    </div>
    <div class="col-md-3">
        %s
    </div>
    <div class="col-md-5">
        %s
    </div>
</div>
EOS;
        $html = '';
        
        $html .= sprintf($template, 
                $helper($this->structureElement),
                $helper($this->niveauElement),
                $helper($this->etapeElement),
                $helper($this->elementElement));
        
        $html .= '<script>' . $this->getJs() . '</script>';
        
        return $html;
    }
    
    protected function getJs()
    {
        $js = <<<EOS
$(function() {
    var str = $("#{$this->structureElement->getAttribute('id')}")           .data('updateUrl', '{$this->fieldset->getStructuresSourceUrl()}');
    var niv = $("#{$this->niveauElement->getAttribute('id')}")              .data('updateUrl', '{$this->fieldset->getNiveauxSourceUrl()}');
    var eta = $("#{$this->etapeElement->getAttribute('id')}")               .data('updateUrl', '{$this->fieldset->getEtapesSourceUrl()}');
    var ele = $("#{$this->elementElement->getAttribute('id')}-autocomplete").data('updateUrl', '{$this->fieldset->getElementsSourceUrl()}');
    var selects = [ str, niv, eta ];
    
    updateSelect(str);
    
    str.change(function() { updateSelect(niv); });
    niv.change(function() { updateSelect(eta); });
    eta.change(function() { updateAutocomplete(ele); });
    
    function updateSelect(select)
    {
        var url = getUrl(select.data('updateUrl'));
        select.css('opacity', '0.5');
        select.append($("<option/>").attr("value", 'temp').text("Patientez, svp...")).val('temp');
        $.get(url, function(data) { updateSelectOptions(select, data); select.css('opacity', '1'); select.change(); });
    }
    
    function updateAutocomplete(element)
    {
        var url = getUrl(element.data('updateUrl'));
        element.autocomplete("option", "source", url);
        element.autocomplete("search");
        element.change(); // inutile
    }
    
    function getUrl(urlTemplate)
    {
        var pattern;
        var url = urlTemplate;
        $.each(selects, function (key, select) {
            pattern = new RegExp(select.attr('name') + "=(\\\w+)", "g");
            url = url.replace(pattern, select.attr('name') + "=" + select.val());
        });
        pattern = new RegExp("(\\\w+)=__(\\\w+)__", "g"); 
        url = url.replace(pattern, "$1=");
        return url;
    }
});
    
function updateSelectOptions(select, options)
{
    var selection = select.val();
    $("option:gt(0)", select).remove();
    $.each(options, function(key, value) {
        select.append($("<option/>").attr("value", key).text(value));
    });
    select.val(selection);
}
EOS;
        return $js;
    }
}