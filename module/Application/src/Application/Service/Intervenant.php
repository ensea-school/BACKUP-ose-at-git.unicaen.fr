<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\Finder\FinderIntervenantPermanentWithServiceReferentiel;

/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends AbstractEntityService
{
    use \Application\Service\ContextProviderAwareTrait;
    
    /**
     * 
     * @return \Application\Entity\Db\Finder\FinderIntervenantPermanentWithServiceReferentiel
     */
    public function getFinderIntervenantPermanentWithServiceReferentiel()
    {
        $qb = new FinderIntervenantPermanentWithServiceReferentiel($this->getEntityManager(), $this->getContextProvider());

        return $qb;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Service\Intervenant
     * @throws \Common\Exception\DomainException
     */
    public function checkIntervenantForServiceReferentiel(IntervenantEntity $intervenant)
    {
        // verif type d'intervenant
        if (!$intervenant instanceof IntervenantPermanent) {
            throw new \Common\Exception\DomainException(
                    "La saisie de service référentiel n'est possible que pour les intervenants permanents.");
        }
        
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\DbRole) {
            if ($intervenant->getStructure() !== $role->getStructure() 
                    && $intervenant->getStructure()->getParenteNiv2() !== $role->getStructure()->getParenteNiv2()) {
                throw new \Common\Exception\DomainException(
                        sprintf("L'intervenant %s n'est pas affecté à votre structure de responsabilité (%s) ni à l'une de ses sous-structures.",
                                $intervenant,
                                $role->getStructure()));
            }
        }
        
        return $this;
    }
    
    

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Intervenant';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'int';
    }
    
    /**
     * Importe un intervenant non encore importé.
     * 
     * @param string $sourceCode Code source
     * @return IntervenantEntity
     * @throws RuntimeException Intervenant déjà importé ou introuvable après import
     */
    public function importer($sourceCode)
    {
        $repo = $this->getEntityManager()->getRepository($this->getEntityClass());
        
        if (($intervenant = $repo->findBySourceCode($sourceCode))) {
            throw new RuntimeException("L'intervenant spécifié a déjà été importé : sourceCode = $sourceCode.");
        }
        
        $import = $this->getServiceLocator()->get('importProcessusImport'); /* @var $import \Import\Processus\Import */
        $import->intervenant($sourceCode);

        if (!($intervenant = $repo->findOneBySourceCode($sourceCode))) {
            throw new RuntimeException("L'intervenant suivant est introuvable après import : sourceCode = $sourceCode.");
        }
        
        return $intervenant;
    }

    /**
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");
        return parent::getList($qb, $alias);
    }

}
