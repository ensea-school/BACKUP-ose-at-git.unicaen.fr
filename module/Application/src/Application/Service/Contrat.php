<?php

namespace Application\Service;

use Application\Service\Traits\TypeContratAwareTrait;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Contrat as ContratEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Fichier as FichierEntity;
use LogicException;

/**
 * Description of Contrat
 *
 */
class Contrat extends AbstractEntityService
{
    use ValidationAwareTrait;
    use TypeValidationAwareTrait;
    use TypeContratAwareTrait;
    use VolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return ContratEntity::class;
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
        list($qb, $alias) = $this->initQuery($qb, $alias);

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
        list($qb, $alias) = $this->initQuery($qb, $alias);

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
     * @param Intervenant $intervenant              Intervenant concerné
     * @param bool        $avenantsValidesSeulement Ne compter que les avenants validés ?
     *
     * @return int
     */
    public function getNextNumeroAvenant(IntervenantEntity $intervenant, $avenantsValidesSeulement = true)
    {
        $qb = $this->finderByIntervenant($intervenant);
        $qb = $this->finderByTypeContrat($this->getServiceTypeContrat()->getAvenant(), $qb);
        if ($avenantsValidesSeulement) {
            $qb = $this->finderByValidation(true, $qb);
        }
        $avenantsCount = (int)$qb->select('COUNT(' . $this->getAlias() . ')')->getQuery()->getSingleScalarResult();

        return $avenantsCount;
    }



    /**
     * Création des Fichiers déposés pour un contrat.
     *
     * @param array         $files       Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png',
     *                                   'size' => 321215]
     * @param ContratEntity $contrat
     * @param boolean       $deleteFiles Supprimer les fichiers temporaires après création du Fichier
     *
     * @return Fichier[]
     */
    public function creerFichiers($files, ContratEntity $contrat, $deleteFiles = true)
    {
        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }
        $instances = [];

        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = $file['name'];
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new FichierEntity())
                ->setType($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);

            $contrat->addFichier($fichier);

            $this->getEntityManager()->persist($fichier);
            $instances[] = $fichier;

            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $instances;
    }

}