<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 20/07/15
 * Time: 11:52
 */

namespace Common\Filter;


use Application\Entity\Db\Intervenant;
use Common\Exception\RuntimeException;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception\RuntimeException as FilterRuntimeException;

class IntervenantEmailFormatter extends AbstractFilter
{
    private $intervenantsWithNoEmail = [];

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value Un ou plusieurs objets de type Intervenant
     * @throws FilterRuntimeException If filtering $value is impossible
     * @throws RuntimeException Rencontre d'un intervenant sans adresse mail
     * @return array email => name
     */
    public function filter($value)
    {
        $this->intervenantsWithNoEmail = [];

        return $this->filterRecursive($value);
    }

    private function filterRecursive($value)
    {
        $emails = [];

        if ($value instanceof Intervenant) {
            $intervenant = $value;
            $email = $intervenant->getEmailPerso(true);
            if (! $email) {
                $this->intervenantsWithNoEmail[$intervenant->getSourceCode()] = $intervenant;
            }

            $emails = [
                $email => $intervenant->getNomComplet()
            ];
        }
        elseif (is_array($value)) {
            foreach ($value as $intervenant) {
                $emails = array_merge($emails, $this->filterRecursive($intervenant));
            }
        }
        else {
            throw new FilterRuntimeException("Type d'entrée attendue : Intervenant ou Intervenant[].");
        }

        return $emails;
    }

    /**
     * Retourne les intervenants sans adresse mail rencontrés lors du filtrage.
     *
     * @return array Intervenant[]
     */
    public function getIntervenantsWithNoEmail()
    {
        return $this->intervenantsWithNoEmail;
    }

    /**
     * Convenient method.
     *
     * @param mixed $value
     * @return array
     * @throws FilterRuntimeException If filtering $value is impossible
     * @throws RuntimeException Rencontre d'un intervenant sans adresse mail
     */
    static public function filtered($value)
    {
        $instance = new static();

        return $instance->filter($value);
    }
}