<?php

namespace Contrat\Service;

use Application\Entity\Db\Fichier;
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
use Mission\Entity\Db\Mission;
use RuntimeException;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
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



    public function saveContratFichier(Contrat $contrat)
    {
        $contratFilePath = $this->generer($contrat, false, true);
        $files           = [];
        $files[]         = [
            'tmp_name' => $contratFilePath,
            'type'     => mime_content_type($contratFilePath),
            'size'     => filesize($contratFilePath),
            'name'     => basename($contratFilePath),
        ];
        $this->creerFichiers($files, $contrat);

        return true;
    }



    public function envoyerContratSignatureElectronique(Contrat $contrat)
    {
        //1- On génére le contrat et on le stock temporairement
        $contratFilePath = $this->generer($contrat, false, true);
        $filename        = basename($contratFilePath);
        //2- On récupére le parapheur
        $letterFileParam = $this->getServiceParametres()->get('signature_electronique_parapheur');
        $letterFile      = $this->getSignatureService()->getLetterfileService()->getLetterFileStrategy($letterFileParam);
        $letterFileLevel = $contrat->getIntervenant()->getStatut()->getContratSignatureType();

        /**
         * @var Contrat $contrat
         */

        $libelleContrat = ($contrat->estUnAvenant()) ? 'Signature de l\'avenant N°' . $contrat->getId() : 'Signature du Contrat N°' . $contrat->getId();
        $signature      = new Signature();
        $signature->setLetterfileKey($letterFile->getName());
        $signature->setType($letterFileLevel)
            ->setLabel('Test')
            ->setDateCreated(new \DateTime())
            ->setAllSignToComplete(false)
            ->setDescription($libelleContrat)
            ->setDocumentPath($filename);

        //On traite les destinataires
        //TODO : on doit récupérer le statut de l'intervenant pour savoir quel rôle va devoir signer les contrats electroniquement
        //TODO : Rajouter un paramètre au niveau du statut pour savoir si tous les utilisateurs affectés au rôle en question doivent signer le contrat
        /**
         * @var Statut $statut
         */
        $statut         = $contrat->getIntervenant()->getStatut();
        $roleSignataire = $statut->getContratSignatureRole();


        //Et envoyer au email utilisateur
        $destinataires  = [];
        $data['emails'] = 'antony.lecourtes@unicaen.fr';
        $postedEmails   = explode(',', $data['emails']);
        foreach ($postedEmails as $email) {
            $sr = new SignatureRecipient();
            $sr->setSignature($signature);
            $sr->setStatus(Signature::STATUS_SIGNATURE_DRAFT);
            $sr->setEmail($email);
            $sr->setPhone('0679434732');
            $destinataires[] = $sr;
        }
        $signature->setRecipients($destinataires);
        $this->getSignatureService()->saveNewSignature($signature, true);
        $contrat->setSignature($signature);
        $this->save($contrat);

        return $contrat;
    }



    public function supprimerSignatureElectronique(Contrat $contrat): Contrat
    {
        $signature = $contrat->getSignature();

        if ($signature instanceof Signature && !$signature->isFinished()) {
            try {
                //On supprimer la signature du parapheur
                $this->getSignatureService()->deleteSignature($signature);
                //On met à jour le contrat
                $contrat->setSignature(null);
                $this->save($contrat);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return $contrat;
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
            $document->saveToFile($config['documents_path'] . $fileName);

            return $config['documents_path'] . $fileName;
        }
        if ($download) {
            $document->download($fileName);

            return null;
        } else {
            return $document;
        }
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
                AND c.dateRetourSigne IS NOT NULL
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
}