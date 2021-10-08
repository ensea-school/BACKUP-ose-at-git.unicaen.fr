<?php

namespace Application\ORM\Persister;

use Application\Entity\Db\Annee;
use Application\Interfaces\ParametreEntityInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Zend\Hydrator\ClassMethods;

class ParametreEntityPersister
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ParametreEntityInterface
     */
    protected $entity;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var array
     */
    protected $key = [];

    /**
     * @var ClassMethods
     */
    protected $hydrator;



    public function __construct(EntityManager $em)
    {
        $this->em       = $em;
        $this->hydrator = new ClassMethods();
    }



    public function save(ParametreEntityInterface $entity)
    {
        $classname      = get_class($entity);
        $this->entity   = $entity;
        $this->metadata = $this->em->getClassMetadata($classname);

        if ($entity->isSaveOnlyAnneeCourante()) {
            // Si on ne sauvegarde que pour l'année en cours, alors on doit "figer" les autres années
            $params          = $this->getKey();
            $params['annee'] = $this->em->find(Annee::class, $this->entity->getAnnee()->getId() + 1);
            $next            = $this->em->getRepository(get_class($this->entity))->findOneBy($params);
            if (!$next) {
                $next = new $classname;
                $this->hydrator->hydrate($params, $next);
                //$next->
            }
            var_dump($next);
        }
        var_dump('save');
    }



    public function getKey(): array
    {
        if (!isset($this->key)) {

            $this->key = [];

            $ucc  = $this->getUniqueConstraintCols();
            $cols = $this->getColumns();

            $data = $this->hydrator->extract($this->entity);
            foreach ($ucc as $col) {
                $col             = $cols[$col];
                $this->key[$col] = $data[$col];
            }
        }

        return $this->key;
    }



    public function getOldValues(): array
    {
        
    }



    protected function getUniqueConstraintCols(): array
    {
        $tableName = $this->metadata->table['name'];

        if (!isset($this->metadata->table['uniqueConstraints'][$tableName . '_UN'])) {
            throw new \Exception('Contrainte d\'unicité non trouvée');
        }

        $ucc = $this->metadata->table['uniqueConstraints'][$tableName . '_UN']['columns'];

        foreach ($ucc as $i => $consCol) {
            if ($consCol == 'ANNEE_ID') {
                unset($ucc[$i]);
            }
        }

        return $ucc;
    }



    protected function getColumns(): array
    {
        $columns = $this->metadata->fieldNames;
        foreach ($this->metadata->associationMappings as $am) {
            $col           = array_keys($am['joinColumnFieldNames'])[0];
            $columns[$col] = $am['fieldName'];
        }

        return $columns;
    }
}