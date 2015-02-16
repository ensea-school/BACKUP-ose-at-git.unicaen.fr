<?php

namespace Application\Service;

use Application\Entity\Db\MiseEnPaiement as MiseEnPaiementEntity;

/**
 * Description of MiseEnPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiement extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\MiseEnPaiement';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'mep';
    }

    /**
     * Sauvegarde tous les changements intervenus dans un ensemble de mises en paiement
     *
     * @param array $changements
     */
    public function saveChangements( $changements )
    {
        foreach( $changements as $miseEnPaiementId => $data ){
            if (0 === strpos($miseEnPaiementId, 'new')){ // insert
                $miseEnPaiement = $this->newEntity();
                /* @var $miseEnPaiement MiseEnPaiementEntity */
                $this->hydrateFromChangements($miseEnPaiement, $data);
                $this->save($miseEnPaiement);
            }else{
                $miseEnPaiement = $this->get( $miseEnPaiementId );
                if (null == $data || 'removed' == $data){ // delete
                    $this->delete($miseEnPaiement);
                }else{ // update
                    $this->hydrateFromChangements($miseEnPaiement, $data);
                    $this->save($miseEnPaiement);
                }
            }
        }
    }

    private function hydrateFromChangements( MiseEnPaiementEntity $object, $data )
    {
        if (isset($data['heures'])){
            $object->setHeures( (float)$data['heures'] );
        }

        if (isset($data['centre-cout-id'])){
            $serviceCentreCout = $this->getServiceLocator()->get('applicationCentreCout');
            /* @var $serviceCentreCout CentreCout */
            $object->setCentreCout( $serviceCentreCout->get( (integer)$data['centre-cout-id'] ) );
        }

        if (isset($data['formule-resultat-service-id'])){
            $serviceFormuleResultatService = $this->getServiceLocator()->get('ApplicationFormuleResultatService');
            /* @var $serviceFormuleResultatService FormuleResultatService */
            $object->setFormuleResultatService( $serviceFormuleResultatService->get( (integer)$data['formule-resultat-service-id'] ) );
        }

        if (isset($data['formule-resultat-service-referentiel-id'])){
            $serviceFormuleResultatServiceReferentiel = $this->getServiceLocator()->get('ApplicationFormuleResultatServiceReferentiel');
            /* @var $serviceFormuleResultatServiceReferentiel FormuleResultatServiceReferentiel */
            $object->setFormuleResultatServiceReferentiel( $serviceFormuleResultatServiceReferentiel->get( (integer)$data['formule-resultat-service-referentiel-id'] ) );
        }

        if (isset($data['type-heures-id'])){
            $serviceTypeHeures = $this->getServiceLocator()->get('applicationTypeHeures');
            /* @var $serviceTypeHeures TypeHeures */
            $object->setTypeHeures( $serviceTypeHeures->get( (integer)$data['type-heures-id'] ) );
        }
    }

}