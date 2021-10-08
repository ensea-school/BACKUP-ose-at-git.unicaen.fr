<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/* @var $a \Plafond\Service\PlafondStructureService::class */
$a = $container->get(\Plafond\Service\PlafondStructureService::class);

$ps2 = $a->get(1);
$ps  = new \Plafond\Entity\Db\PlafondStructure();
$ps->setAnnee($ps2->getAnnee());
$ps->setPlafond($ps2->getPlafond());
$ps->setStructure($ps2->getStructure());
$ps->setHeures(25);
$ps->setSaveOnlyAnneeCourante(true);
//$ps->setAnnee($container->get(\Application\Service\AnneeService::class)->get(2016));

$em = $a->getEntityManager();

$pep = new \Application\ORM\Persister\ParametreEntityPersister($em);
$pep->save($ps);

//var_dump($changes);
//var_dump($classMetadata->fieldMappings);
//var_dump($classMetadata->associationMappings);


//$em->persist($ps);
//$em->flush($ps);

return;
/*
$persister     = new OseEntityPersister($em, $classMetadata);
$em->getUnitOfWork()->computeChangeSet($classMetadata, $ps);
$persister->update($ps);
*/
$nsp = new \Plafond\Entity\Db\PlafondStructure();
$nsp->setPlafond($ps->getPlafond());
$nsp->setStructure($ps->getStructure());
$nsp->setHeures(87);
$nsp->setAnnee($container->get(\Application\Service\AnneeService::class)->get(2021));

$em->persist($nsp);
$em->flush($nsp);


function saveParametre(\Application\Interfaces\ParametreEntityInterface $entity, \Doctrine\ORM\EntityManager $em)
{
    $class         = get_class($entity);
    $classMetadata = $em->getClassMetadata($class);

    $uow = $em->getUnitOfWork();
    $uow->computeChangeSet($classMetadata, $entity);
    $changes = $uow->getEntityChangeSet($entity);

    $tableName         = $classMetadata->table['name'];
    $uniqueConstraints = $classMetadata->table['uniqueConstraints'];
    if (!isset($uniqueConstraints[$tableName . '_UN'])) throw new \Exception('Contrainte d\'unicité non trouvée');

    $consCols = $uniqueConstraints[$tableName . '_UN']['columns'];
    foreach ($consCols as $i => $consCol) {
        if ($consCol == 'ANNEE_ID') {
            unset($consCols[$i]);
        }
    }


    $mapping = [];
    foreach ($classMetadata->fieldMappings as $metaConfig) {
        $mapping[$metaConfig['columnName']] = $metaConfig['columnName'];
    }

    var_dump($classMetadata);
}





class OseEntityPersister extends \Doctrine\ORM\Persisters\Entity\BasicEntityPersister
{

    /**
     * {@inheritdoc}
     */
    public function update($entity)
    {
        $tableName  = $this->class->getTableName();
        $updateData = $this->prepareUpdateData($entity);

        if (!isset($updateData[$tableName]) || !($data = $updateData[$tableName])) {
            return;
        }

        $isVersioned     = $this->class->isVersioned;
        $quotedTableName = '"PLAFOND_STRUCTURE"';
        //$quotedTableName = $this->quoteStrategy->getTableName($this->class, $this->platform);

        $this->updateTable($entity, $quotedTableName, $data, $isVersioned);

        if ($isVersioned) {
            $id = $this->em->getUnitOfWork()->getEntityIdentifier($entity);

            $this->assignDefaultVersionValue($entity, $id);
        }
    }
}