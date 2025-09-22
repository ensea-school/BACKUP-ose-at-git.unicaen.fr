<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Application\Service\OseBddAdminFactory;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Framework\Application\Application;
use Plafond\Service\PlafondServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;


/**
 * Description of InstallBddCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class InstallBddCommand extends Command
{
    use BddAwareTrait;
    use PlafondServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use AdministrationServiceAwareTrait;

    private SymfonyStyle $io;



    protected function configure(): void
    {
        $this
            ->setDescription('Installation de la base de données')
            ->addOption('oseappli-pwd', 'p', InputOption::VALUE_OPTIONAL, 'Choix d\'un mot de passe pour l\'utilisateur système oseappli');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->getBdd()->setLogger($this->io);
        $this->io->title($this->getDescription());

        $this->install();
        $this->pj();
        $this->plafonds();
        $this->motDePasse($input);
        $this->getServiceAdministration()->clearCache();

        $this->io->success('L\'installation de la base de données est maintenant terminée!');

        return Command::SUCCESS;
    }



    private function install()
    {
        $bdd = $this->getBdd();

        $bdd->install(false);

        /* Insertion des données */

        // On s'occupe d'abord de créer puis d'initialiser l'utilisateur OSE et la source OSE
        $bdd->data()->run('install', 'UTILISATEUR');
        $bdd->data()->run('install', 'SOURCE');

        // On récupère les ID et on les passe en paramétrage
        $bdd->setHistoUserId(OseBddAdminFactory::getOseAppliId($bdd));
        $bdd->setSourceId(OseBddAdminFactory::getSourceOseId($bdd));

        // On installe ensuite toutes les données
        $bdd->data()->run('install');
    }



    private function pj()
    {
        //Provisoire en attendant de mettre à jour les données par défaut
        $sqlUpdatePjActive = "UPDATE statut SET PJ_ACTIVE  = 0 WHERE id NOT IN (
                                    SELECT s.id FROM type_piece_jointe_statut tpjs
                                    JOIN statut s ON s.id = tpjs.statut_id 
                                    AND tpjs.histo_destruction is NULL
                                    GROUP BY s.id)";
        $this->getBdd()->exec($sqlUpdatePjActive);
    }



    private function plafonds()
    {
        $this->io->comment('Mise en place des plafonds ...');
        $this->getServicePlafond()->construire();
    }



    private function motDePasse(InputInterface $input)
    {
        // On présuppose que le MDP oseappli a été transmis depuis les options de la ligne de commande
        $pwd1 = $input->getOption('oseappli-pwd');

        $saisi = false;
        if (!$pwd1) {
            $this->io->text("Choix d'un mot de passe pour l'utilisateur système oseappli");
            $pwd1 = $this->io->askHidden("Veuillez saisir un mot de passe (au minimum 6 caractères) :");

            $pwd2 = $this->io->askHidden("Veuillez saisir à nouveau le même mot de passe :");

            if ($pwd1 !== $pwd2) {
                $this->io->error('Les mots de passe saisis ne correspondent pas!');
                $this->motDePasse($input);
            }
            $saisi = true;
        }

        if ('no' == $pwd1) {
            $pwd1 = Application::getInstance()->config()['global']['oseappliPassword'] ?? null;
        }

        if (!$pwd1){
            $this->io->info(
                'Avant de vous connecter à OSE avec le login "oseappli", il vous faudra définir son mot de passe.'."\n"
                .'Vous devrez pour ceci éxécuter la commande suivante:'."\n".' ./bin/ose changement-mot-de-passe'
            );
        }else {
            $utilisateur = 'oseappli';
            $motDePasse  = $pwd1;

            $userObject = $this->getServiceUtilisateur()->getByUsername($utilisateur);

            if (!$userObject) {
                $this->io->error("Utilisateur $utilisateur non trouvé");
                die();
            }

            $this->getServiceUtilisateur()->changerMotDePasse($userObject, $motDePasse);
            if ($saisi) {
                $this->io->comment('Mot de passe enregistré');
                $this->io->info('Vous pourrez vous connecteur à OSE avec le login "oseappli" et votre nouveau mot de passe.');
            }
        }
    }
}