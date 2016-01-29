<?php
namespace Application\Form\Intervenant;

use Application\Entity\Db\Dossier as DossierEntity;
use Application\Constants;
use DateTime;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 *
 *
 */
class DossierFieldsetDoctrineHydrator extends DoctrineObject
{    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  DossierEntity $dossier
     * @return object
     */
    public function hydrate(array $data, $dossier)
    {
        $data['rib'] = implode('-', $data['rib']);
        
        if (isset($data['dateNaissance'])) {
            $data['dateNaissance'] = DateTime::createFromFormat(Constants::DATE_FORMAT, $data['dateNaissance']);
        }
        
        $this->processPremierRecrutement($data);
        
        return parent::hydrate($data, $dossier);
    }

    /**
     * Extract values from an object
     *
     * @param  DossierEntity $dossier
     * @return array
     */
    public function extract($dossier)
    {
        $data = parent::extract($dossier);

        if ($dossier->getPaysNaissance() === null) {
            unset($data['paysNaissance']); // indispensable pour que la valeur par défaut soit sélectionnée!
        }
        if ($dossier->getDateNaissance() && $dossier->getDateNaissance() instanceof DateTime) {
            $data['dateNaissance'] = $dossier->getDateNaissance()->format(Constants::DATE_FORMAT);
        }
        if ($dossier->getRib()) {
            $data['rib'] = array_combine(['bic', 'iban'], explode('-', $dossier->getRib()));
        }
        
        $this->processPremierRecrutement($data);

        return $data;
    }
    
    /**
     * Force la valeur du témoin "premier recrutement" si elle est absente des données fournies.
     * 
     * @param array $data
     * @return self
     */
    private function processPremierRecrutement(&$data)
    {
        if (!array_key_exists('premierRecrutement', $data)) {
            $data['premierRecrutement'] = '0';
        }
        
        return $this;
    }
}