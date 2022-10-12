<?php

namespace Service\View\Helper;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;


/**
 * Description of HorodatageViewHelper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class HorodatageViewHelper extends AbstractHtmlElement
{
    use IntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;

    protected bool $referentiel = false;



    public function __invoke(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant, bool $referentiel = false): HorodatageViewHelper
    {
        $this->setTypeVolumeHoraire($typeVolumeHoraire);
        $this->setIntervenant($intervenant);
        $this->referentiel = $referentiel;

        return $this;
    }



    public function __toString(): string
    {
        return $this->render();
    }



    public function render(bool $withDiv = true): string
    {
        $r = $this->getView()->partial('service/service/horodatage', [
            'typeVolumeHoraire' => $this->getTypeVolumeHoraire(),
            'intervenant'       => $this->getIntervenant(),
            'referentiel'       => $this->referentiel,
        ]);

        if ($withDiv) {
            $url = $this->getView()->url('service/horodatage', [
                'intervenant'       => $this->getIntervenant()->getId(),
                'typeVolumeHoraire' => $this->getTypeVolumeHoraire()->getId(),
                'referentiel'       => $this->referentiel ? '1' : '0',
            ]);

            return $this->getView()->tag('div', ['class' => 'horodatage', 'data-url' => $url])->html($r);
        } else {
            return $r;
        }
    }

}