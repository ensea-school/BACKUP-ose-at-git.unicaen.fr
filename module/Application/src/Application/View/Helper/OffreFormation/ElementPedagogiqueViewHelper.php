<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\ElementPedagogique as Entity;
use Application\Traits\ElementPedagogiqueAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;

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
    public function render()
    {
        $entity = $this->getElementPedagogique();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Code {$entity->getSource()->getLibelle()}"                => $entity->getSourceCode(),
            "Libellé"                                                  => $entity->getLibelle(),
            "Structure"                                                => $entity->getStructure(),
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

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink($format = 'original')
    {
        $element = $this->getElementPedagogique();
        if (!$element) return '';

        switch ($format) {
            case 'libelle':
                $str = $element->getLibelle();
                break;
            default:
                $str = (string)$element;
        }

        $url   = $this->getView()->url('of/element/voir', ['elementPedagogique' => $element->getId()]);
        $out   = '<a href="' . $url . '" data-po-href="' . $url . '" class="ajax-modal">' . $str . '</a>';

        if ($element->getHistoDestruction()) {
            return '<span class="bg-danger"><abbr title="Cet élément pédagogique n\'existe plus">' . $out . '</abbr></span>';
        } else {
            return $out;
        }
    }
}