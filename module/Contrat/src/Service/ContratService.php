<?php

namespace Contrat\Service;

use Application\Entity\Db\EtatSortie;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Role;
use Application\Entity\Db\Utilisateur;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\RoleServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Doctrine\ORM\QueryBuilder;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use phpDocumentor\Reflection\Types\Collection;
use RuntimeException;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Unicaen\OpenDocument\Document;
use UnicaenSignature\Entity\Db\Process;
use UnicaenSignature\Entity\Db\ProcessStep;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
use UnicaenSignature\Service\ProcessServiceAwareTrait;
use UnicaenSignature\Service\SignatureServiceAwareTrait;
use UnicaenVue\Util;
use UnicaenVue\View\Model\AxiosModel;


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
     * @param boolean|\Application\Entity\Db\Validation $validation <code>true</code>, <code>false</code> ou
     *                                                              bien une Validation précise
     * @param QueryBuilder|null                         $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByValidation($validation, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        if ($validation instanceof \Application\Entity\Db\Validation) {
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



    public function creerProcessContratSignatureElectronique(Contrat $contrat)
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
            $config  = \OseAdmin::instance()->config()->get('unicaen-signature');
            $content = $document->saveToData();
            file_put_contents($config['documents_path'] . '/' . $fileName, $content);
            $contratFilePath = $config['documents_path'] . '/' . $fileName;
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

                        //On doit aller chercher les recipients
                        foreach ($signatureFlowDatas['steps'] as $key => $step) {
                            //Si l'étape de process concerne un rôle de l'application on va chercher les utilisateurs de ce role.
                            if ($step['recipient_method'] == 'by_role' && empty($step['recipients'])) {
                                $role = '';
                                if (array_key_exists('by_role', $step['options'])) {
                                    $role = $this->getServiceRole()->get($step['options']['by_role']);
                                }
                                //On a trouvé le rôle pour la signature établissement
                                if ($role instanceof Role) {
                                    //Si c'est un rôle etablissement, on prend tous les utilisateurs affectés à ce rôle
                                    if ($role->getPerimetre()->getCode() == 'etabalissement') {
                                        $utilisateurs = $this->getServiceUtilisateur()->getUtilisateursByRole($role);
                                    } else {
                                        $structure = $contrat->getStructure();
                                        if ($structure instanceof Structure) {
                                            //On prend tous les utilisateurs du role attendu par le circuit de signature y
                                            // compris les utilisateurs ayant le même rôle dans les structures hiérarchique
                                            $utilisateurs = $this->getServiceUtilisateur()->getUtilisateursByRoleAndStructure($role, $structure);
                                        } else {
                                            //Cas d'un contrat n'ayant pas de structure, on prend tous les rôles peut importe la structure
                                            $utilisateurs = $this->getServiceUtilisateur()->getUtilisateursByRole($role);
                                        }

                                        /*C'est un rôle avec un périmètre composante donc on va chercher
                                        uniquement les utilisateurs de la dites composantes et éventuellement
                                        de ses sous structures*/


                                    }

                                    $recipients = [];
                                    /**
                                     * @var Utilisateur $utilisateur
                                     */
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
                                //HOOK pour ne forcer l'envoie dans esup avec mon email
                                $recipients = [];
                                //On regarde si on a le paramétrage hook_recepient dans la config pour forcer l'envoie
                                //des signatures toujours à la même personne
                                $recipients[] = [
                                    'firstname' => 'Antony',
                                    'lastname'  => 'Le Courtes',
                                    'email'     => 'antony.lecourtes@unicaen.fr',
                                ];

                                $signatureFlowDatas['steps'][$key]['recipients'] = $recipients;

                            }
                            //Si l'étape de process concerne l'intervenant du contrat on va chercher l'email de l'intervenant.
                            if ($step['recipient_method'] == 'by_intervenant' && empty($step['recipients'])) {
                                $intervenant = $contrat->getIntervenant();
                                $nom         = $intervenant->getNomUsuel();
                                $prenom      = $intervenant->getPrenom();
                                $mail        = $intervenant->getEmailPerso();

                                $recipients[] =
                                    [
                                        'firstname' => $prenom,
                                        'lastname'  => $nom,
                                        'email'     => $mail,
                                    ];
                                //HOOK pour ne forcer l'envoie dans esup avec mon email
                                $recipients                                      = [];
                                $recipients[]                                    = [
                                    'firstname' => 'Antony',
                                    'lastname'  => 'Le Courtes',
                                    'email'     => 'antony.lecourtes@unicaen.fr',
                                ];
                                $signatureFlowDatas['steps'][$key]['recipients'] = $recipients;

                            }
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
                        throw new \Exception("Aucun circuit de signature paramètré pour cet état de sortie");
                    }

                } else {
                    throw new \Exception("La signature électronique n'est pas activée pour cet état de sortie");
                }


            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return true;

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
            $config = \OseAdmin::instance()->config()->get('unicaen-signature');
            //On récupere le contenu du contrat pour le stocker temporairement afin de pouvoir l'envoyer dans le parapheur
            $content = $document->saveToData();
            file_put_contents($config['documents_path'] . '/' . $fileName, $content);
            /*            $var = exec('chmod 777 ' . $config['documents_path'] . '/copy.pdf');
                        dump($var);
                        die;
            */
            return $config['documents_path'] . '/' . $fileName;
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
        $config = \OseAdmin::instance()->config()->get('unicaen-signature');
        if ($contrat instanceof Contrat) {
            $process = $contrat->getProcessSignature();
            $path    = $config['documents_path'];
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
                file_put_contents($path . '/' . $fichierContratNom, $fichierContratContent);
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
        $dql = " SELECT c,m
            FROM " . Contrat::class . " c
            JOIN c.intervenant i
            JOIN c.mission m
            WHERE c.intervenant = :intervenant
                --AND c.dateRetourSigne IS NOT NULL
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



    public function getContratInitialMission(?Mission $mission)
    {
        $dql = '
        SELECT
          c
        FROM
        Contrat\Entity\Db\Contrat c
        JOIN c.mission m
        JOIN m.volumesHoraires      vhm
        WHERE
          m.histoDestruction IS NULL
          AND vhm.histoDestruction IS NULL
          AND  m.id = :mission
          AND c.contrat IS NULL';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('mission', $mission->getId());

        $res = $query->getResult();
        if ($res) {
            return $res[0];
        }

        return null;
    }



    public function getDataSignatureContrat(array $post): AxiosModel
    {
        $anneeContexte = $this->getServiceContext()->getAnnee()->getId();

        $sql = "
              SELECT 
                    uss.id            id_signature,
                    c.id              id_contrat,
                    i.id              id_intervenant,
                    i.nom_usuel       nom,
                    i.prenom          prenom,
                    s.libelle_long    libelle_structure,
                    uss.datecreated   date_creation_signature_electronique,
                    uss.status        statut_signature_electronique
                    FROM contrat c
                    JOIN unicaen_signature_signature uss ON c.signature_id = uss.id 
                    JOIN intervenant i ON c.intervenant_id = i.id 
                    LEFT JOIN STRUCTURE s ON s.id = c.structure_id 
                    WHERE 
                    i.annee_id = " . $anneeContexte . "
                    AND lower(i.nom_usuel) like :search
                ";

        $em = $this->getEntityManager();

        $res  = Util::tableAjaxData($em, $post, $sql);
        $data = $res->getData();
        //On parcours le résultat pour transformer le statut de la signature en libellé
        foreach ($data['data'] as $key => $values) {
            $values['STATUT_SIGNATURE_ELECTRONIQUE'] = Signature::getStatusLabel($values['STATUT_SIGNATURE_ELECTRONIQUE']);
            $data['data'][$key]                      = $values;
        }

        $res->setData($data);

        return $res;
    }



    public function getContratWithProcessWaiting()
    {

        /*
         *         $dql   = "SELECT cm FROM " . CentreCout::class." cm WHERE cm.histoDestruction IS NULL";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();*/

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