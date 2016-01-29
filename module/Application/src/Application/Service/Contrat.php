<?php

namespace Application\Service;

use Application\Service\Traits\TypeContratAwareTrait;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Service\Traits\VolumeHoraireAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Contrat as ContratEntity;
use Application\Entity\Db\TypeContrat as TypeContratEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Fichier as FichierEntity;
use LogicException;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Contrat extends AbstractEntityService
{
    use ValidationAwareTrait;
    use TypeValidationAwareTrait;
    use TypeContratAwareTrait;
    use VolumeHoraireAwareTrait;

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
     * Retourne une nouvelle entité de la classe donnée
     * 
     * @param TypeContratEntity|string $type
     * @return \Application\Entity\Db\Contrat
     */
    public function newEntity($type = null)
    {
        $type = $this->normalizeTypeContrat($type);
        
        $entity = parent::newEntity();
        $entity->setTypeContrat($type);
        
        if ($type->getCode() === TypeContratEntity::CODE_CONTRAT) {
            $entity->setNumeroAvenant(0);
        }
        
        return $entity;
    }
    
    /**
     * Suppression (historisation) d'un projet de contrat/avenant.
     * 
     * @param \Application\Entity\Db\Contrat $contrat
     * @return self
     */
    public function supprimer(ContratEntity $contrat)
    {
        if ($contrat->getValidation()) {
            throw new LogicException("Impossible de supprimer un contrat/avenant validé.");
        }
        
        // recherche des VH liés au contrat
        $qb = $this->getServiceVolumeHoraire()->finderByContrat($contrat);
        $vhs = $qb->getQuery()->getResult();
        
        // détachement du contrat et des VH
        foreach ($vhs as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
            $contrat->removeVolumeHoraire($vh); // l'ordre importe!
            $vh->setContrat(null);
        }
        $this->getEntityManager()->flush();
        
        $this->delete($contrat);
        
        return $this;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Contrat $contrat
     * @return \Application\Service\Contrat
     */
    public function valider(ContratEntity $contrat)
    {
        $typeValidation        = $this->getServiceTypeValidation()->finderByCode(TypeValidationEntity::CODE_CONTRAT)->getQuery()->getSingleResult();
        
        $validation = $this->getServiceValidation()->newEntity($typeValidation)
                ->setIntervenant($contrat->getIntervenant())
                ->setStructure($contrat->getStructure());
        
        $contrat->setValidation($validation);
                
        $this->getEntityManager()->persist($validation);
        
        $this->getEntityManager()->flush($validation);
        $this->getEntityManager()->flush($contrat);
        
        return $validation;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Contrat $contrat
     * @return \Application\Service\Contrat
     */
    public function devalider(ContratEntity $contrat)
    {
        $typeContrat = $this->getServiceTypeContrat()->finderByCode(TypeContratEntity::CODE_CONTRAT)->getQuery()->getOneOrNullResult();
        
        $contrat->setValidation(null)
                ->setTypeContrat($typeContrat)
                ->setContrat(null)
                ->setNumeroAvenant(0)
                ->setDateRetourSigne(null);
        
        $this->getEntityManager()->flush($contrat);
        
        return $this;
    }
    
    /**
     * Calcule le numero d'avenant suivant : nombre d'avenants validés + 1.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @param bool $avenantsValidesSeulement Ne compter que les avenants validés ?
     * @return int
     */
    public function getNextNumeroAvenant(IntervenantEntity $intervenant, $avenantsValidesSeulement = true)
    {
        $typeAvenant        = $this->getServiceTypeContrat()->finderByCode(TypeContratEntity::CODE_AVENANT)->getQuery()->getOneOrNullResult();
        
        $qb = $this->finderByIntervenant($intervenant);
        $qb = $this->finderByType($typeAvenant, $qb);
        if ($avenantsValidesSeulement) {
            $qb = $this->finderByValidation(true, $qb);
        }
        $avenantsCount = (int) $qb->select('COUNT(' . $this->getAlias() . ')')->getQuery()->getSingleScalarResult();
        
        return $avenantsCount;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Contrat $contrat
     * @return \Application\Service\Contrat
     */
    public function requalifier(ContratEntity $contrat)
    {
        $typeContrat        = $this->getServiceTypeContrat()->finderByCode(TypeContratEntity::CODE_CONTRAT)->getQuery()->getOneOrNullResult();
        $typeAvenant        = $this->getServiceTypeContrat()->finderByCode(TypeContratEntity::CODE_AVENANT)->getQuery()->getOneOrNullResult();
        
        // calcul du numero d'avenant définitif : nombre d'avenants validés + 1
        $avenantsCount = $this->getNextNumeroAvenant($contrat->getIntervenant());
        
        // recherche du contrat initial de rattachement
        $qb = $this->finderByIntervenant($contrat->getIntervenant());
        $qb = $this->finderByType($typeContrat, $qb);
        $qb = $this->finderByValidation(true, $qb);
        $contratInitial = $qb->getQuery()->getOneOrNullResult();
        
        // requalif en avenant nécessaire ssi il existe un contrat validé
        if ($contratInitial) {
            $contrat->setTypeContrat($typeAvenant)
                    ->setContrat($contratInitial)
                    ->setNumeroAvenant($avenantsCount + 1);
        }
        else {
            $contrat->setNumeroAvenant(0);
        }
        
        return $this;
    }
    
    /**
     * Création des Fichiers déposés pour un contrat.
     * 
     * @param array $files Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png', 'size' => 321215]
     * @param ContratEntity $contrat
     * @param boolean $deleteFiles Supprimer les fichiers temporaires après création du Fichier
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
            
//            if (!$this->getAuthorize()->isAllowed($pj, Fichierssertion::PRIVILEGE_CREATE)) {
//                throw new UnAuthorizedException('Création du fichier suivant interdite : ' . $pj);
//            }
        
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

    /**
     * Recherche par type 
     *
     * @param TypeContratEntity|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeContrat", 'tc')
                ->andWhere("tc = :tc")
                ->setParameter('tc', $this->normalizeTypeContrat($type));

        return $qb;
    }
    
    /**
     * Recherche par intervenant concerné. 
     *
     * @param IntervenantEntity $intervenant
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByIntervenant(IntervenantEntity $intervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.intervenant", 'i')
                ->andWhere("i = :intervenant")
                ->setParameter('intervenant', $intervenant);

        return $qb;
    }
    
    /**
     * Recherche par type de validation
     *
     * @param TypeValidationEntity|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByTypeValidation($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $type = $this->getServiceValidation()->normalizeTypeValidation($type);
        
        $qb
                ->join("$alias.validation", "v")
                ->join("v.typeValidation", 'tv')
                ->andWhere("tv = :tv")->setParameter('tv', $type);

        return $qb;
    }

    /**
     * Retourne la liste des services dont les volumes horaires sont validés ou non.
     *
     * @param boolean|\Application\Entity\Db\Validation $validation <code>true</code>, <code>false</code> ou 
     * bien une Validation précise
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByValidation($validation, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($validation instanceof \Application\Entity\Db\Validation) {
            $qb
                    ->join("$alias.validation", "v")
                    ->andWhere("v = :validation")->setParameter('validation', $validation);
        }
        else {
            $value = $validation ? 'IS NOT NULL' : 'IS NULL';
            $qb->andWhere("$alias.validation $value");
        }
        
        return $qb;
    }
    
    /**
     * 
     * @param TypeContrat|string $type
     * @return TypeContratEntity
     * @throws RuntimeException
     */
    private function normalizeTypeContrat($type)
    {
        if (null === $type) {
            return null;
        }
        if ($type instanceof TypeContratEntity) {
            return $type;
        }
        
        $qb = $this->getServiceTypeContrat()->finderByCode($code = $type);
        $type = $qb->getQuery()->getOneOrNullResult();
        if (!$type) {
            throw new RuntimeException("Aucun type de contrat trouvé avec le code '$code'.");
        }
        
        return $type;
    }
}