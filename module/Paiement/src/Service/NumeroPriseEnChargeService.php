<?php

namespace Paiement\Service;

use Application\Service\AbstractService;
use Intervenant\Entity\Db\Intervenant;
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
    public function treatImportFile(array $file, string $model = 'winpaie'): AxiosModel
    {
        $errors                = [];
        $nameFile              = $file['tmp_name'];
        $errors['intervenant'] = [];
        $errors['message']     = '';
        $errors['file']        = [];
        try {
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

            $errors['file']    = $datas['errors'];
            $errors['message'] = (isset($datas['message'])) ? $datas['message'] : [];
            //On met à jour les numéros de prise en charge des intervenants
            $em = $this->getEntityManager();
            $em->beginTransaction();
            foreach ($datas['result'] as $key => $value) {
                $repository = $em->getRepository(Intervenant::class);

                $intervenant = $repository->createQueryBuilder('i')
                    ->where('i.numeroInsee LIKE :val')
                    ->andWhere('i.annee = :annee')
                    ->setParameter('val', '%' . $value['insee'] . '%')
                    ->setParameter('annee', $this->getServiceContext()->getAnnee())
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();

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
        } catch (\Exception $e) {

            $errors['message'] = "Fichier invalide, impossible de le lire. Merci de mettre le fichier au format xlsx ou ods (" . $e->getMessage() . ")";
        }


        return new AxiosModel($errors);
    }



    public function winpaieTreatment(Sheet $sheet): array
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
        $maxCol = $sheet->getMaxCol();
        if ($maxCol <= 3) {
            $datas['result']  = $lines;
            $datas['errors']  = $errors;
            $datas['message'] = 'Le format de fichier fourni n\'est pas celui de winpaie';

            return $datas;
        }

        for ($rowNum = 2; $rowNum <= $maxRow; $rowNum++) {

            $cell     = $sheet->getCellByCoords($colonneNames['insee'], $rowNum);
            $colValue = $cell->getContent();
            //On supprime la quote et les espaces
            $colValue = str_replace(["'"], [''], $colValue);
            // Expression régulière pour capturer les caractères après le 16ème caractère
            /*
             * 1- si numero comporte une lettre alors c'est un insee etrangé
             * 2- si il fait moins de 15 caractères c'est un numéro provisoire
             * 3- sinon c'est un insee normale
             */
            $regexInseeEtranger = '/[a-zA-Z]/';
            $regexInsee         = '/^(.{15})(.*)$/';

            //On regarde d'abord si c'est un insee etranger (qui contient une lettre)
            if (preg_match($regexInseeEtranger, $colValue)) {
                if (preg_match('/^(.{13})(.*)$/', $colValue, $matches)) {
                    $insee   = trim($matches[1]);
                    $pec     = trim($matches[2]);
                    $lines[] = [
                        'insee' => $insee,
                        'pec'   => $pec,
                        'nom'   => $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent(),
                    ];
                }
                continue;
            } elseif (preg_match($regexInsee, $colValue, $matches)) {
                $insee   = $matches[1];
                $pec     = $matches[2];
                $lines[] = [
                    'insee' => trim($insee),
                    'pec'   => trim($pec),
                    'nom'   => $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent(),
                ];
            } else {
                //On stocke les erreurs dans datas pour afficher les lignes qui n'ont pas pu être traitées.
                if (!empty($colValue)) {
                    $inseeFile = ($sheet->getCellByCoords($colonneNames['insee'], $rowNum)) ? $sheet->getCellByCoords($colonneNames['insee'], $rowNum)->getContent() : '';
                    $nomFile   = ($sheet->getCellByCoords($colonneNames['nom'], $rowNum)) ? $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent() : '';
                    $errors[]  = "Ligne " . $rowNum . " : " . $nomFile . " - " . $inseeFile;
                }
            }
        }
        $datas['result']  = $lines;
        $datas['errors']  = $errors;
        $datas['message'] = '';


        return $datas;
    }



    public function genericTreatment(Sheet $sheet): array
    {
        $lines  = [];
        $errors = [];

        $colonneNames = [
            'insee'      => 1,
            'numero pec' => 2,
            'nom'        => 3,
        ];

        $maxRow = $sheet->getMaxRow();
        $maxCol = $sheet->getMaxCol();
        if ($maxCol != 3) {
            $datas['result']  = $lines;
            $datas['errors']  = $errors;
            $datas['message'] = 'Le format de fichier fourni n\'est pas le modèle numérique';

            return $datas;
        }

        for ($rowNum = 2; $rowNum <= $maxRow; $rowNum++) {


            $insee   = $sheet->getCellByCoords($colonneNames['insee'], $rowNum)->getContent();
            $insee   = (!empty($insee)) ? preg_replace('/[^A-Za-z0-9]/', '', $insee) : '';
            $pec     = intval($sheet->getCellByCoords($colonneNames['numero pec'], $rowNum)->getContent());
            $nom     = $sheet->getCellByCoords($colonneNames['nom'], $rowNum)->getContent();
            $lines[] = [
                'insee' => $insee,
                'pec'   => $pec,
                'nom'   => $nom,
            ];
        }

        $datas['result']  = $lines;
        $datas['errors']  = $errors;
        $datas['message'] = '';

        return $datas;
    }

}
