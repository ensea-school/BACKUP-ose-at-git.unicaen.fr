<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of CreerUtilisateurCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreerUtilisateurCommand extends Command
{

    use BddAwareTrait;

    private $params = ['nom', 'prenom', 'date-naissance', 'login', 'mot-de-passe', 'creer-intervenant'];



    protected function configure(): void
    {
        $this->setDescription('Création d\'un utilisateur')
            ->addOption(
                'nom',
                null,
                InputOption::VALUE_OPTIONAL,
                'Nom de l\'utilisateur'
            )
            ->addOption(
                'prenom',
                null,
                InputOption::VALUE_OPTIONAL,
                'Prénom de l\'utilisateur'
            )
            ->addOption(
                'date-naissance',
                null,
                InputOption::VALUE_OPTIONAL,
                'Date de naissance (format jj/mm/aaaa)'
            )
            ->addOption(
                'login',
                null,
                InputOption::VALUE_OPTIONAL,
                'Login de l\'utilisateur'
            )
            ->addOption(
                'mot-de-passe',
                null,
                InputOption::VALUE_OPTIONAL,
                'Mot de passe (6 caractères minimum)'
            )
            ->addOption(
                'creer-intervenant',
                null,
                InputOption::VALUE_OPTIONAL,
                'Mot de passe (6 caractères minimum)',
            )
            ->addOption(
                'code',
                null,
                InputOption::VALUE_OPTIONAL,
                'Code de l\'intervenant (optionnel)'
            )
            ->addOption(
                'statut',
                null,
                InputOption::VALUE_OPTIONAL,
                'Code statut de l\'intervenant'
            )
            ->addOption(
                'annee',
                null,
                InputOption::VALUE_OPTIONAL,
                'Année universitaire ( année en cours par défaut, sinon entrez 2020 pour 2020/2021, etc.)'
            );


    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $nom              = $input->getOption('nom');
        $prenom           = $input->getOption('prenom');
        $dateNaissance    = $input->getOption('date-naissance');
        $login            = $input->getOption('login');
        $motDePasse       = $input->getOption('mot-de-passe');
        $creerIntervenant = $input->getOption('creer-intervenant');


        $helper = $this->getHelper('question');

        if (!$nom) {
            $questionNom = new Question("Nom de l'utilisateur : ");
            $nom         = $helper->ask($input, $output, $questionNom);
        }
        if (!$prenom) {
            $questionPrenom = new Question("Prénom de l'utilisateur : ");
            $prenom         = $helper->ask($input, $output, $questionPrenom);
        }
        //IN PROGRESS
        //date de naissance
        $questionDateNaissance = new Question("Date de naissance (format jj/mm/aaaa) : ");
        $questionDateNaissance->setValidator(function (string $date): string {
            [$jour, $mois, $annee] = explode('/', $date);
            if (preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $date) && checkdate($mois, $jour, $annee)) {
                return $date;
            } else {
                throw new \RuntimeException('La date fournie n\'est pas au format attendu jj/mm/aaaa ou n\'est pas une date valide');
            }
        });
        $dateNaissance = $helper->ask($input, $output, $questionDateNaissance);
        //login
        $questionLogin = new Question('Login : ');
        $login         = $helper->ask($input, $output, $questionLogin);
        //mot de passe
        $questionMotDePasse = new Question('Mot de passe (6 caractères minimum) :');
        $questionMotDePasse->setValidator(function (string $motDePasse): string {
            if (strlen($motDePasse) >= 6) {
                return $motDePasse;
            }
            throw new \RuntimeException('Le mot de passe doit contenir minimum 6 caractères');
        });
        $questionMotDePasse->setHidden(true);
        $questionMotDePasse->setHiddenFallback(false);
        $motDePasse = $helper->ask($input, $output, $questionMotDePasse);
        //Création d'un intervenant
        $creerIntervenant         = false;
        $questionCreerIntervenant = new ConfirmationQuestion("Souhaitez vous créer l'intervenant (oui/non) ? :", false, '/^oui$/i');
        if ($helper->ask($input, $output, $questionCreerIntervenant)) {
            $creerIntervenant = true;
            //Code intervenant
            $questionCodeIntervenant = new Question('Code intervenant (optionnel) : ');
            $codeIntervenant         = $helper->ask($input, $output, $questionCodeIntervenant);
            //Année universitaire
            //On récupére l'année universitaire en cours
            $anneeEnCours = $this->bdd->select("SELECT ID,LIBELLE FROM ANNEE WHERE ID = (SELECT VALEUR FROM PARAMETRE WHERE NOM = 'annee')", [], ['fetch' => $this->bdd::FETCH_ONE]);

            $questionAnnee = new Question('Année universitaire (' . $anneeEnCours['LIBELLE'] . ' par défaut, sinon entrez 2020 pour 2020/2021, etc.): ');
            $questionAnnee->setValidator(function ($annee): string {
                if (preg_match('/^\d{4}$/', $annee) || empty($annee)) {
                    return $annee;
                }
                throw new \RuntimeException('L\'année doit être sur 4 chiffres (ex: 2023, 2024, etc...');
            });
            $annee = $helper->ask($input, $output, $questionAnnee);
            if (empty($annee)) {
                $annee = $anneeEnCours['ID'];
            }
            //Statut de l'intervenant
            $statuts             = $this->bdd->select("SELECT CODE CODE, LIBELLE FROM STATUT WHERE ANNEE_ID = " . $annee . " AND HISTO_DESTRUCTION IS NULL AND CODE <> 'AUTRES' ORDER BY ORDRE");
            $listChoicesStatut   = [];
            $listChoicesStatut[] = 'AUTRES (par défaut)';
            foreach ($statuts as $statut) {
                $listChoicesStatut[] = $statut['CODE'] . " (" . $statut['LIBELLE'] . ")";
            }
            $questionStatut = new ChoiceQuestion('Statut de \'intervenant ("AUTRES" par défaut, sinon entrez le code parmi les propositions suivantes) :', $listChoicesStatut, 0);
            $questionStatut->setErrorMessage("Le code renseigné n'est pas un code valide");
            $questionStatut->setMultiselect(false);
            $statut = $helper->ask($input, $output, $questionStatut);
            
        }


        $params['nom']                         = $nom;
        $params['prenom']                      = $prenom;
        $params['date-naissance']              = $dateNaissance;
        $params['login']                       = $login;
        $params['mot-de-passe']                = $motDePasse;
        $params['params']['creer-intervenant'] = ($creerIntervenant) ?? 'non';
        $params['params']['code']              = $codeIntervenant;
        $params['params']['statut']            = ($statut) ?? 'AUTRES';
        $params['params']['annee']             = ($annee) ?? '2024';

        //On vérifie qu'on a le minimum d'info (nom,prenom, date de naissance, login et mot de passe pour créer l'utilisateur sinon on arrête le traitement
        foreach ($params as $param) {
            if (!$param) {
                $io->error("Pour créer un utilisateur vous devez fournir au minimum les paramètres suivants : nom, prenom, date de naissance, login, mot de passe");
                return Command::FAILURE;
            }
        }


        return Command::SUCCESS;
    }


}