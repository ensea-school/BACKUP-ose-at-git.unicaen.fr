<?php declare(strict_types=1);

namespace tests\TblContrat;

use Contrat\Tbl\Process\Model\Contrat;
use tests\TblContrat\TblContratTestCase;
final class CalculNumeroAvenantTest extends TblContratTestCase
{
    /**
     * Test pour vérifier que pour un contrat global sans avenant existant,
     * le numéro d'aveant sera bien 1
     *
     * @return void
     */

    public function testNumeroAvenantContratGlobalSansAvenant(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Modification de contrat initial, avenant */
        $avenant            = new Contrat();
        $avenant->isMission = false;
        $avenant->edite     = false;
        $avenant->setParent($contrat);

        $this->process->calculNumeroAvenant($avenant);
        $this->assertEquals(1, $avenant->numeroAvenant, 'Numéro d\'avenant attendu n\'est pas égal à 1');

    }



    /**
     * Test d'un contrat ayant déjà des avenants, pour trouver le bon prochain numéro d'avenant
     *
     * @return void
     */

    public function testNumeroAvenantContratGlobalAvecAvenantExistant(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant1                = new Contrat();
        $avenant1->id            = 2;
        $avenant1->edite         = true;
        $avenant1->isMission     = false;
        $avenant1->numeroAvenant = 1;
        $avenant1->setParent($contrat);

        /* Avenant n°3 */
        $avenant2                = new Contrat();
        $avenant2->id            = 3;
        $avenant2->edite         = true;
        $avenant2->isMission     = false;
        $avenant2->numeroAvenant = 2;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);

        $this->process->calculNumeroAvenant($avenant3);
        $this->assertEquals(3, $avenant3->numeroAvenant, 'Dans le cadre d\'un troisième avenant le numéro d\'avenant attendu doit être égale à 3');

    }



    /* Contrat global initiale, avenant avec numéro 1 et 3 existant, numero 2 supprimer.
    Nouvelle avenant en place. Numéro attendu 4 */
    public function testNumeroAvenantContratGlobalAvecAvenantExistantEtSupprimé(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant1                = new Contrat();
        $avenant1->id            = 2;
        $avenant1->edite         = true;
        $avenant1->isMission     = false;
        $avenant1->numeroAvenant = 1;
        $avenant1->setParent($contrat);

        /* Avenant n°3 */
        $avenant2                = new Contrat();
        $avenant2->id            = 3;
        $avenant2->edite         = true;
        $avenant2->isMission     = false;
        $avenant2->numeroAvenant = 3;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);
        $contrat->avenants = [$avenant1, $avenant2, $avenant3];
        $this->process->calculNumeroAvenant($avenant1);
        $this->process->calculNumeroAvenant($avenant3);
        $this->process->calculNumeroAvenant($avenant2);
        $this->process->calculNumeroAvenant($contrat);
        $this->assertEquals(4, $avenant3->numeroAvenant);
        $this->assertEquals(1, $avenant1->numeroAvenant);
        $this->assertEquals(3, $avenant2->numeroAvenant);
        $this->assertEquals(0, $contrat->numeroAvenant);

    }



    public function testNumeroAvenantContratGlobalAvecDeuxAvenants(): void
    {

        /* Contrat initial */
        $contrat                = new Contrat();
        $contrat->id            = 1;
        $contrat->edite         = true;
        $contrat->isMission     = false;
        $contrat->numeroAvenant = 0;
        $contrat->avenants      = [];

        /* Avenant n°1 */
        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->setParent($contrat);

        /* Avenant n°3 */
        $avenant2            = new Contrat();
        $avenant2->isMission = false;
        $avenant2->setParent($contrat);

        /* Création d'un 3ème avenant */
        $avenant3            = new Contrat();
        $avenant3->isMission = false;
        $avenant3->edite     = false;
        $avenant3->setParent($contrat);
        $contrat->avenants = [$avenant2, $avenant3];
        $this->process->calculNumeroAvenant($avenant2);
        $this->process->calculNumeroAvenant($avenant3);
        $this->assertEquals(1, $avenant3->numeroAvenant);
        $this->assertEquals(1, $avenant2->numeroAvenant);
        $this->assertEquals(0, $contrat->numeroAvenant);

    }
}
