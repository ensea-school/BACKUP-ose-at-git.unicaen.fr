<?php

namespace Application\View\Helper\ServiceReferentiel;

use Laminas\View\Helper\AbstractHelper;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\FonctionReferentielAwareTrait;

/**
 * Description of FonctionReferentielViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FonctionReferentielViewHelper extends AbstractHelper
{
    use FonctionReferentielAwareTrait;

    /**
     *
     * @param FonctionReferentiel $fonctionReferentiel
     *
     * @return self
     */
    public function __invoke(FonctionReferentiel $fonctionReferentiel = null)
    {
        if ($fonctionReferentiel) $this->setFonctionReferentiel($fonctionReferentiel);

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
        $fonctionReferentiel = $this->getFonctionReferentiel();
        if (!$fonctionReferentiel) return '';

        $out = sprintf("<span title=\"%s\">%s</span>", $fonctionReferentiel->getLibelleLong(), $fonctionReferentiel);

        if ($fonctionReferentiel->getHistoDestruction()) {
            return '<span class="bg-danger"><abbr title="Cette fonction référentielle n\'existe plus">' . $out . '</abbr></span>';
        }

        return $out;
    }
}