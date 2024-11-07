<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$effectifs = 100;
$cmin      = [0, 4];
$cmax      = [0, 4];

//$cmin      = [2,2];
//$cmax      = [2,2];

$noeuds = [
    1,
    5,
    2,
    8,
];

/*$noeuds = [
    1,
    1,
    3
];*/

$totalPoids = array_sum($noeuds);
$nbChoix    = count($noeuds);
$maxPoids   = 0;
foreach ($noeuds as $poids) {
    if ($poids > $maxPoids) $maxPoids = $poids;
}


function calc($choixMin, $choixMax, $poids, $maxPoids, $totalPoids, $nbChoix)
{
    $coefChoix = ($choixMin + $choixMax) / 2 / $nbChoix;

    $coefPoids = $poids / $totalPoids;

    $maxCoefPoids = $maxPoids / $totalPoids;

    $resMax = $coefChoix * $maxCoefPoids * $nbChoix;
    if (($resMax) <= 1) {
        return 0;

        return $coefChoix * $coefPoids * $nbChoix;
    } else {
//        $res = $coefChoix * $nbChoix * ($coefPoids+(((1/$nbChoix)-$coefPoids)*0));
//        $resMax = $coefChoix * $nbChoix * $maxCoefPoids;

        return $resMax;
        $res = $coefChoix;

        //$res = 1 / (($coefChoix * $nbChoix * $coefPoids) - 1);
//return $res;
        $correcteur = $coefChoix * $nbChoix * ($coefPoids + (((1 / $nbChoix) - $coefPoids) * $correcteur));

        return $correcteur;
    }
}

//var_dump(calc(3,3,3,3,5,3));

echo "Effectifs = " . $effectifs . '<br />';
echo "Total Poids = " . $totalPoids . '<br />';
echo "Nombre de noeuds = " . $nbChoix . '<br />';
?>
<table class="table table-bordered table-sm">
    <thead>
    <tr>
        <th rowspan="2" style="width:5em">Choix min</th>
        <th rowspan="2" style="width:5em">Choix max</th>
        <?php foreach ($noeuds as $poids): ?>
            <th colspan="2">Noeud de poids = <?= $poids ?></th>
        <?php endforeach; ?>
        <th style="width:5em" rowspan="2">Tot Eff.</th>
    </tr>
    <tr>
        <?php foreach ($noeuds as $poids): ?>
            <th style="width:5em">Coef</th>
            <th style="width:5em">Effectifs</th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php for ($choixMin = $cmin[0]; $choixMin <= $cmin[1]; $choixMin++) {
        for ($choixMax = $cmax[0]; $choixMax <= $cmax[1]; $choixMax++) {
            $totEff = 0;

            ?>
            <tr>
                <th><?= $choixMin ?></th>
                <th><?= $choixMax ?></th>
                <?php foreach ($noeuds as $poids):

                    $coef = calc($choixMin, $choixMax, $poids, $maxPoids, $totalPoids, $nbChoix);
                    $eff = $coef * $effectifs;
                    $totEff += $eff;
                    ?>
                    <td><?= $coef ?></td>
                    <td><?= $eff ?></td>
                <?php endforeach; ?>
                <td><?= $totEff ?></td>
            </tr>

        <?php }
    } ?>
    </tbody>
</table>



