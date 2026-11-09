<?php

namespace Administration\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;

class ReportSuiviAlternanceCommand extends Command
{
    use BddAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Report des éléments de la source Suivialternance et de leurs chemins pédagogiques de l’année précédente')
            ->addOption('annee', 'a', InputOption::VALUE_REQUIRED, 'ID de l’année cible');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io    = new SymfonyStyle($input, $output);
        $annee = (int)$input->getOption('annee');
        if ($annee <= 2013) {
            $io->error('L’option -a (ou --annee) est obligatoire et doit être un identifiant d’année entier supérieur à 2013.');
            return Command::INVALID;
        }

        $transaction = false;
        try {
            if (!$this->bdd->getTable('ANNEE')->select(['ID' => $annee])) {
                throw new RuntimeException("L’année cible $annee n’existe pas.");
            }

            $sources = $this->bdd->getTable('SOURCE')->select(['CODE' => 'Suivialternance']);
            if (count($sources) !== 1) {
                throw new RuntimeException('La source Suivialternance est introuvable ou ambiguë.');
            }
            $sourceId = reset($sources)['ID'];

            $elements = $this->bdd->getTable('ELEMENT_PEDAGOGIQUE');
            $chemins = $this->bdd->getTable('CHEMIN_PEDAGOGIQUE');
            $etapes   = [];
            $sourcesCodesEtapes = [];
            foreach ($this->bdd->getTable('ETAPE')->select(['ANNEE_ID' => $annee, 'HISTO_DESTRUCTION' => null]) as $etape) {
                $etapes[$etape['CODE']][] = $etape['ID'];
                $sourcesCodesEtapes[$etape['ID']] = $etape['SOURCE_CODE'];
            }
            $codesEtapes = [];
            foreach ($this->bdd->getTable('ETAPE')->select(['ANNEE_ID' => $annee - 1]) as $etape) {
                $codesEtapes[$etape['ID']] = $etape['CODE'];
            }
            $existants = [];
            foreach ($elements->select(['ANNEE_ID' => $annee, 'SOURCE_ID' => $sourceId, 'HISTO_DESTRUCTION' => null]) as $element) {
                $existants[$element['SOURCE_CODE']] = true;
            }

            $this->bdd->beginTransaction();
            $transaction = true;
            $nbTotal = 0;
            $nbElements  = 0;
            $nbChemins = 0;
            $nbExistants = 0;
            $nbElementsIgnores = 0;
            $nbCheminsIgnores = 0;
            foreach ($elements->select(['ANNEE_ID' => $annee - 1, 'SOURCE_ID' => $sourceId, 'HISTO_DESTRUCTION' => null]) as $element) {
                $nbTotal++;
                if (isset($existants[$element['SOURCE_CODE']])) {
                    $nbExistants++;
                    continue;
                }

                $ancienId            = $element['ID'];
                $element = $this->preparerCopie($element);
                $element['ANNEE_ID'] = $annee;
                try {
                    $element['ETAPE_ID'] = $this->trouverEtape($element['ETAPE_ID'], $codesEtapes, $etapes, $annee);
                } catch (RuntimeException $e) {
                    $io->warning("Élément $ancienId ignoré : " . $e->getMessage());
                    $nbElementsIgnores++;
                    continue;
                }
                // insert() attribue le nouvel ID de séquence directement dans le tableau.
                $elements->insert($element);
                $nbElements++;
                $existants[$element['SOURCE_CODE']] = true;

                foreach ($chemins->select(['ELEMENT_PEDAGOGIQUE_ID' => $ancienId, 'HISTO_DESTRUCTION' => null]) as $chemin) {
                    $ancienCheminId                   = $chemin['ID'];
                    $chemin                           = $this->preparerCopie($chemin);
                    $chemin['ELEMENT_PEDAGOGIQUE_ID'] = $element['ID'];
                    try {
                        $chemin['ETAPE_ID'] = $this->trouverEtape($chemin['ETAPE_ID'], $codesEtapes, $etapes, $annee);
                    } catch (RuntimeException $e) {
                        $io->warning("Chemin $ancienCheminId de l’élément $ancienId ignoré : " . $e->getMessage());
                        $nbCheminsIgnores++;
                        continue;
                    }
                    // Même construction que Pégase : code source étape, code source élément, année.
                    $chemin['SOURCE_CODE'] = $sourcesCodesEtapes[$chemin['ETAPE_ID']] . '_' . $element['SOURCE_CODE'] . '_' . $annee;
                    $chemins->insert($chemin);
                    $nbChemins++;
                }
            }
            $this->bdd->commitTransaction();
            $transaction = false;
            $anneeSource = $annee - 1;
            $io->success("Report Suivialternance $anneeSource vers $annee : $nbTotal élément(s) source, $nbElements élément(s) et $nbChemins chemin(s) créé(s), $nbExistants élément(s) déjà présent(s).");
            if ($nbElementsIgnores || $nbCheminsIgnores) {
                $io->warning("Sans correspondance d’étape : $nbElementsIgnores élément(s) et $nbCheminsIgnores chemin(s) ignoré(s).");
            }
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            if ($transaction) {
                $this->bdd->rollbackTransaction();
            }
            $io->error('Report annulé : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }



    private function trouverEtape($ancienId, array $codes, array $etapes, int $annee): int
    {
        if (!isset($codes[$ancienId])) {
            throw new RuntimeException("L’étape $ancienId n’appartient pas à l’année précédente.");
        }
        $code = $codes[$ancienId];
        $codeAvecTirets = str_replace('_', '-', $code);
        $candidats = array_unique([
            $code, 'VET-' . $code, 'vet-' . $code, 'Vet-' . $code,
            $codeAvecTirets, 'VET-' . $codeAvecTirets, 'vet-' . $codeAvecTirets, 'Vet-' . $codeAvecTirets,
        ]);
        foreach ($candidats as $candidat) {
            if (!isset($etapes[$candidat])) {
                continue;
            }
            return (int)$etapes[$candidat][0];
        }
        throw new RuntimeException("Aucune étape pour le code $code en $annee, avec ou sans préfixe VET-/vet-/Vet- et remplacement des _ par des -.");
    }



    private function preparerCopie(array $ligne): array
    {
        unset($ligne['ID']);
        $ligne['HISTO_CREATION']    = $ligne['HISTO_MODIFICATION'] = new \DateTime();
        $ligne['HISTO_DESTRUCTION'] = $ligne['HISTO_DESTRUCTEUR_ID'] = null;
        if (null !== $this->bdd->getHistoUserId()) {
            $ligne['HISTO_CREATEUR_ID'] = $ligne['HISTO_MODIFICATEUR_ID'] = $this->bdd->getHistoUserId();
        }
        return $ligne;
    }
}
