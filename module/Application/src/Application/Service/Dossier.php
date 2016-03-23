<?php

namespace Application\Service;

use Application\Entity\Db\Dossier as DossierEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

/**
 * Description of Dossier
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Dossier extends AbstractEntityService
{    
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return DossierEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'd';
    }
    
    /**
     * Enregistrement d'un dossier.
     * 
     * NB: tout le travail est déjà fait via un formulaire en fait! 
     * Cette méthode existe surtout pour déclencher l'événement de workflow.
     * 
     * @param \Application\Entity\Db\Dossier $dossier
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function enregistrerDossier(DossierEntity $dossier, IntervenantEntity $intervenant)
    {
        $this->getEntityManager()->persist($dossier);
        $this->getEntityManager()->persist($intervenant);
        
        $this->getEntityManager()->flush();
    }
    
    /**
     * Suppression d'un dossier.
     * 
     * @param \Application\Entity\Db\Dossier $dossier
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function supprimerDossier(DossierEntity $dossier, IntervenantEntity $intervenant)
    {
        $intervenant->setDossier(null);
        $this->getEntityManager()->remove($dossier);
        
        $this->getEntityManager()->flush();
    }

}