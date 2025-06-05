<?php

namespace Contrat\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Role;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\RoleServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\TblContrat;
use Doctrine\ORM\QueryBuilder;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use EtatSortie\Entity\Db\EtatSortie;
use EtatSortie\Service\EtatSortieServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use RuntimeException;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use UnicaenSignature\Entity\Db\Process;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Service\ProcessServiceAwareTrait;
use UnicaenSignature\Service\SignatureServiceAwareTrait;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;


/**
 * Description of Contrat
 *
 */
class ContratService extends AbstractEntityService
{
    use ValidationServiceAwareTrait;
    use TypeValidationServiceAwareTrait;
    use TypeContratServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use FichierServiceAwareTrait;
    use EtatSortieServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use SignatureServiceAwareTrait;
    use RoleServiceAwareTrait;
    use AffectationServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use ProcessServiceAwareTrait;
    use RoleServiceAwareTrait;
    use TblContratServiceAwareTrait;

    protected array $unicaenSignatureConfig = [];



    public function __construct(array $unicaenSignatureConfig)
    {
        $this->unicaenSignatureConfig = $unicaenSignatureConfig;
    }



    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Contrat::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'c';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.intervenant, $alias.typeContrat, $alias.numeroAvenant");

        return $qb;
    }



    /**
     * Retourne la liste des services dont les volumes horaires sont validés ou non.
     *
     * @param boolean|\Workflow\Entity\Db\Validation $validation    <code>true</code>, <code>false</code> ou
     *                                                              bien une Validation précise
     * @param QueryBuilder|null                      $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByValidation($validation, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if ($validation instanceof \Workflow\Entity\Db\Validation) {
            $qb
                ->join("$alias.validation", "v")
                ->andWhere("v = :validation")->setParameter('validation', $validation);
        } else {
            $value = $validation ? 'IS NOT NULL' : 'IS NULL';
            $qb->andWhere("$alias.validation $value");
        }

        return $qb;
    }



    /**
     * Calcule le numero d'avenant suivant : nombre d'avenants validés.
     *
     * @param Intervenant $intervenant Intervenant concerné
     *
     * @return int
     */
    public function getNextNumeroAvenant(Intervenant $intervenant)
    {
        $sql = "
        SELECT 
          max(numero_avenant) + 1 numero_avenant
        FROM 
          contrat c
          JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
        WHERE 
          c.histo_destruction IS NULL AND c.intervenant_id = :intervenant
        ";

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['intervenant' => $intervenant->getId()]);
        if (isset($res[0]['NUMERO_AVENANT'])) {
            $numeroAvenant = (int)$res[0]['NUMERO_AVENANT'];
        } else {
            $numeroAvenant = 1;
        }

        return $numeroAvenant;
    }



    public function creerProcessContratSignatureElectronique(Contrat $contrat): void
    {
        //On récupere le contenu du contrat
        try {
            $document = $this->generer($contrat, false, false);
            //Création du nom du contrat
            $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
                ($contrat->getStructure() == null ? null : $contrat->getStructure()->getCode()),
                                $contrat->getIntervenant()->getNomUsuel(),
                                $contrat->getIntervenant()->getCode());
            //Récupération de la configuration de unicaen signature
            $content = $document->saveToData();
            $contratFilePath = $this->unicaenSignatureConfig['documents_path'] . '/' . $fileName;
            $filesystem = new Filesystem();
            $filesystem->appendToFile($contratFilePath, $content);
            $filesystem->chmod($contratFilePath, 0777);
            $filename        = basename($contratFilePath);
            //Récupération du circuit de signature si la signature est activé pour l'état de sortie de ce contrat
            $intervenant       = $contrat->getIntervenant();
            $etatSortieContrat = $intervenant->getStatut()->getContratEtatSortie();
            if ($etatSortieContrat instanceof EtatSortie) {
                //Vérification si la signature électronique est activée pour cet état de sortie.
                if ($etatSortieContrat->isSignatureActivation()) {
                    $signatureFlow = $etatSortieContrat->getSignatureCircuit();
                    if (!empty($signatureFlow)) {
                        $signatureFlowDatas = $this->getSignatureService()->createSignatureFlowDatasById(
                            "",
                            $signatureFlow->getId(),
                            []
                        )['signatureflow'];
                        //On enrichit le label
                        $signatureFlowDatas['label'] .= " - " . $intervenant->__toString();


                        $recipients = [];
                        //On regarde si on a le paramétrage hook_recepient dans la config
                        //pour forcer l'envoie toujours à la même personne
                        $recipientsHook = [];
                        if (array_key_exists('hook_recipients', $this->unicaenSignatureConfig)) {
                            if (!empty($this->unicaenSignatureConfig['hook_recipients'])) {
                                foreach ($this->unicaenSignatureConfig['hook_recipients'] as $recipient) {
                                    $recipientsHook[] = [
                                        'firstname' => $recipient['firstname'],
                                        'lastname'  => $recipient['lastname'],
                                        'email'     => $recipient['email'],
                                    ];
                                }

                            }
                        }
                        //On doit aller chercher les destinataires pour la signature électronique

                        foreach ($signatureFlowDatas['steps'] as $key => $step) {
                            //Si l'étape de process concerne un rôle de l'application on va chercher les utilisateurs de ce role.
                            if (in_array($step['recipient_method'], ['by_etablissement', 'by_etablissement_and_intervenant']) && empty($step['recipients'])) {
                                $role = '';
                                if (array_key_exists('by_etablissement', $step['options'])) {
                                    $role = $this->getServiceRole()->get($step['options']['by_etablissement']);
                                }
                                if (array_key_exists('by_etablissement_and_intervenant', $step['options'])) {
                                    $role = $this->getServiceRole()->get($step['options']['by_etablissement_and_intervenant']);
                                }
                                //On a trouvé le rôle pour la signature établissement
                                if ($role instanceof Role) {
                                    //Si c'est un rôle etablissement, on prend tous les utilisateurs affectés à ce rôle
                                    if ($role->getPerimetre()->getCode() == 'etablissement') {
                                        $utilisateurs = $this->getServiceUtilisateur()->getUtilisateursByRole($role);
                                    } else {
                                        //Sinon on va filtrer par composante
                                        $structure = $contrat->getStructure();
                                        if ($structure instanceof Structure) {
                                            //On prend tous les utilisateurs du role attendu par le circuit de signature y
                                            // compris les utilisateurs ayant le même rôle dans les structures hiérarchiques supérieures
                                            $utilisateurs = $this->getServiceUtilisateur()->getUtilisateursByRoleAndStructure($role, $structure);
                                            //Si on a trouvé aucun utilisateur pour ce rôle on arrete la création du processus de signature
                                            if (empty($utilisateurs)) {
                                                throw new \Exception("Aucun utilisateur trouvé pour le rôle <strong>" . $role->getLibelle() . "</strong> habilité pour la structure <strong>" . $structure->getLibelleCourt() . "</strong> nécessaire pour ce circuit de signature électronique.");
                                            }
                                        } else {
                                            throw new \Exception("Le contrat n'est rattaché à aucune composante, la signature ne peut se faire que sur un rôle avec un périmètre établissement");
                                        }
                                    }
                                    foreach ($utilisateurs as $utilisateur) {
                                        $recipients[] = [
                                            'firstname' => $utilisateur['DISPLAY_NAME'],
                                            'lastname'  => '',
                                            'email'     => $utilisateur['EMAIL'],
                                        ];
                                    }
                                } else {
                                    throw new \Exception("Le rôle paramètré pour ce circuit de signature n'existe pas.");
                                }
                                //Si on est en mode test avec des destinataires par défaut de renseignés pour contourner l'envoi aux vrais destinataires
                                if (!empty($recipientsHook)) {
                                    $recipients = $recipientsHook;
                                }


                                $signatureFlowDatas['steps'][$key]['recipients'] = $recipients;

                            }
                            //Si l'étape de process concerne l'intervenant du contrat on va chercher l'email de l'intervenant.
                            if (in_array($step['recipient_method'], ['by_intervenant', 'by_etablissement_and_intervenant']) && empty($step['recipients'])) {
                                $intervenant = $contrat->getIntervenant();
                                $nom         = $intervenant->getNomUsuel();
                                $prenom      = $intervenant->getPrenom();
                                //Si aucun email pour l'intervenant on annule la signature électronique
                                if (empty($intervenant->getEmailPerso()) && empty($intervenant->getEmailPro())) {
                                    throw new \Exception("L'intervenant ne posséde aucun email permettant d'initialiser la signature électronique.");
                                }
                                //On envoie en priorité sur l'email pro, notamment pour les étudiants qui on un compte numérique avec une adresse etu.unicaen.fr
                                $email        = (!empty($intervenant->getEmailPro())) ? $intervenant->getEmailPro() : $intervenant->getEmailPerso();
                                $recipients[] =
                                    [
                                        'firstname' => $prenom,
                                        'lastname'  => $nom,
                                        'email'     => $email,
                                    ];

                                //Si on est en mode test avec des destinataires par défaut de renseignés dans la conf
                                if (!empty($recipientsHook)) {
                                    $recipients = $recipientsHook;
                                }
                                $signatureFlowDatas['steps'][$key]['recipients'] = $recipients;
                            }
                        }
                    } else {
                        throw new \Exception("Aucun circuit de signature paramétré pour cet état de sortie");
                    }


                    //Création du processus de signature
                    $process = $this->getProcessService()->createUnconfiguredProcess($filename, $signatureFlow->getId());
                    //Création des différentes étapes de signature du circuit
                    $this->getProcessService()->configureProcess($process, $signatureFlowDatas);
                    $contrat->setProcessSignature($process);
                    //Déclenchement de la première étape de signature du circuit
                    $this->getProcessService()->trigger($process, true);
                    //Sauvegarde du contrat initial dans fichier
                    $dataFile['tmp_name'] = $contratFilePath;
                    $dataFile['type']     = 'application/pdf';
                    $dataFile['size']     = filesize($contratFilePath);
                    $dataFile['name']     = $filename;
                    $files[]              = $dataFile;
                    $this->creerFichiers($files, $contrat, true);
                } else {
                    throw new \Exception("La signature électronique n'est pas activée pour cet état de sortie");
                }

            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    public function generer(Contrat $contrat, $download = true, $save = false)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            ($contrat->getStructure() == null ? null : $contrat->getStructure()->getCode()),
                            $contrat->getIntervenant()->getNomUsuel(),
                            $contrat->getIntervenant()->getCode());

        if ($contrat->estUnAvenant()) {
            $modele = $contrat->getIntervenant()->getStatut()->getAvenantEtatSortie();
            if (!$modele) {
                $modele = $contrat->getIntervenant()->getStatut()->getContratEtatSortie();
            }
        } else {
            $modele = $contrat->getIntervenant()->getStatut()->getContratEtatSortie();
        }

        if (!$modele) {
            throw new \Exception('Aucun modèle ne correspond à ce contrat');
        }

        $filtres = ['CONTRAT_ID' => $contrat->getId()];

        $document = $this->getServiceEtatSortie()->genererPdf($modele, $filtres);

        if ($contrat->estUnProjet()) {
            $document->getStylist()->addFiligrane('PROJET');
        }


        if ($save) {
            //On récupere le contenu du contrat pour le stocker temporairement afin de pouvoir l'envoyer dans le parapheur
            $content = $document->saveToData();
            $file = $this->unicaenSignatureConfig['documents_path'] . '/' . $fileName;
            file_put_contents($this->unicaenSignatureConfig['documents_path'] . '/' . $fileName, $content);
            chmod($this->unicaenSignatureConfig['documents_path'] . '/' . $fileName, 0777);

            return $this->unicaenSignatureConfig['documents_path'] . '/' . $fileName;
        }
        if ($download) {
            $document->download($fileName);

            return null;
        } else {
            return $document;
        }
    }



    /**
     * Création des Fichiers déposés pour un contrat.
     *
     * @param array   $files             Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png',
     *                                   'size' => 321215]
     * @param Contrat $contrat
     * @param boolean $deleteFiles       Supprimer les fichiers temporaires après création du Fichier
     *
     * @return Fichier[]
     */
    public function creerFichiers($files, Contrat $contrat, $deleteFiles = true)
    {
        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }
        $instances = [];

        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = str_replace([',', ';', ':'], '', $file['name']);
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new Fichier())
                ->setTypeMime($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);

            $contrat->addFichier($fichier);

            $this->getServiceFichier()->save($fichier);
            $instances[] = $fichier;

            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $instances;
    }



    public function supprimerSignatureElectronique(Contrat $contrat): Contrat
    {
        $processSignature = $contrat->getProcessSignature();
        //Si j'ai bien un processus de signature alors je peux procéder à sa suppression
        if ($processSignature instanceof Process) {
            try {
                $this->getEntityManager()->refresh($contrat);
                //On remet la date de retour signée à null
                $contrat->setDateRetourSigne(null);
                //On supprime la relation du contrat au process signature
                $contrat->setProcessSignature(null);
                //On save le contrat
                $this->save($contrat);
                //On supprime l'ensemble du process et des process step
                $this->getProcessService()->deleteProcess($processSignature);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        //On supprime les fichiers liés au contrat
        $this->supprimerFichiers($contrat);

        return $contrat;
    }



    /**
     * Supprime les fichiers liés au contrat
     * @param Contrat $contrat
     * @return Contrat
     */

    public function supprimerFichiers(Contrat $contrat): Contrat
    {
        if ($contrat instanceof Contrat) {
            //On récupére les fichiers déposés pour ce contrat
            $fichiers = $contrat->getFichier();
            if (!empty($fichiers)) {
                foreach ($fichiers as $fichier) {
                    //Suprression relation contrat fichier
                    $contrat->removeFichier($fichier);
                    //On supprimer la date de retour signée du contrat
                    $contrat->setDateRetourSigne(null);
                    //On save le contrat
                    $this->getEntityManager()->refresh($contrat);
                    $this->save($contrat);
                    //Suppression de l'entity fichier
                    $this->getServiceFichier()->delete($fichier, false);
                }
            }
            return $contrat;
        } else {
            throw new \Exception("Le contrat spécifié n'est pas valide");
        }
    }



    /**
     * Supprime (historise par défaut).
     *
     * @param Contrat $entity Entité à détruire
     * @param bool    $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$softDelete) {
            $id = (int)$entity->getId();

            $sql = "UPDATE volume_horaire SET contrat_id = NULL WHERE contrat_id = $id";
            $this->getEntityManager()->getConnection()->executeQuery($sql);

            foreach ($entity->getFichier() as $fichier) {
                $this->getServiceFichier()->delete($fichier, $softDelete);
            }
        }

        return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
    }



    /**
     * Met à jour la signature electronique d'un contrat
     *
     * @param Contrat $contrat
     *
     * @return Contrat
     * @throws \UnicaenSignature\Exception\SignatureException
     */

    public function rafraichirProcessSignatureElectronique(Contrat $contrat): bool
    {
        if ($contrat instanceof Contrat) {
            $process = $contrat->getProcessSignature();
            $path    = $this->unicaenSignatureConfig['documents_path'];
            try {
                $fichierContrat = $this->recupererFichierContrat($contrat, $path);
                if ($process instanceof Process) {
                    //On lance la prochaine étape du processus

                    $this->processService->trigger($process);
                    //On supprimer le fichier contrat actuellement en base pour le mettre à jour


                    //On récupérer le nouveau contrat pour le sauvegarder comme fichier actuel
                    /**
                     * @var Fichier $fichierContrat
                     */
                    $fichierContratPath = $path . '/' . $fichierContrat->getNom();
                    $file['tmp_name']   = $fichierContratPath;
                    $file['type']       = 'application/pdf';
                    $file['size']       = filesize($fichierContratPath);
                    $file['name']       = $process->getDocumentName();
                    $files[]            = $file;
                    $this->supprimerFichiers($contrat);
                    $this->creerFichiers($files, $contrat, true);
                    //On regarde si le processus de signature est terminée
                    if ($process->isFinished()) {
                        //Si le process est terminée alors on enregistre la date de retour signée
                        $dateDeRetourSigne = $process->getLastUpdate();
                        $contrat->setDateRetourSigne($dateDeRetourSigne);
                        $this->save($contrat);
                        return true;
                    }

                }
            } catch (\Exception $e) {
                throw $e;
            }


        }
        return false;
    }



    /**
     * Met à jour la signature electronique d'un contrat
     *
     * @param Contrat $contrat
     * @param string  $path
     *
     * @return ?Fichier
     * @throws \Exception
     */


    public function recupererFichierContrat(Contrat $contrat, $path = null): ?Fichier
    {
        $fichiers = $contrat->getFichier();
        //Si on a un path on dépose le fichier à l'endroit indiqué
        if (!empty($fichiers)) {
            $fichierContrat = $fichiers[0];
            if ($path) {
                /**
                 * @var Fichier $fichierContrat
                 */
                /*On redépose le fichier du  contrat dans l'état actuel dans le répertoire temporaire de la conf singature
                 pour qu'il puisse être physiquement envoyé dans Esup*/
                $fichierContratContent = $fichierContrat->getContenu();
                $fichierContratNom     = $fichierContrat->getNom();
                $filename = $path . '/' . $fichierContratNom;
                //file_put_contents($path . '/' . $fichierContratNom, $fichierContratContent);
                $filesystem = new Filesystem();
                $filesystem->appendToFile($filename, $fichierContratContent);
                $filesystem->chmod($path . '/' . $fichierContratNom, 0777);

            }
            return $fichierContrat;
        } else {
            throw new \Exception("Aucun fichier n'est lié à ce contrat");
        }


    }



    public function getUrlSignedContrat(Contrat $contrat): string
    {
        $fichiers = $contrat->getFichier();
        if (!empty($fichiers)) {
            foreach ($fichiers as $fichier) {
                /**
                 * @var Fichier $fichier
                 */
                return $this->getServiceFichier()->getFichierFilename($fichier);
            }
        }
        return '';
    }



    public function creerDeclaration($files, Contrat $contrat, $deleteFiles = true)
    {

        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }
        $instances = [];

        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = str_replace([',', ';', ':'], '', $file['name']);
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new Fichier())
                ->setTypeMime($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);

            $contrat->setDeclaration($fichier);

            $this->getServiceFichier()->save($fichier);
            $instances[] = $fichier;

            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $instances;
    }



    public function hasAvenant(Contrat $contrat)
    {
        $dql = '
        SELECT
          c
        FROM
          Contrat\Entity\Db\Contrat c
        WHERE
          c.histoDestruction IS NULL
          AND c.contrat = :contrat';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('contrat', $contrat->getId());

        $res = $query->getResult();
        if ($res) {
            return true;
        }

        return false;
    }



    public function getFirstContratMission(Intervenant $intervenant): ?Mission
    {
        $dql = "
            SELECT tblc, c,m
            FROM ".TblContrat::class." tblc
            JOIN tblc.intervenant i
            JOIN tblc.contrat c
            JOIN tblc.mission m
            WHERE tblc.intervenant = :intervenant
            AND tblc.mission IS NOT NULL
            AND c.histoDestruction IS NULL
            ORDER BY m.dateDebut ASC
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('intervenant', $intervenant);

        $contrat = $query->setMaxResults(1)->getOneOrNullResult();

        if ($contrat) {
            return $contrat->getMission();
        }

        return null;
    }


    public function getContratWithProcessWaiting()
    {

        $dql = "
            SELECT c
            FROM " . Contrat::class . " c
            JOIN c.processSignature p
            WHERE p.status = :statut";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('statut', Signature::STATUS_SIGNATURE_WAIT);
        $result = $query->getResult();

        return $result;


    }
}