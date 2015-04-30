<?php

namespace Application\View\Helper\OffreFormation;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\ElementPedagogique as Entity;
use Application\Traits\ElementPedagogiqueAwareTrait;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogique extends AbstractHelper
{
    use ElementPedagogiqueAwareTrait;

    /**
     *
     * @param Entity $elementPedagogique
     * @return self
     */
    public function __invoke( Entity $elementPedagogique = null )
    {
        if ($elementPedagogique) $this->setElementPedagogique($elementPedagogique);
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

    public function renderLink( $format='original' )
    {
        $element = $this->getElementPedagogique();
        if (! $element) return '';

        if ($element->getHistoDestruction()){
            return '<span class="bg-danger"><abbr title="Cet élément pédagogique n\'existe plus">'.$element.'</abbr></span>';
        }

        switch( $format ){
            case 'libelle':
                $str = $element->getLibelle();
            break;
            default:
                $str = (string)$element;
        }

        $url = $this->getView()->url('of/element/voir', ['id' => $element->getId()]);
        $pourl = $this->getView()->url('of/element/apercevoir', ['id' => $element->getId()]);
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$str.'</a>';
        return $out;
    }
}