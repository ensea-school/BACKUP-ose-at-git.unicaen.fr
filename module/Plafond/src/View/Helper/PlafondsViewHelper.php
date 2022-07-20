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
        $contenu = $this->affichage();
        if (!empty($contenu)) {
            $params = [
                'perimetre'         => $this->getServicePlafond()->entityToPerimetreCode($this->entity),
                'id'                => $this->entity->getId(),
                'typeVolumeHoraire' => $this->typeVolumeHoraire->getId(),
            ];

            $attrs = [
                'class'    => 'plafonds',
                'data-url' => $this->getView()->url('plafond/plafonds', $params),
            ];

            return $this->getView()->tag('div', $attrs)->html($contenu);
        } else {
            return '';
        }
    }



    public function affichage(): string
    {
        $aff = false;

        $html = $this->getView()->tag('h4')->text('Plafonds');
        foreach ($this->plafonds as $plafond) {
            if ($plafond->getHeures() > 0) {
                $html .= $this->affichagePlafond($plafond);
                $aff  = true;
            }
        }

        if (!$aff) return '';

        return $this->getView()->tag('div', ['class' => 'alert alert-info'])->html($html);
    }



    protected function affichagePlafond(PlafondControle $plafond)
    {
        $t = $this->getView()->tag();

        $labAttrs = ['class' => ['label']];
        if ($plafond->isBloquant()) {
            $labAttrs['class'][] = 'label-danger';
            $labAttrs['title']   = 'Plafond bloquant';
        } else {
            $labAttrs['class'][] = 'label-info';
            $labAttrs['title']   = 'Plafond informatif';
        }


        $text = '';

        $max = $plafond->getPlafond() + $plafond->getDerogation();
        if ($plafond->getHeures() > $max) {
            $max = $plafond->getHeures();
            if ($plafond->getHeures() > 0) {
                if ($plafond->getPlafond() == 0) {
                    $text = floatToString($plafond->getHeures()) . 'h pour aucune autorisée';
                } else {
                    $text = floatToString($plafond->getHeures()) . 'h pour ' . floatToString($plafond->getPlafond() + $plafond->getDerogation()) . ' max.';
                }
            }

            if ($plafond->isBloquant()) {
                $color = 'danger';
            } else {
                $color = 'warning';
            }
        } else {
            if ($plafond->isBloquant()) {
                $color = 'info';
            } else {
                $color = 'success';
            }
        }

        if ($max > 0) {
            $progression = ceil($plafond->getHeures() * 100 / $max);
        } else {
            $progression = 0;
        }

        if (!$text) {
            if ($progression > 49) {
                $text = floatToString($plafond->getHeures()) . 'h, '
                    . floatToString($max - $plafond->getHeures()) . ' dispo.';
            } elseif ($progression > 10) {
                $text = floatToString($plafond->getHeures()) . 'h';
            }
        }

        $html = '';

        $html .= $t('div', ['class' => 'col-md-4'])->html(
            $t('div', [
                'class' => 'progress',
            ])->html(
                $t('div', [
                    'class'         => 'progress-bar progress-bar-striped progress-bar-' . $color,
                    'role'          => 'progressbar',
                    'aria-valuenow' => $progression,
                    'aria-valuemin' => 0,
                    'aria-valuemax' => 100,
                    'style'         => 'width:' . $progression . '%',
                ])->text($text)
            )
        );

        $html .= $t('div', ['class' => 'col-md-8'])->html(
            $plafond->getMessage()
            . ' '
            . $t('span', $labAttrs)->text('n° ' . $plafond->getNumero())
        );

        return $t('div', ['class' => 'row plafond'])->html($html);
    }
}