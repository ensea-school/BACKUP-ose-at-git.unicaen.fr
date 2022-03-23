<?php

namespace Application\View\Helper\Service;

use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Application\View\Helper\AbstractViewHelper;

/**
 * Aide de vue permettant d'afficher un résumé des services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Resume extends AbstractViewHelper
{
    use TypeIntervenantServiceAwareTrait;

    /**
     * Filtre de données
     *
     * @var array
     */
    protected $resumeServices;



    /**
     * Helper entry point.
     *
     * @return self
     */
    final public function __invoke($resumeServices)
    {
        $this->resumeServices = $resumeServices;

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



    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->resumeServices) return '';

        $typesIntervention = $this->resumeServices['types-intervention'];
        $totaux            = [
            'intervenant'       => 0,
            'heures'            => 0,
            'total'             => 0,
            'type-intervention' => [],
            'heures-ref'        => 0,
        ];

        $hasTi = 0 < count($typesIntervention);

        $urlTriIntervenant = $this->getView()->url('service/resume', [], ['query' => ['action' => 'trier', 'tri' => 'intervenant']]);
        //$urlTriReferentiel = $this->getView()->url( 'service/resume', [], ['query' => ['action' => 'trier', 'tri' => 'referentiel']]);
        $urlTriHetd = $this->getView()->url('service/resume', [], ['query' => ['action' => 'trier', 'tri' => 'hetd']]);
        $res        = '<table class="table table-hover table-bordered">' . "\n";
        $res        .= '<thead>' . "\n";
        $res        .= '<tr>' . "\n";
        $res        .= '    <th style="width:40%" rowspan="' . ($hasTi ? '2' : '1') . '"><a href="' . $urlTriIntervenant . '">Intervenant</a></th>' . "\n";
        if ($hasTi) {
            $res .= '    <th style="width:40%" colspan="' . count($typesIntervention) . '">Enseignements</th>' . "\n";
        }
        $res .= '    <th style="width:10%" rowspan="' . ($hasTi ? '2' : '1') . '">Référentiel</th>' . "\n";
        $res .= '    <th style="width:10%" rowspan="' . ($hasTi ? '2' : '1') . '">Service dû</th>' . "\n";
        $res .= '    <th style="width:10%" rowspan="' . ($hasTi ? '2' : '1') . '"><a href="' . $urlTriHetd . '">Solde HETD</a></th>' . "\n";
        $res .= '</tr>' . "\n";
        if ($hasTi) {
            $res .= '<tr>' . "\n";
            foreach ($typesIntervention as $ti) {
                $totaux['type-intervention-' . $ti->getCode()] = 0;
                $res                                           .= '        <th><abbr title="' . $ti->getLibelle() . '">' . $ti . '</abbr></th>' . "\n";
            }
            $res .= '</tr>' . "\n";
        }
        $res .= '</thead>' . "\n";
        $res .= '<tbody>' . "\n";
        foreach ($this->resumeServices['data'] as $line) {
            if (!isset($line['heures-ref'])) $line['heures-ref'] = 0;
            if (!isset($line['heures-service-statutaire'])) $line['heures-service-statutaire'] = 0;
            if (!isset($line['heures-service-du-modifie'])) $line['heures-service-du-modifie'] = 0;


            $na = '<abbr title="Non applicable (intervenant vacataire))">NA</abbr>';

            $intervenantPermanent = $line['intervenant-type-code'] === \Intervenant\Entity\Db\TypeIntervenant::CODE_PERMANENT;

            $res .= '<tr>' . "\n";
            $url = $this->getView()->url('intervenant/services-prevus', ['intervenant' => $line['intervenant-id']]);

            $res .= '<td><a href="' . $url . '">' . strtoupper($line['intervenant-nom']) . '</a></td>' . "\n";
            $totaux['intervenant']++;
            if ($hasTi) {
                foreach ($typesIntervention as $ti) {
                    $totaux['type-intervention-' . $ti->getCode()] += $line['type-intervention-' . $ti->getCode()];
                    $totaux['heures']                              += $line['type-intervention-' . $ti->getCode()];
                    $res                                           .= '<td style="text-align:right;white-space:nowrap">' . \UnicaenApp\Util::formattedNumber($line['type-intervention-' . $ti->getCode()]) . '</td>' . "\n";
                }
            }
            $totaux['heures-ref'] += $line['heures-ref'];
            $totaux['total']      += isset($line['total']) ? $line['total'] : 0;
            $res                  .= '<td style="text-align:right;white-space:nowrap">' . ($intervenantPermanent ? \UnicaenApp\Util::formattedNumber($line['heures-ref']) : $na) . '</td>' . "\n";
            $res                  .= $this->renderServiceDu($line['heures-service-statutaire'] + $line['heures-service-du-modifie']);
            $res                  .= $this->renderSoldeHetd($line['solde'], $intervenantPermanent);
            $res                  .= '</tr>' . "\n";
        }
        $res .= '</tbody>' . "\n";
        $res .= '<tfoot>' . "\n";
        $res .= '<tr>' . "\n";
        $res .= '<th rowspan="' . ($hasTi ? '2' : '1') . '" style="text-align:right">' . $totaux['intervenant'] . ' intervenants</th>' . "\n";
        if ($hasTi) {
            foreach ($typesIntervention as $ti) {
                $res .= '        <th style="text-align:right;white-space:nowrap"><abbr title="' . $ti->getLibelle() . '">' . \UnicaenApp\Util::formattedNumber($totaux['type-intervention-' . $ti->getCode()]) . '</abbr></th>' . "\n";
            }
        }
        $res .= '<th rowspan="' . ($hasTi ? '2' : '1') . '" style="text-align:right;white-space:nowrap">' . \UnicaenApp\Util::formattedNumber($totaux['heures-ref']) . '</th>' . "\n";
        $res .= '<th rowspan="' . ($hasTi ? '2' : '1') . '">&nbsp;</th>' . "\n";
        $res .= '<th rowspan="' . ($hasTi ? '2' : '1') . '"><span style="white-space:nowrap">Tot. <abbr title="Heures Complémentaires">HC</abbr></span> <span style="white-space:nowrap">' . \UnicaenApp\Util::formattedNumber($totaux['total']) . '</span></th>' . "\n";
        $res .= '</tr>' . "\n";
        $res .= '<tr>' . "\n";
        if ($hasTi) {
            $res .= '<th colspan="' . count($typesIntervention) . '" style="text-align:right;white-space:nowrap">Total des heures de service : ' . \UnicaenApp\Util::formattedNumber($totaux['heures']) . '</th>' . "\n";
        }
        $res .= '</tr>' . "\n";
        $res .= '</tfoot>' . "\n";
        $res .= '</table>' . "\n";

        return $res;
    }



    protected function renderServiceDu($serviceDu)
    {
        $class = '';
        if (is_numeric($serviceDu)) {
            if ($serviceDu < 0) $class = ' class="bg-danger"';
            $serviceDu = \UnicaenApp\Util::formattedNumber($serviceDu);
        }

        $res = '<td style="text-align:right;white-space:nowrap"' . $class . '>' . $serviceDu . '</td>' . "\n";

        return $res;
    }



    protected function renderSoldeHetd($solde, $intervanantPermanent = false)
    {
        $class = '';
        $plus  = '';
        if (is_numeric($solde)) {
            if ($intervanantPermanent) {
                if ($solde > 0) {
                    $class = ' class="bg-warning"';
                    $plus  = '+';
                }
                if ($solde < 0) $class = ' class="bg-danger"';
                $solde = $plus . \UnicaenApp\Util::formattedNumber($solde);
            } else {
                $solde = \UnicaenApp\Util::formattedNumber($solde);
            }
        }

        $res = '<td style="text-align:right;white-space:nowrap"' . $class . '>' . $solde . '</td>' . "\n";

        return $res;
    }
}