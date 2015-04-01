<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Structure;
use Application\Interfaces\StructureAwareInterface;
use Application\Traits\StructureAwareTrait;

/**
 * Description of Structure
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureViewHelper extends AbstractHelper implements StructureAwareInterface
{
    use StructureAwareTrait;

    /**
     *
     * @param Structure $structure
     * @return self
     */
    public function __invoke( Structure $structure = null )
    {
        $this->setStructure($structure);
        return $this;
    }

    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {

    }

    public function renderLink()
    {
        $structure = $this->getStructure();
        if (! $structure) return '';

        if ($structure->getHistoDestruction()){
            return '<p class="bg-danger"><abbr title="Cette structure n\'existe plus">'.$structure.'</abbr></p>';
        }

        $url = $this->getView()->url('structure/default', ['action' => 'voir', 'id' => $structure->getId()]);
        $pourl = $this->getView()->url('structure/default', ['action' => 'apercevoir', 'id' => $structure->getId()]);
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$structure.'</a>';
        return $out;
    }
}