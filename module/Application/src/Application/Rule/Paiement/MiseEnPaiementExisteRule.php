<?php

namespace Application\Rule\Paiement;

use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use LogicException;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MiseEnPaiementExisteRule extends \Application\Rule\AbstractRule
{
    use MiseEnPaiementAwareTrait;
    use IntervenantAwareTrait;
    
    const MESSAGE_EXISTE     = 'messageExiste';
    const MESSAGE_EXISTE_PAS = 'messageExistePas';

    /**
     * Témoin indiquant si l'on parle simplement de *demande* de mise en paiement.
     * @var boolean
     */
    private $isDemande = false;
    
    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_EXISTE     => "Il existe   une    %s mise en paiement.",
        self::MESSAGE_EXISTE_PAS => "Il n'existe aucune %s mise en paiement.",
    ];

    /**
     * Constructeur.
     * 
     * @param boolean $isDemande Témoin indiquant si l'on parle de *demande* de mise en paiement.
     */
    public function __construct($isDemande = false)
    {
        parent::__construct();
        
        $this->setIsDemande($isDemande);
    }
    
    /**
     * Exécution de la règle.
     *
     * @return boolean
     */
    public function execute()
    {
        if (! $this->getIntervenant()) {
            throw new LogicException("Aucun intervenant spécifié.");
        }
        
        $qb = $this->getServiceMiseEnPaiement()->finderByIntervenants([ $this->getIntervenant() ]);
        $this->getServiceMiseEnPaiement()->finderByHistorique($qb);
        $qb->select(sprintf("COUNT(%s)", $this->getServiceMiseEnPaiement()->getAlias()));
        
        $alias = $this->getServiceMiseEnPaiement()->getAlias();
        if ($this->getIsDemande()) {
            $qb->andWhere("$alias.dateMiseEnPaiement IS NULL");
        }
        else {
            $qb->andWhere("$alias.dateMiseEnPaiement IS NOT NULL");
        }
        
        $demandeMepExiste = (int) $qb->getQuery()->getSingleScalarResult();
        
        if ($demandeMepExiste) {
            $this->message(self::MESSAGE_EXISTE);
            return true;
        }
        else {
            $this->message(self::MESSAGE_EXISTE_PAS);
            return false;
        }
    }
    
    /**
     *
     * @return boolean
     */
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * Retourne le Témoin indiquant si l'on parle simplement de *demande* de mise en paiement.
     * 
     * @return boolean
     */
    function getIsDemande()
    {
        return $this->isDemande;
    }

    /**
     * Spécifie le Témoin indiquant si l'on parle simplement de *demande* de mise en paiement.
     * 
     * @param boolean $isDemande
     * @return self
     */
    function setIsDemande($isDemande = true)
    {
        $this->isDemande = $isDemande;
        
        foreach ($this->messageTemplates as $key => $value) {
            $this->messageTemplates[$key] = sprintf($value, $this->getIsDemande() ? "demande de" : '');
        }
        
        return $this;
    }
}