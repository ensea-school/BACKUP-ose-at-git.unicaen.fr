<?php

namespace Contrat\Service;

use Application\Entity\Db\Fichier;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Doctrine\ORM\QueryBuilder;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Mission\Entity\Db\Mission;
use RuntimeException;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;


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



    public function generer(Contrat $contrat, $download = true)
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
        /*$config = \OseAdmin::instance()->config()->get('signature');;

        $document->saveToFile($config['tmp_dir_contrat'] . $fileName);

        exit;*/

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
}