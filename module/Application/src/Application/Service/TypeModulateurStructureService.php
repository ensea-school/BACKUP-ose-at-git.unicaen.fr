<?php

namespace Application\Service;

use Application\Entity\Db\TypeModulateurStructure;
use Application\Service\Traits\TypeModulateurStructureServiceAwareTrait;

/**
 * Description of TypeModulateurStructureService
 */
class TypeModulateurStructureService extends AbstractEntityService
{
use TypeModulateurStructureServiceAwareTrait;
    /**
     * Liste des types de modulateur par structure
     *
     * @var TypeModulateurStructure[]
     */
    protected $typesModulateurStructure;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeModulateurStructure::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tmd';
    }

    public function existe(TypeModulateurStructure $tms){
    /* @var $tbltms typeModulateurStructure[] */
    /* @var $elt typeModulateurStructure */
    $dql = "
        SELECT
          m
        FROM
          Application\Entity\Db\TypeModulateurStructure m
        WHERE
        m.histoDestruction IS NULL
        AND m.id != :id
        AND m.typeModulateur = :typemodulateur
        AND m.structure = :structure";

    $query = $this->getEntityManager()->createQuery($dql);
    $query->setParameter('id',$tms->getId());
    $query->setParameter('typemodulateur',$tms->getTypeModulateur());
    $query->setParameter('structure',$tms->getStructure());
    $tbltms = $query->getResult();
    // recherche de doublon
    $ok = true;
    foreach ($tbltms as $elt) {
        if (((!$elt->getAnneeDebut()) or ($elt->getAnneeDebut()->getId() <= $tms->getAnneeFin()->getId())) and ((!$elt->getAnneeFin())
                or ($elt->getAnneeFin()->getId() >= $tms->getAnneeDebut()->getId()))) {
            $ok = false;
        }
    }
    return $ok;
}

    /**
     * @param TypeModulateurStructure $entity
     *
     * @return TypeModulateurStructure
     */
    public function save($entity)
    {
        if (!$this->existe($entity)) {
            throw new \Exception('Un élément est en doublon avec l\'ajout demandé!');
        }

        return parent::save($entity);
    }
}