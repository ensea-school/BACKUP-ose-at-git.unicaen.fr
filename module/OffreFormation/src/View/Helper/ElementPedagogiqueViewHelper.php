<?php

namespace OffreFormation\View\Helper;

use Application\Provider\Privilege\Privileges;
use Application\Util;
use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Entity\Db\ElementPedagogique as Entity;
use OffreFormation\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

/**
 * Description of ElementPedagogiqueViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueViewHelper extends AbstractHtmlElement
{
    use ElementPedagogiqueAwareTrait;
    use SchemaServiceAwareTrait;


    /**
     *
     * @param Entity $elementPedagogique
     *
     * @return self
     */
    public function __invoke(?Entity $elementPedagogique = null)
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
            "Structure"                                                => $entity->getStructure(),
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
        $entity  = $this->getElementPedagogique();
        $schemas = $this->getServiceSchema();

        if (!$entity || !$schemas) {
            return '';
        }

        $html = $this->renderDescription();

        $buttons = '';
        if ($this->getView()->isAllowed($entity, Privileges::ODF_ELEMENT_EDITION)) {
            $buttons .= '<a class="btn btn-secondary" href="' . $this->getView()->url('of/element/modifier', ['elementPedagogique' => $entity->getId()]) . '" data-event="element-pedagogique-modifier"><i class="fas fa-pencil"></i> Modifier</a>';
            $buttons .= '<a class="btn btn-secondary" href="' . $this->getView()->url('of/element/supprimer', ['elementPedagogique' => $entity->getId()]) . '" data-event="element-pedagogique-supprimer"><i class="fas fa-trash-can"></i> Supprimer</a>';
        }
        if ($this->getView()->isAllowed($entity, Privileges::ODF_ELEMENT_SYNCHRONISATION) && $this->getServiceSchema()->isImportedEntity($entity)) {
            $buttons .= '<a class="btn btn-secondary" href="' . $this->getView()->url('of/element/synchronisation', ['elementPedagogique' => $entity->getId()]) . '" data-event="element-pedagogique-synchronisation"><i class="fas fa-arrows-rotate"></i> Synchronisation</a>';
        }
        if ($buttons) $html .= "<div class=\"actions\">$buttons</div>";

        return $html;
    }



    public function renderLink($content = null, $attributes = [])
    {
        $element = $this->getElementPedagogique();
        if (!$element) return '';

        if (!$content) $content = (string)$element;

        $default = [
            'href'  => $this->getView()->url('of/element/voir', ['elementPedagogique' => $element->getId()]),
            'class' => ['element-pedagogique-link', 'ajax-modal'],
            'id'    => (string)$element->getId(),
        ];

        if ($element->getHistoDestruction()) {
            $default['title']   = 'Cet enseignement n\'existe plus';
            $default['class'][] = 'bg-danger';
        }

        $tag = 'a';
        if (!$this->getView()->isAllowed(Privileges::getResourceId(Privileges::ODF_ELEMENT_VISUALISATION))) $tag = 'span';

        return "<$tag " . $this->htmlAttribs(Util::mergeHtmlAttribs($default, $attributes)) . '>' . $content . "</$tag>";
    }
}