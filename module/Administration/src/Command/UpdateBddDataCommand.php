<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;
use Unicaen\BddAdmin\Data\DataManager;

/**
 * Description of UpdateBddDataCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddDataCommand extends Command
{
    use BddAwareTrait;
    use AdministrationServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Contrôle et mise à jour du jeu de données');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $bdd = $this->getBdd()->setLogger($io);

        $io->title($this->getDescription());
        try {
            $bdd->data()->run(DataManager::ACTION_UPDATE);
            $this->updateReadOnlyStatuts();
            $this->getServiceAdministration()->clearCache();
            $io->success('Données à jour');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }



    public function updateReadOnlyStatuts(): void
    {
        $data = require 'data/statuts.php';
        $tableName = 'statut';
        $keyColumn = 'code';
        $typeIntervenantCol = 'type_intervenant_id';
        $idCol = 'id';

        mpg_upper($tableName);
        mpg_upper($keyColumn);
        mpg_upper($typeIntervenantCol);
        mpg_upper($data);
        mpg_upper($idCol);

        foreach ($data as $code => $s) {
            $s[$typeIntervenantCol] = (int)$this->getBdd()->selectOne('SELECT id FROM type_intervenant WHERE code = :code', [$keyColumn => $s[$typeIntervenantCol]], $idCol);
            $this->getBdd()->getTable($tableName)->update($s, [$keyColumn => $code]);
        }
    }
}