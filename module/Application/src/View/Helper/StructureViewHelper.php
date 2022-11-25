<?php

namespace Application\View\Helper;

use Application\Entity\Db\Structure;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * Description of StructureViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureViewHelper extends AbstractViewHelper
{
    use StructureAwareTrait;


    /**
     *
     * @param Structure $structure
     *
     * @return self
     */
    public function __invoke(Structure $structure = null)
    {
        $this->setStructure($structure);

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
        $entity = $this->getStructure();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Libellé long"                            => $entity->getLibelleLong(),
            "Libellé court"                           => $entity->getLibelleCourt(),
            "N° {$entity->getSource()->getLibelle()}" => $entity->getCode(),
        ];

        $html = "<dl class=\"structure dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink()
    {
        $structure = $this->getStructure();
        if (!$structure) return '';

        if ($structure->getHistoDestruction()) {
            return '<span class="bg-danger"><abbr title="Cette structure n\'existe plus">' . $structure . '</abbr></span>';
        }

        $url = $this->getView()->url('structure/voir', ['structure' => $structure->getId()]);
        $out = '<a href="' . $url . '" class="ajax-modal">' . $structure . '</a>';

        return $out;
    }
}