<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * Description of StructureService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureViewHelper extends AbstractHelper
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
            "Libellé long :"                          => $entity->getLibelleLong(),
            "Libellé court"                           => $entity->getLibelleCourt(),
            "Type de structure"                       => $entity->getType()->getLibelle(),
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

        $url   = $this->getView()->url('structure/default', ['action' => 'voir', 'id' => $structure->getId()]);
        $pourl = $this->getView()->url('structure/default', ['action' => 'apercevoir', 'id' => $structure->getId()]);
        $out   = '<a href="' . $url . '" data-po-href="' . $pourl . '" class="ajax-modal">' . $structure . '</a>';

        return $out;
    }
}