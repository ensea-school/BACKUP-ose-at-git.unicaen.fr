<?php

namespace Signature\Service;


use Application\Entity\Db\Role;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\RoleServiceAwareTrait;
use UnicaenSignature\Entity\Db\SignatureFlow;


/**
 * Description of SignatureFlowService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method SignatureFlow get($id)
 * @method SignatureFlow[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method SignatureFlow newEntity()
 *
 */
class SignatureFlowService extends AbstractEntityService
{
    use RoleServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return SignatureFlow::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'sf';
    }



    public function formatDatasFlow(array $listeSignatureFlow)
    {
        foreach ($listeSignatureFlow as $keyFlow => $flow) {
            if (!empty($flow['steps'])) {
                foreach ($flow['steps'] as $keyStep => $flowStep) {
                    if ($flowStep['method'] == 'by_intervenant') {
                        $listeSignatureFlow[$keyFlow]['steps'][$keyStep]['typeSignataire'] = 'Intervenant';
                    }
                    //Si le signataire est de type role, on va récupérer le role
                    if ($flowStep['method'] == 'by_role') {
                        if (!empty($flowStep['options'])) {
                            foreach ($flowStep['options'] as $option => $value) {
                                if ($option == 'by_role') {
                                    $role = $this->getServiceRole()->get($value);
                                    if ($role instanceof Role) {
                                        $libelleRole = $role->getLibelle();
                                        if (strlen($libelleRole) > 30) {
                                            $libelleRole = substr($libelleRole, 0, 20) . '...';
                                        }
                                        $listeSignatureFlow[$keyFlow]['steps'][$keyStep]['typeSignataire'] = $libelleRole;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $listeSignatureFlow;
    }



    public function getListeCircuit()
    {

    }

}
