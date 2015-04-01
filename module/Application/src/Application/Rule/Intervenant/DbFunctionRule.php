<?php

namespace Application\Rule\Intervenant;

use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;

/**
 * Règle métier déterminant si un intervenant a reçu un type d'agrément donné.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DbFunctionRule extends \Application\Rule\AbstractRule
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    const MESSAGE_NO = 'messageNo';

    protected $function;

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_NO => "Echec!",
    ];

    /**
     *
     * @param string $function
     * @return self
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * Exécute la règle métier.
     *
     * @return array [ {id} => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->message(null);

        $sql = sprintf("BEGIN :res := %s(:intervenant, :structure); END;", $this->function);

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */

        // prepare statement
        $stmt = $em->getConnection()->prepare($sql);

        $stmt->bindParam('res', $res);
        $stmt->bindValue('intervenant', $this->getIntervenant()->getId());
        $stmt->bindValue('structure', $this->getStructure() ? $this->getStructure()->getId() : null);

        // execute prepared statement
        $stmt->execute();

//        var_dump(sprintf("%s(%s, %s) : %s",
//                $this->function,
//                $this->getIntervenant(),
//                $this->getStructure() ?: 'null',
//                $res));

        return $res;
    }

    public function isRelevant()
    {
        return true;
    }
}