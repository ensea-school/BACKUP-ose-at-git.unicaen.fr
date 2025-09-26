<?php

namespace OffreFormation\Service;

use Application\Provider\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use OffreFormation\Entity\Db\CentreCoutEp;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use Paiement\Service\CentreCoutServiceAwareTrait;

/**
 * Description of CentreCoutEpService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCoutEpService extends AbstractEntityService
{
    use SourceServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use TypeHeuresServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return CentreCoutEp::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ccep';
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return mixed
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }



    /**
     * Sauvegarde un centre de coûts
     *
     * @param CentreCoutEp $entity
     *
     * @return CentreCoutEp
     * @throws \RuntimeException
     */
    public function save($entity)
    {
        if (!$this->getAuthorize()->isAllowed($entity, Privileges::ODF_CENTRES_COUT_EDITION)) {
            throw new UnAuthorizedException('Vous n\'avez pas les droits requis pour associer/dissocier un centre de coûts de cet enseignement');
        }

        if (!$entity->getSourceCode()
            && ($cc = $entity->getCentreCout())
            && ($th = $entity->getTypeHeures())
            && ($ep = $entity->getElementPedagogique())
        ) {
            $entity->setSourceCode(uniqid($cc->getId() . '_' . $th->getId() . '_' . $ep->getId()));
        }

        return parent::save($entity);
    }



    /**
     * Ajoute des centres de coût par types d'heures sur élement pédagogique
     *
     * @param ElementPedagogique $element
     * @param Array              $centreCouts tableau type heures => centre de coût
     *
     * @return ElementPedagogique
     */
    public function addElementCentreCout(ElementPedagogique $element, $centreCouts)
    {
        $centreCoutEpCollection = $element->getCentreCoutEp()->toArray();
        $mergeCentreCoutEp      = [];

        foreach ($centreCoutEpCollection as $centreCoutEp) {
            $mergeCentreCoutEp[$centreCoutEp->getTypeHeures()->getCode()] = $centreCoutEp;
        }

        foreach ($centreCouts as $th => $cc) {
            //Mise à jour ou delete
            if (array_key_exists($th, $mergeCentreCoutEp)) {
                $centreCoutEp = $mergeCentreCoutEp[$th];
                if (empty($cc)) {
                    $this->delete($centreCoutEp);
                } else {
                    $centreCoutEntity = $this->getServiceCentreCout()->getRepo()->findOneByCode($cc);
                    $centreCoutEp->setCentreCout($centreCoutEntity);
                    $this->save($centreCoutEp);
                }
            } else {
                if (!empty($cc)) {
                    //Creation
                    $centreCoutEntity = $this->getServiceCentreCout()->getRepo()->findOneByCode($cc);
                    $typeHeuresEntity = $this->getServiceTypeHeures()->getRepo()->findOneByCode($th);
                    try {
                        $centreCoutEntity = $this->getServiceCentreCout()->getRepo()->findOneByCode($cc);
                        $typeHeuresEntity = $this->getServiceTypeHeures()->getRepo()->findOneByCode($th);
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage("Centre de coût ou Modulateur inexistant");
                    }
                    $newCentreCoutEp = $this->newEntity();
                    $newCentreCoutEp->setCentreCout($centreCoutEntity);
                    $newCentreCoutEp->setElementPedagogique($element);
                    $newCentreCoutEp->setTypeHeures($typeHeuresEntity);
                    $this->save($newCentreCoutEp);
                }
            }
        }
        //refresh l'entité pour l'affichage utilisateur post traitement
        $this->entityManager->refresh($element);

        return $element;
    }
}