<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\ElementPedagogique as Entity;
use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Provider\Privilege\Privileges;
use Zend\View\Helper\AbstractHtmlElement;
use Application\Util;

/**
 * Description of ElementPedagogiqueViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueViewHelper extends AbstractHtmlElement
{
    use ElementPedagogiqueAwareTrait;



    /**
     *
     * @param Entity $elementPedagogique
     *
     * @return self
     */
    public function __invoke(Entity $elementPedagogique = null)
    {
        if ($elementPedagogique) $this->setElementPedagogique($elementPedagogique);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString()
    {
        return $this->render();
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function renderDescription()
    {
        $entity = $this->getElementPedagogique();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Code {$entity->getSource()->getLibelle()}"                => $entity->getSourceCode(),
            "Libellé"                                                  => $entity->getLibelle(),
            "StructureService"                                                => $entity->getStructure(),
            "Discipline"                                               => $entity->getDiscipline(),
            "Période d'enseignement"                                   => $entity->getPeriode(),
            "<span title=\"Formation ouverte à distance\">FOAD</span>" => (bool)$entity->getTauxFoad() ? "Oui" : "Non",
            "Régime(s) d'inscription"                                  => $entity->getRegimesInscription(true),
        ];

        if (($autresEtapes = $entity->getEtapes(false))) {
            $vars["Formation principale"]  = $entity->getEtape();
            $vars["Autre(s) formation(s)"] = $this->getView()->htmlList($autresEtapes);
        } else {
            $vars["Formation"] = $entity->getEtape();
        }

        $html = "<dl class=\"element dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        return $html;
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        $entity = $this->getElementPedagogique();

        if (!$entity) {
            return '';
        }

        $html = $this->renderDescription();


        if ($this->getView()->isAllowed($entity,Privileges::ODF_ELEMENT_EDITION)){
            $buttons = '';
            $buttons .= '<a class="btn btn-default ajax-modal" href="' . $this->getView()->url('of/element/modifier', ['elementPedagogique' => $entity->getId()]) . '" data-event="element-pedagogique-modifier"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
            $buttons .= '<a class="btn btn-default ajax-modal" href="' . $this->getView()->url('of/element/supprimer', ['elementPedagogique' => $entity->getId()]) . '" data-event="element-pedagogique-supprimer"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>';
            $html .= "<div class=\"actions\">$buttons</div>";
        }

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink($content = null, $attributes = [])
    {
        $element = $this->getElementPedagogique();
        if (!$element) return '';

        if (!$content) $content = (string)$element;

        $default = [
            'href'       => $this->getView()->url('of/element/voir', ['elementPedagogique' => $element->getId()]),
            'class'      => ['element-pedagogique-link', 'ajax-modal'],
            'id'         => $element->getId(),
        ];

        if ($element->getHistoDestruction()) {
            $default['title']   = 'Cet enseignement n\'existe plus';
            $default['class'][] = 'bg-danger';
        }

        $tag = 'a';
        if (! $this->getView()->isAllowed(Privileges::getResourceId(Privileges::ODF_ELEMENT_VISUALISATION))) $tag = 'span';

        return "<$tag " . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . "</$tag>";
    }



    public function renderAjouterLink($content = '', $attributes = [])
    {
        if (!$content) $content = '<span class="glyphicon glyphicon-plus"></span> Ajouter un enseignement';

        $default = [
            'href'       => $this->getView()->url('of/element/ajouter'),
            'class'      => ['element-pedagogique-ajouter-link', 'ajax-modal', 'iconify', 'btn', 'btn-default'],
            'data-event' => 'element-pedagogique-ajouter',
            'title'      => 'Ajouter un enseignement',
        ];

        return '<a ' . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . '</a>';
    }
}