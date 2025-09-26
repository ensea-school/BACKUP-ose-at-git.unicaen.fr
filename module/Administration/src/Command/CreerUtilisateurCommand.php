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
use Utilisateur\Service\UtilisateurServiceAwareTrait;

/**
 * Description of CreerUtilisateurCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreerUtilisateurCommand extends Command
{

    use BddAwareTrait;
    use UtilisateurServiceAwareTrait;

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
                'Souhaitez vous créer l\'intervenant (oui/non)',
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
        $interactive = false;
        $io          = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $nom              = $input->getOption('nom');
        $prenom           = $input->getOption('prenom');
        $dateNaissance    = $input->getOption('date-naissance');
        $login            = $input->getOption('login');
        $motDePasse       = $input->getOption('mot-de-passe');
        $creerIntervenant = $input->getOption('creer-intervenant');
        $codeIntervenant  = $input->getOption('code');
        $annee            = $input->getOption('annee');
        $statut           = $input->getOption('statut');
        if ($dateNaissance) {
            $dateNaissance = \DateTime::createFromFormat('d/m/Y', $dateNaissance);
        }


        $helper = $this->getHelper('question');
        $params = [];
        if (!$nom) {
            $interactive = true;
            $questionNom = new Question("Nom de l'utilisateur : ");
            $nom         = $helper->ask($input, $output, $questionNom);
        }
        if (!$prenom) {
            $questionPrenom = new Question("Prénom de l'utilisateur : ");
            $prenom         = $helper->ask($input, $output, $questionPrenom);
        }
        if (!$dateNaissance) {
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
            $dateNaissance = \DateTime::createFromFormat('d/m/Y', $dateNaissance);

        }
        if (!$login) {
            $questionLogin = new Question('Login : ');
            $login         = $helper->ask($input, $output, $questionLogin);
        }
        if (!$motDePasse) {
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
        }

        if ($creerIntervenant) {
            $creerIntervenant = in_array(strtolower($creerIntervenant), ['Oui', 'OUI', 'oui', 'o', 'y', 'Y']) ? true : false;
        } else {
            $questionCreerIntervenant = new ConfirmationQuestion("Souhaitez vous créer l'intervenant (oui/non) ? :", false, '/^oui$/i');
            $creerIntervenant         = $helper->ask($input, $output, $questionCreerIntervenant);
        }


        if ($creerIntervenant) {
            //Gestion du code intervenant
            if (!$codeIntervenant) {
                //Prompt uniquement si je n'ai pas passé l'option creer-intervenant
                $questionCodeIntervenant = new Question('Code intervenant (optionnel) : ');
                $codeIntervenant         = $helper->ask($input, $output, $questionCodeIntervenant);
            }
            //Gestion de l'année de l'intervenant
            if (!$annee) {
                $anneeEnCours  = $this->bdd->select("SELECT ID,LIBELLE FROM ANNEE WHERE ID = (SELECT VALEUR FROM PARAMETRE WHERE NOM = 'annee')", [], ['fetch' => $this->bdd::FETCH_ONE]);
                $anneeEnCours  = $this->bdd->select("SELECT ID,LIBELLE FROM ANNEE WHERE ID = (SELECT VALEUR FROM PARAMETRE WHERE NOM = 'annee')", [], ['fetch' => $this->bdd::FETCH_ONE]);
                $questionAnnee = new Question('Année universitaire (' . $anneeEnCours['LIBELLE'] . ' par défaut, sinon entrez 2020 pour 2020/2021, etc.): ', $anneeEnCours['ID']);
                $questionAnnee->setValidator(function ($annee): string {
                    if (preg_match('/^\d{4}$/', $annee) || empty($annee)) {
                        return $annee;
                    }
                    throw new \RuntimeException('L\'année doit être sur 4 chiffres (ex: 2023, 2024, etc...');
                });
                $annee = $helper->ask($input, $output, $questionAnnee);
            }
            //Gestion du statut
            if (!$statut) {
                $statuts             = $this->bdd->select("SELECT CODE CODE, LIBELLE FROM STATUT WHERE ANNEE_ID = " . $annee . " AND HISTO_DESTRUCTION IS NULL AND CODE <> 'AUTRES' ORDER BY ORDRE");
                $listChoicesStatut   = [];
                $listChoicesStatut[] = 'AUTRES';
                foreach ($statuts as $statut) {
                    $listChoicesStatut[] = $statut['CODE'];
                }
                $questionStatut = new ChoiceQuestion('Statut de \'intervenant ("AUTRES" par défaut, sinon entrez le code parmi les propositions suivantes) :', $listChoicesStatut, 0);
                $questionStatut->setErrorMessage("Le code renseigné n'est pas un code valide");
                $questionStatut->setMultiselect(false);
                $statut = $helper->ask($input, $output, $questionStatut);
            }
            $params['creer-intervenant'] = $creerIntervenant;
            $params['code']              = $codeIntervenant;
            $params['statut']            = $statut;
            $params['annee']             = $annee;

        }

        //On vérifie qu'on a bien le minimum d'information pour créer un utilisateur et un intervenant
        if (!isset($nom, $prenom, $dateNaissance, $login, $motDePasse, $creerIntervenant)) {
            $io->error("Pour créer un utilisateur et/ou un intervenant il faut au minimum fournir un nom, prénom, date de naissance, login et mot de passe et création de l'intervenant (oui/non)");
            return Command::FAILURE;
        }

        try {
            $this->getServiceUtilisateur()->creerUtilisateur($nom, $prenom, $dateNaissance, $login, $motDePasse, $params);
            $displayUtilisateur = "$login sur l'année ";
            $displayIntervenant = "$prenom $nom sur l'année $annee avec le statut $statut";
            if ($creerIntervenant) {
                $io->success("Création de l'utilisateur $displayUtilisateur et de l'intervenant $displayIntervenant réalisée avec succés");
            } else {
                $io->success("Création de l'utilisateur $displayUtilisateur réalisée avec succés");
            }


        } catch (\Exception $e) {
            $io->error($e);
        }

        return Command::SUCCESS;
    }


}