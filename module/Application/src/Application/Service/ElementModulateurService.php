<?php

namespace Application\Service;

use Application\Entity\Db\ElementModulateur;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Modulateur;
use Application\Service\Traits\ModulateurServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ElementModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateurService extends AbstractEntityService
{
    use Traits\ElementPedagogiqueServiceAwareTrait;
    use ModulateurServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Application\Entity\Db\ElementModulateur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'epmod';
    }



    /**
     * Filtre la liste des services selon lecontexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($this->getServiceElementPedagogique(), $qb, 'elementPedagogique', false, $alias);

        $this->getServiceElementPedagogique()->finderByAnnee($this->getServiceContext()->getannee(), $qb); // Filtre d'année obligatoire

        return $qb;
    }



    public function addElementModulateur(ElementPedagogique $element, $codeModulateur)
    {
        $elementModulateurCollection = $element->getElementModulateur();
        foreach ($elementModulateurCollection as $elementModulateur) {
            $this->delete($elementModulateur);
        }
        $modulateur           = $this->getServiceModulateur()->getRepo()->findOneByCode($codeModulateur);
        $newElementModulateur = $this->newEntity();
        $newElementModulateur->setElement($element);
        $newElementModulateur->setModulateur($modulateur);
        $this->save($newElementModulateur);
        $this->entityManager->refresh($element);

        return $element;
    }
}