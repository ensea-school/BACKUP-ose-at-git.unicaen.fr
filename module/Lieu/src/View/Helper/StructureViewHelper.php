<?php

namespace Lieu\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;

/**
 * Description of StructureViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureViewHelper extends AbstractHtmlElement
{
    use StructureAwareTrait;


    public function __invoke(?Structure $structure = null): StructureViewHelper
    {
        $this->setStructure($structure);

        return $this;
    }



    public function __toString(): string
    {
        return $this->render();
    }



    public function render(): string
    {
        $entity = $this->getStructure();

        if (!$entity) {
            return '';
        }

        $vars = [
            "Libellé long"                            => $entity->getLibelleLong(),
            "Libellé court"                           => $entity->getLibelleCourt(),
            "N° {$entity->getSource()->getLibelle()}" => $entity->getCode(),
            "Adresse" => '<pre>'.$entity->getAdresse(false).'</pre>',
        ];

        $html = "<dl class=\"structure dl-horizontal\">\n";
        foreach ($vars as $key => $value) {
            $html .= "\t<dt>$key :</dt><dd>$value</dd>\n";
        }
        $html .= "</dl>";

        $html .= $this->getView()->historique($entity);

        return $html;
    }



    public function renderLink(): string
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