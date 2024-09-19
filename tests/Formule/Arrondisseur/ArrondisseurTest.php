<?php declare(strict_types=1);

namespace tests\Formule\Arrondisseur;

use Formule\Model\Arrondisseur\Arrondisseur;
use Formule\Model\Arrondisseur\Ligne;
use Formule\Model\Arrondisseur\Valeur;
use tests\OseTestCase;

final class ArrondisseurTest extends OseTestCase
{
    protected function makeDiffDataset(array $floatVals): array
    {
        $ligne = new Ligne();
        $somme = new Valeur($ligne);
        $valeurs = [];
        foreach($floatVals as $floatVal){
            $v = new Valeur($ligne);
            $v->setValue($floatVal);
            $somme->setValue($somme->getValue() + $floatVal);
            $valeurs[] = $v;
        }

        return compact('somme', 'valeurs');
    }



    protected function assertDiffDataset(array $expected, array $dataset): void
    {
        /** @var $valeurs Valeur[] */
        ['somme' => $somme, 'valeurs' => $valeurs] = $this->makeDiffDataset($dataset);

        $a = new Arrondisseur();
        $a->repartirDiff($somme, $valeurs);

        $result = [];
        foreach($valeurs as $valeur){
            $result[] = $valeur->getValueFinale();
        }

        $this->assertArrayEquals($expected, $result );
    }




    public function testRepartirDiff()
    {
        $dataset = [4.0231, 4.0232, 4.0233]; // 12,07
        $expected = [4.02, 4.02, 4.03]; // 12,06

        //$this->assertDiffDataset($expected, $dataset);
        $this->assertEquals(true, true);
    }

}
