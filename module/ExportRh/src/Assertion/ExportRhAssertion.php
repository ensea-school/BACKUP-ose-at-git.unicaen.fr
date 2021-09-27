<?php

namespace ExportRh\Assertion;


use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use ExportRh\Service\ExportRhServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Description of ExportRhAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class ExportRhAssertion extends AbstractAssertion
{
    use ExportRhServiceAwareTrait;
    use ContextServiceAwareTrait;

    const PRIV_CAN_INTERVENANT_EXPORT_RH = 'intervenant-export-rh';



    /* ---- Routage général ---- */
    public function __invoke(array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat

        $config        = $this->getMvcEvent()->getApplication()->getServiceManager()->get('Config');
        $exportRhActif = $config['export-rh']['actif'];
        //Si le module export rh n'est pas activé alors on renvoie toujours false
        if (!$exportRhActif) {
            return false;
        }

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case self::PRIV_CAN_INTERVENANT_EXPORT_RH:
                        return $this->assertIntervenantExportRh($entity);
                }
            break;
        }


        return true;
    }



    protected function assertIntervenantExportRh(Intervenant $intervenant)
    {
        if (!$this->getRole()->hasPrivilege(Privileges::EXPORT_RH_SYNC)) {
            return false;
        }

        $config                    = $this->getMvcEvent()->getApplication()->getServiceManager()->get('Config');
        $anneeUniversitaireEnCours = $this->getExportRhService()->getAnneeUniversitaireEnCours();
        $anneeContexte             = $this->getServiceContext()->getAnnee();

        //Si nous ne sommes dans l'année universitaire en cours le module export reste inactif
        if ($anneeContexte->getId() != $anneeUniversitaireEnCours->getId()) {
            return false;
        }

        return true;
    }

}