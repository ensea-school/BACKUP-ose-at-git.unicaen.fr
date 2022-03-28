<?php

namespace Plafond\View\Helper;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\VolumeHoraire;
use Laminas\View\Helper\AbstractHtmlElement;
use Plafond\Entity\PlafondControle;
use Plafond\Service\PlafondServiceAwareTrait;


/**
 * Description of PlafondsViewHelper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PlafondsViewHelper extends AbstractHtmlElement
{
    use PlafondServiceAwareTrait;

    private Structure|Intervenant|ElementPedagogique|VolumeHoraire|FonctionReferentiel $entity;

    private TypeVolumeHoraire                                                          $typeVolumeHoraire;

    /**
     * @var PlafondControle[]
     */
    private array $plafonds;



    /**
     *
     * @return self
     */
    public function __invoke(Structure|Intervenant|ElementPedagogique|VolumeHoraire|FonctionReferentiel $entity, TypeVolumeHoraire $typeVolumeHoraire)
    {
        $this->entity            = $entity;
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->plafonds          = $this->getServicePlafond()->controle($entity, $typeVolumeHoraire);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString(): string
    {
        return $this->render();
    }



    public function render(): string
    {
        $params = [
            'perimetre'         => $this->getServicePlafond()->entityToPerimetreCode($this->entity),
            'id'                => $this->entity->getId(),
            'typeVolumeHoraire' => $this->typeVolumeHoraire->getId(),
        ];

        $attrs = [
            'class'    => 'plafonds',
            'data-url' => $this->getView()->url('plafond/plafonds', $params),
        ];

        return $this->getView()->tag('div', $attrs)->html($this->affichage());
    }



    public function affichage(): string
    {
        var_dump($this->plafonds);

        return '';
    }

}