<?php

namespace Application\View\Helper\Formule;

use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\FormuleResultat;
use Application\Interfaces\FormuleResultatAwareInterface;
use Application\Traits\FormuleResultatAwareTrait;

/**
 * Description of TotauxHetdViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TotauxHetdViewHelper extends AbstractHtmlElement implements FormuleResultatAwareInterface
{
    use FormuleResultatAwareTrait;

    /**
     * Helper entry point.
     *
     * @param FormuleResultat $formuleResultat
     * @return self
     */
    final public function __invoke( FormuleResultat $formuleResultat )
    {
        $this->setFormuleResultat( $formuleResultat );
        return $this;
    }

    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    public function getRefreshUrl()
    {
        $fr = $this->getFormuleResultat();
        return $this->getView()->url(
            'intervenant/formule-totaux-hetd',
            ['intervenant' => $fr->getIntervenant()->getSourceCode()],
            ['query' => [
                'typeVolumeHoraire' => $fr->getTypeVolumeHoraire()->getId(),
                'etatVolumeHoraire' => $fr->getEtatVolumeHoraire()->getId(),
            ] ],
            true
        );
    }

    public function render()
    {
        $fr = $this->getFormuleResultat();

        $attrs = [
            'id'        => 'formule-totaux-hetd',
            'data-url'  => $this->getRefreshUrl()
        ];

        ob_start();
        ?>
        <div <?php echo $this->htmlAttribs($attrs) ?>>
        <h1>Totaux <abbr title="Heures Équivalent TD">HETD</abbr></h1>

        <table class="table table-bordered" style="width:auto;">
            <?php if ($fr->getServiceDu() > 0): ?>
            <tr><th>Service Du</th>
                <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getServiceDu()) ?></td></tr>
            <?php endif; ?>

            <tr><th>Service assuré</th>
                <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getServiceAssure()) ?></td></tr>

            <?php if ($fr->getIntervenant()->estPermanent()): ?>

                <?php if ($fr->getEnseignements() > 0): ?>
                <tr><th style="padding-left:5em">Dont enseignements</th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getEnseignements()) ?></td></tr>
                <?php endif; ?>

                <?php if ($fr->getReferentiel() > 0): ?>
                <tr><th style="padding-left:5em">Dont référentiel</th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getReferentiel()) ?></td></tr>
                <?php endif; ?>

            <?php endif; ?>

            <?php if ($fr->getHeuresComplTotal() > 0) : ?>

                <?php if ($fr->getIntervenant()->estPermanent()): ?>
                <tr><th>Heures complémentaires</th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getHeuresComplTotal()) ?></td></tr>
                <?php endif; ?>

                <?php if ($fr->getHeuresComplFi() > 0): ?>
                <tr><th style="padding-left:5em">Dont <abbr title="Formation initiale">FI</abbr></th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getHeuresComplFi()) ?></td></tr>
                <?php endif; ?>

                <?php if ($fr->getHeuresComplFa() > 0): ?>
                <tr><th style="padding-left:5em">Dont <abbr title="Formation en apprentissage">FA</abbr></th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getHeuresComplFa()) ?></td></tr>
                <?php endif; ?>

                <?php if ($fr->getHeuresComplFc() > 0): ?>
                <tr><th style="padding-left:5em">Dont <abbr title="Formation continue">FC</abbr></th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getHeuresComplFc()) ?></td></tr>
                <?php endif; ?>

                <?php if ($fr->getHeuresComplReferentiel() > 0): ?>
                <tr><th style="padding-left:5em">Dont référentiel</th>
                    <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getHeuresComplReferentiel()) ?></td></tr>
                <?php endif; ?>

            <?php endif; ?>
            <?php if ($fr->getSousService() > 0) : ?>

            <tr><th>Sous-service</th>
                <td style="text-align: right"><?php echo \Common\Util::formattedHeures($fr->getSousService()) ?></td></tr>

            <?php endif; ?>
        </table>
        </div>
        <?php
        $result = ob_get_clean();
        return $result;
    }
}