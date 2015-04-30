<?php

namespace Application\View\Helper\Intervenant;

use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\Intervenant;
use Application\Traits\IntervenantAwareTrait;

/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantViewHelper extends AbstractHtmlElement
{
    use IntervenantAwareTrait;

    /**
     *
     * @param Intervenant $intervenant
     * @return self
     */
    public function __invoke( Intervenant $intervenant = null )
    {
        if ($intervenant) $this->setIntervenant($intervenant);
        return $this;
    }

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
        return $this->renderLink();
    }

    public function renderLink()
    {
        $intervenant = $this->getIntervenant();
        if (! $intervenant) return '';

        if ($intervenant->getHistoDestruction()){
            return '<span class="bg-danger"><abbr title="Cet intervenant a été supprimé de OSE">'.$intervenant.'</abbr></span>';
        }

        $pourl = $this->getView()->url('intervenant/default', ['action' => 'apercevoir', 'intervenant' => $intervenant->getSourceCode()]);
        $out = '<a href="'.$pourl.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$intervenant.'</a>';
        return $out;
    }
}