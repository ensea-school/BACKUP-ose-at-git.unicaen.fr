<?php

namespace Formule\View\Helper;

use Formule\Entity\Db\FormuleResultat;
use Laminas\View\Helper\AbstractHtmlElement;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of TotauxHetdViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TotauxHetdViewHelper extends AbstractHtmlElement
{
    use EntityManagerAwareTrait;

    protected ?FormuleResultat $formuleResultat = null;



    /**
     * @param FormuleResultat $formuleResultat
     *
     * @return self
     */
    public function setFormuleResultat(?FormuleResultat $formuleResultat)
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
    }



    public function getFormuleResultat(): ?FormuleResultat
    {
        return $this->formuleResultat;
    }



    /**
     * Helper entry point.
     *
     * @param FormuleResultat $formuleResultat
     *
     * @return self
     */
    final public function __invoke(FormuleResultat $formuleResultat)
    {
        $this->setFormuleResultat($formuleResultat);

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
            'intervenant/formule-totaux-hetd', [
            'intervenant'       => $fr->getIntervenant()->getId(),
            'typeVolumeHoraire' => $fr->getTypeVolumeHoraire()->getId(),
            'etatVolumeHoraire' => $fr->getEtatVolumeHoraire()->getId(),
        ]);
    }



    public function render()
    {
        $fr = $this->getFormuleResultat();

        $attrs = [
            'id'       => 'formule-totaux-hetd',
            'data-url' => $this->getRefreshUrl(),
        ];

        ob_start();
        ?>
        <div <?= $this->htmlAttribs($attrs) ?>>
            <h1>Totaux <abbr title="Heures Équivalent TD">HETD</abbr></h1>

            <table class="table table-bordered" style="width:auto;">
                <?php if ($fr->getServiceDu() > 0): ?>
                    <tr>
                        <th>Service Dû</th>
                        <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getServiceDu()) ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($fr->getHeuresService() > 0): ?>
                    <tr>
                        <th>Service assuré</th>
                        <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresService()) ?></td>
                    </tr>

                    <?php if ($fr->getHeuresServiceFa() + $fr->getHeuresServiceFc() + $fr->getHeuresServiceFi() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont enseignements</th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresServiceFa() + $fr->getHeuresServiceFc() + $fr->getHeuresServiceFi()) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($fr->getHeuresServiceReferentiel() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont référentiel</th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresServiceReferentiel()) ?></td>
                        </tr>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if ($fr->getHeuresCompl() > 0) : ?>

                    <tr>
                        <th><?= ($fr->getServiceDu() > 0) ? 'Heures complémentaires' : 'Service assuré' ?></th>
                        <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresCompl()) ?></td>
                    </tr>

                    <?php if ($fr->getHeuresComplFi() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont <abbr title="Formation initiale">FI</abbr></th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresComplFi()) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($fr->getHeuresComplFa() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont <abbr title="Formation en apprentissage">FA</abbr></th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresComplFa()) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($fr->getHeuresComplFc() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont <abbr title="Formation continue">FC</abbr></th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresComplFc()) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($fr->getHeuresComplFcMajorees() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont <abbr
                                        title="Rémunération FC au titre de l'article D714-60 du code de l’Éducation">rémunération
                                    FC D714-60</abbr></th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresComplFcMajorees()) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($fr->getHeuresComplReferentiel() > 0): ?>
                        <tr>
                            <th style="padding-left:5em">Dont référentiel</th>
                            <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getHeuresComplReferentiel()) ?></td>
                        </tr>
                    <?php endif; ?>

                <?php endif; ?>
                <?php if ($fr->getSousService() > 0) : ?>

                    <tr>
                        <th>Sous-service</th>
                        <td style="text-align: right"><?= \UnicaenApp\Util::formattedNumber($fr->getSousService()) ?></td>
                    </tr>

                <?php endif; ?>
            </table>
        </div>
        <?php
        $result = ob_get_clean();

        return $result;
    }
}