<?php

namespace Application\Service;

use Application\Service\AbstractEntityService;
use Application\Entity\Db\WfEtape as WfEtapeEntity;


/**
 * Description of Service
 *
 * @author Bertrand
 */
class WfEtape extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\WfEtape';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'e';
    }

    /**
     * Recherche une étapde par son code.
     * 
     * @param string $code
     * @return WfEtapeEntity
     */
    public function getByCode($code)
    {
        return $this->finderByCode($code)->getQuery()->getOneOrNullResult();
    }
    
    /**
     * Retourne la 1ère étape du workflow
     *
     * @return WfEtapeEntity
     */
    public function findPremiere()
    {
        $qb = $this->getRepo()->createQueryBuilder("e")
                ->join("e.etapeSuivante", "es")
                ->where("e.code = :code")->setParameter('code', WfEtapeEntity::CODE_DEBUT);
                
        $debut = $qb->getQuery()->getOneOrNullResult();
        
        return $debut/*->getEtapeSuivante()->first()*/;
    }

    /**
     * Retourne les étapes du workflow
     *
     * @return WfEtapeEntity[] Code => WfEtapeEntity
     */
    public function findAll()
    {
        $etape  = $this->findPremiere();
        $etapes = [$etape->getCode() => $etape];
        
        while (($suivante = $etape->getEtapeSuivante()->first())) {  // NB: il n'y a en fait qu'une seule étape suivante au maximum
            $etapes[$suivante->getCode()] = $suivante;
            $etape = $suivante;
        }
        
        return $etapes;
    }
}
