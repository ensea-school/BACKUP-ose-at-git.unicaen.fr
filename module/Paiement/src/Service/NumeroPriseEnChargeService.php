<?php

namespace Paiement\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractService;
use Unicaen\OpenDocument\Calc\Sheet;
use Unicaen\OpenDocument\Document;
use UnicaenVue\View\Model\AxiosModel;

/**
 * Description of NumeroPriseEnChargeService
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class NumeroPriseEnChargeService extends AbstractService
{

    /**
     * @param array $file
     *
     * @return AxiosModel Liste des erreurs rencontrées sous forme json
     */
    public function treatImportFile (array $file, string $model = 'winpaie'): AxiosModel
    {
        $errors   = [];
        $nameFile = $file['tmp_name'];

        $document = new Document();
        $document->loadFromFile($nameFile);
        $sheet = $document->getCalc()->getSheet(0);

        switch ($model) {
            case 'winpaie':
                $datas = $this->winpaieTreatment($sheet);
            break;
            default:
                $datas = $this->genericTreatment($sheet);
            break;
        }
        $errors['file'] = $datas['errors'];

        //On met à jour les numéros de prise en charge des intervenants
        $em = $this->getEntityManager();
        $em->beginTransaction();
        foreach ($datas['result'] as $key => $value) {
            $intervenant = $em->getRepository(Intervenant::class)->findOneBy(['numeroInsee' => $value['insee'], 'annee' => $this->getServiceContext()->getAnnee()]);
            if ($intervenant) {
                $intervenant->setNumeroPec($value['pec']);
                $intervenant->setSyncPec(0);
                $em->persist($intervenant);
            } else {
                $errors['intervenant'][] = $value['nom'] . " - " . $value['insee'];
            }
        }
        $em->flush();
        $em->commit();

        return new AxiosModel($errors);
    }



    public function winpaieTreatment (Sheet $sheet): array
    {
        $lines  = [];
        $errors = [];

        $colonneNames = [
            'insee'            => 1,
            'nom'              => 2,
            'nom de naissance' => 3,
            'code poste'       => 4,
            'libelle poste'    => 5,
            'code grade'       => 6,
            'libelle grade'    => 7,

        ];

        $maxRow = $sheet->getMaxRow();

        for ($rowNum = 2; $rowNum <= $maxRow; $rowNum++) {

            $cell     = $sheet->getCellByCoords($colonneNames['insee'], $rowNum);
            $colValue = $cell->getContent();
            // Expression régulière pour capturer les caractères après le 16ème caractère
            $regexInsee           = '/^(.{16})(.*)$/';
            $regexInseeProvisoire = '/^(.{15})(.*)$/';
            // Utilisation de preg_match pour appliquer l'expression régulière
            if (preg_match($regexInsee, $colValue, $matches)) {
                $insee = str_replace("'", "", $matches[1]);
                $pec   = $matches[2];
                //Si je n'ai pas réussi à récupérer le numéro PEC s'est que le numéro insee doit etre provisoire sur 15 carctères
                if ($pec == '') {
                    if (preg_match($regexInseeProvisoire, $colValue, $matches)) {
                        $insee = str_replace(["'", " "], ["", ""], $matches[1]);
                        $pec   = $matches[2];
                    } else {
                        $inseeFile = $sheet->getCellByCoords($colonneNames['insee'], $rowNum)->getContent();
                        $nomFile   = $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent();
                        $errors[]  = $nomFile . " - " . $inseeFile;
                    }
                }
                $lines[] = [
                    'insee' => $insee,
                    'pec'   => $pec,
                    'nom'   => $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent(),
                ];
            } else {
                //On stocke les erreurs dans datas pour afficher les lignes qui n'ont pas pu être traitées.
                $inseeFile = $sheet->getCellByCoords($colonneNames['insee'], $rowNum)->getContent();
                $nomFile   = $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent();
                $errors[]  = $nomFile . " - " . $inseeFile;
            }
        }

        $datas['result'] = $lines;
        $datas['errors'] = $errors;

        return $datas;
    }



    public function genericTreatment (Sheet $sheet): array
    {
        $lines  = [];
        $errors = [];

        $colonneNames = [
            'insee'      => 1,
            'numero pec' => 2,
            'nom'        => 3,
        ];

        $maxRow = $sheet->getMaxRow();

        for ($rowNum = 2; $rowNum <= $maxRow; $rowNum++) {


            $insee   = $sheet->getCellByCoords($colonneNames['insee'], $rowNum)->getContent();
            $pec     = $sheet->getCellByCoords($colonneNames['numero pec'], $rowNum)->getContent();
            $nom     = $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent();
            $lines[] = [
                'insee' => $insee,
                'pec'   => $pec,
                'nom'   => $nom,
            ];
        }

        $datas['result'] = $lines;
        $datas['errors'] = $errors;

        return $datas;
    }

}