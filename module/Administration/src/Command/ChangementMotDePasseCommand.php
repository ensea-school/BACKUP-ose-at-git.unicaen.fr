<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Utilisateur\Service\UtilisateurServiceAwareTrait;

/**
 * Description of ChangementMotDePasseCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ChangementMotDePasseCommand extends Command
{
    use UtilisateurServiceAwareTrait;

    private SymfonyStyle $io;



    protected function configure(): void
    {
        $this
            ->setDescription('Changement de mot de passe pour l\'utilisateur oseappli')
            ->addOption('oseappli-pwd', null, InputOption::VALUE_OPTIONAL, 'Choix d\'un mot de passe pour l\'utilisateur système oseappli');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->io->title($this->getDescription());

        $this->motDePasse($input);

        return Command::SUCCESS;
    }



    private function motDePasse(InputInterface $input)
    {
        $pwd1 = $input->getOption('oseappli-pwd');

        if (!$pwd1) {
            $this->io->text("Choix d'un mot de passe pour l'utilisateur système oseappli");
            $pwd1 = $this->io->askHidden("Veuillez saisir un mot de passe (au minimum 6 caractères) :");

            $pwd2 = $this->io->askHidden("Veuillez saisir à nouveau le même mot de passe :");

            if ($pwd1 !== $pwd2) {
                $this->io->error('Les mots de passe saisis ne correspondent pas!');
                $this->motDePasse($input);
            }
        }

        $utilisateur = 'oseappli';
        $motDePasse  = $pwd1;

        $userObject = $this->getServiceUtilisateur()->getByUsername($utilisateur);

        if (!$userObject) {
            $this->io->error("Utilisateur $utilisateur non trouvé");
            die();
        }

        $this->getServiceUtilisateur()->changerMotDePasse($userObject, $motDePasse);
        $this->io->comment('Mot de passe enregistré');
        $this->io->info('Vous pourrez vous connecteur à OSE avec le login "oseappli" et votre nouveau mot de passe.');
    }
}