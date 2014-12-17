<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Etablissement;
use Application\Interfaces\EtablissementAwareInterface;
use Application\Traits\EtablissementAwareTrait;

/**
 * Description of Etablissement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementViewHelper extends AbstractHelper implements EtablissementAwareInterface
{
    use EtablissementAwareTrait;

    /**
     *
     * @param Etablissement $etablissement
     * @return self
     */
    public function __invoke( Etablissement $etablissement = null )
    {
        if ($etablissement) $this->setEtablissement($etablissement);
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
        $etablissement = $this->getEtablissement();
        if (! $etablissement) return '';

        if ($etablissement->getHistoDestruction()){
            return '<p class="bg-danger"><abbr title="Cet établissement n\'existe plus">'.$etablissement.'</abbr></p>';
        }

        $url = $this->getView()->url('etablissement/default', array('action' => 'voir', 'id' => $etablissement->getId()));
        $pourl = $this->getView()->url('etablissement/default', array('action' => 'apercevoir', 'id' => $etablissement->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$etablissement.'</a>';
        return $out;
    }
}