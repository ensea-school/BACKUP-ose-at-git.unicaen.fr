<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Service\ModulateurServiceAwareTrait;
use RuntimeException;

/**
 * Description of ElementModulateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateurService extends AbstractEntityService
{
    use \OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
    use ModulateurServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \OffreFormation\Entity\Db\ElementModulateur::class;
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
    public function finderByContext(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($this->getServiceElementPedagogique(), $qb, 'elementPedagogique', false, $alias);

        $this->getServiceElementPedagogique()->finderByAnnee($this->getServiceContext()->getannee(), $qb); // Filtre d'année obligatoire

        return $qb;
    }



    /**
     * Ajoute un élément modulateur à un élément pédagogique
     *
     * @param ElementPedagogique $element
     * @param String             $codeModulateur Code du modulateur
     *
     * @return ElementPedagogique
     */


    public function addElementModulateur(ElementPedagogique $element, $codeModulateur)
    {

        $elementModulateurCollection = $element->getElementModulateur();
        if ($elementModulateurCollection->count() != 0) {
            foreach ($elementModulateurCollection as $elementModulateur) {
                if (empty($codeModulateur)) {
                    $this->delete($elementModulateur);
                } else {
                    $modulateur = $this->getServiceModulateur()->getRepo()->findOneByCode($codeModulateur);
                    $elementModulateur->setModulateur($modulateur);
                    $this->save($elementModulateur);
                }
            }
        } else {
            //Uniquement si le code modulateur n'est pas vide.
            if (!empty($codeModulateur)) {
                $modulateur           = $this->getServiceModulateur()->getRepo()->findOneByCode($codeModulateur);
                $newElementModulateur = $this->newEntity();
                $newElementModulateur->setElement($element);
                $newElementModulateur->setModulateur($modulateur);
                $this->save($newElementModulateur);
            }
        }
        //refresh l'entité pour l'affichage utilisateur post traitement
        $this->entityManager->refresh($element);

        return $element;
    }
}