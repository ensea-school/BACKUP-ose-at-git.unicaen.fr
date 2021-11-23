<?php

namespace Application\Filter;

use Application\Entity\Db\Intervenant;
use Application\Service\Traits\DossierServiceAwareTrait;
use Laminas\Filter\AbstractFilter;

class IntervenantEmailFormatter extends AbstractFilter
{
    use DossierServiceAwareTrait;

    private $intervenantsWithNoEmail = [];



    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value Un ou plusieurs objets de type Intervenant
     *
     * @return array email => name
     * @throws \RuntimeException Rencontre d'un intervenant sans adresse mail
     * @throws \RuntimeException If filtering $value is impossible
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

            $dossier = $this->getServiceDossier()->getByIntervenant($intervenant);
            if ($dossier->getId() && $dossier->getEmailPerso()) {
                $email = $dossier->getEmailPerso();
            } else {
                $email = $intervenant->getEmailPro();
            }

            if (!$email) {
                $this->intervenantsWithNoEmail[] = $intervenant;
            } else {
                $emails = [
                    $email => (string)$intervenant,
                ];
            }
        } elseif (is_array($value)) {
            foreach ($value as $intervenant) {
                $emails = array_merge($emails, $this->filterRecursive($intervenant));
            }
        } else {
            throw new \RuntimeException("Type d'entrée attendue : Intervenant ou Intervenant[].");
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
     *
     * @return array
     * @throws \RuntimeException If filtering $value is impossible
     * @throws \RuntimeException Rencontre d'un intervenant sans adresse mail
     */
    static public function filtered($value)
    {
        $instance = new static();

        return $instance->filter($value);
    }
}