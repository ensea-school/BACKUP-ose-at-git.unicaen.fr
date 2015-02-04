<?php

namespace Application\View\Helper\OffreFormation;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Etape as Entity;
use Application\Traits\EtapeAwareTrait;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelper extends AbstractHelper
{
    use EtapeAwareTrait;

    /**
     *
     * @param Entity $etape
     * @return self
     */
    public function __invoke( Entity $etape = null )
    {
        if ($etape) $this->setEtape($etape);
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
        $etape = $this->getEtape();
        if (! $etape) return '';

        if ($etape->getHistoDestruction()){
            return '<p class="bg-danger"><abbr title="Cette formation n\'existe plus">'.$etape.'</abbr></p>';
        }

        $url = $this->getView()->url('of/etape/apercevoir', array('id' => $etape->getId()));
        $pourl = $this->getView()->url('of/etape/apercevoir', array('id' => $etape->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$etape.'</a>';
        return $out;
    }
}