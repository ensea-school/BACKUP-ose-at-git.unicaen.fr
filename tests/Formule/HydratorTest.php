<?php declare(strict_types=1);

namespace tests\Formule;

use Doctrine\ORM\EntityManager;
use Formule\Entity\FormuleIntervenant;
use Formule\Hydrator\FormuleIntervenantHydrator;
use tests\OseTestCase;

final class HydratorTest extends OseTestCase
{

    public function testLigne()
    {
        /** @var EntityManager $em */
        $em = \OseAdmin::instance()->container()->get(EntityManager::class);

        $oriData = [
            'id'                         => 243273,
            'annee'                      => 2023,
            'typeVolumeHoraire'          => 2,
            'etatVolumeHoraire'          => 1,
            'typeIntervenant'            => 1,
            'structureCode'              => 'E01',
            'heuresServiceStatutaire'    => 192.0,
            'heuresServiceModifie'       => 0.0,
            'depassementServiceDuSansHC' => false,
            'param1'                     => NULL,
            'param2'                     => NULL,
            'param3'                     => NULL,
            'param4'                     => '140582',
            'param5'                     => '784111',
            'volumesHoraires'            => [
                0 => [
                    'id'                          => 216383,
                    'structureAffectation'        => true,
                    'volumeHoraire'               => 1,
                    'volumeHoraireReferentiel'    => NULL,
                    'service'                     => 1,
                    'serviceReferentiel'          => NULL,
                    'structureCode'               => 'E01',
                    'typeInterventionCode'        => 'CM',
                    'structureUniv'               => false,
                    'structureExterieur'          => false,
                    'serviceStatutaire'           => true,
                    'nonPayable'                  => false,
                    'tauxFi'                      => 0.9756,
                    'tauxFa'                      => 0.0,
                    'tauxFc'                      => 0.0244,
                    'tauxServiceDu'               => 1.5,
                    'tauxServiceCompl'            => 1.5,
                    'ponderationServiceDu'        => 1.0,
                    'ponderationServiceCompl'     => 1.0,
                    'heures'                      => 5.0,
                    'param1'                      => NULL,
                    'param2'                      => NULL,
                    'param3'                      => NULL,
                    'param4'                      => '211289',
                    'param5'                      => NULL,
                    'heuresServiceFi'             => 4.53181935483871,
                    'heuresServiceFa'             => 0.0,
                    'heuresServiceFc'             => 0.11334193548387096,
                    'heuresServiceReferentiel'    => 0.0,
                    'heuresNonPayableFi'          => 0.0,
                    'heuresNonPayableFa'          => 0.0,
                    'heuresNonPayableFc'          => 0.0,
                    'heuresNonPayableReferentiel' => 0.0,
                    'heuresComplFi'               => 2.78518064516129,
                    'heuresComplFa'               => 0.0,
                    'heuresComplFc'               => 0.06965806451612903,
                    'heuresPrimes'                => 0.0,
                    'heuresComplReferentiel'      => 0.0,
                ],
                1 => [
                    'id'                          => 216384,
                    'structureAffectation'        => true,
                    'volumeHoraire'               => 1,
                    'volumeHoraireReferentiel'    => NULL,
                    'service'                     => 1,
                    'serviceReferentiel'          => NULL,
                    'structureCode'               => 'E01',
                    'typeInterventionCode'        => 'TD',
                    'structureUniv'               => false,
                    'structureExterieur'          => false,
                    'serviceStatutaire'           => true,
                    'nonPayable'                  => false,
                    'tauxFi'                      => 0.9756,
                    'tauxFa'                      => 0.0,
                    'tauxFc'                      => 0.0244,
                    'tauxServiceDu'               => 1.0,
                    'tauxServiceCompl'            => 1.0,
                    'ponderationServiceDu'        => 1.0,
                    'ponderationServiceCompl'     => 1.0,
                    'heures'                      => 14.0,
                    'param1'                      => NULL,
                    'param2'                      => NULL,
                    'param3'                      => NULL,
                    'param4'                      => '211290',
                    'param5'                      => NULL,
                    'heuresServiceFi'             => 8.459396129032259,
                    'heuresServiceFa'             => 0.0,
                    'heuresServiceFc'             => 0.21157161290322582,
                    'heuresServiceReferentiel'    => 0.0,
                    'heuresNonPayableFi'          => 0.0,
                    'heuresNonPayableFa'          => 0.0,
                    'heuresNonPayableFc'          => 0.0,
                    'heuresNonPayableReferentiel' => 0.0,
                    'heuresComplFi'               => 5.199003870967742,
                    'heuresComplFa'               => 0.0,
                    'heuresComplFc'               => 0.1300283870967742,
                    'heuresPrimes'                => 0.0,
                    'heuresComplReferentiel'      => 0.0,
                ],
                2 => [
                    'id'                          => 216385,
                    'structureAffectation'        => true,
                    'volumeHoraire'               => 1,
                    'volumeHoraireReferentiel'    => NULL,
                    'service'                     => 1,
                    'serviceReferentiel'          => NULL,
                    'structureCode'               => 'E01',
                    'typeInterventionCode'        => 'CM',
                    'structureUniv'               => false,
                    'structureExterieur'          => false,
                    'serviceStatutaire'           => true,
                    'nonPayable'                  => false,
                    'tauxFi'                      => 0.9756,
                    'tauxFa'                      => 0.0,
                    'tauxFc'                      => 0.0244,
                    'tauxServiceDu'               => 1.5,
                    'tauxServiceCompl'            => 1.5,
                    'ponderationServiceDu'        => 1.0,
                    'ponderationServiceCompl'     => 1.0,
                    'heures'                      => 2.0,
                    'param1'                      => NULL,
                    'param2'                      => NULL,
                    'param3'                      => NULL,
                    'param4'                      => '211291',
                    'param5'                      => NULL,
                    'heuresServiceFi'             => 1.812727741935484,
                    'heuresServiceFa'             => 0.0,
                    'heuresServiceFc'             => 0.04533677419354839,
                    'heuresServiceReferentiel'    => 0.0,
                    'heuresNonPayableFi'          => 0.0,
                    'heuresNonPayableFa'          => 0.0,
                    'heuresNonPayableFc'          => 0.0,
                    'heuresNonPayableReferentiel' => 0.0,
                    'heuresComplFi'               => 1.1140722580645162,
                    'heuresComplFa'               => 0.0,
                    'heuresComplFc'               => 0.027863225806451612,
                    'heuresPrimes'                => 0.0,
                    'heuresComplReferentiel'      => 0.0,
                ],
                3 => [
                    'id'                          => 216386,
                    'structureAffectation'        => true,
                    'volumeHoraire'               => 1,
                    'volumeHoraireReferentiel'    => NULL,
                    'service'                     => 1,
                    'serviceReferentiel'          => NULL,
                    'structureCode'               => 'E01',
                    'typeInterventionCode'        => 'TD',
                    'structureUniv'               => false,
                    'structureExterieur'          => false,
                    'serviceStatutaire'           => true,
                    'nonPayable'                  => false,
                    'tauxFi'                      => 0.9756,
                    'tauxFa'                      => 0.0,
                    'tauxFc'                      => 0.0244,
                    'tauxServiceDu'               => 1.0,
                    'tauxServiceCompl'            => 1.0,
                    'ponderationServiceDu'        => 1.0,
                    'ponderationServiceCompl'     => 1.0,
                    'heures'                      => 8.0,
                    'param1'                      => NULL,
                    'param2'                      => NULL,
                    'param3'                      => NULL,
                    'param4'                      => '211292',
                    'param5'                      => NULL,
                    'heuresServiceFi'             => 4.833940645161291,
                    'heuresServiceFa'             => 0.0,
                    'heuresServiceFc'             => 0.12089806451612903,
                    'heuresServiceReferentiel'    => 0.0,
                    'heuresNonPayableFi'          => 0.0,
                    'heuresNonPayableFa'          => 0.0,
                    'heuresNonPayableFc'          => 0.0,
                    'heuresNonPayableReferentiel' => 0.0,
                    'heuresComplFi'               => 2.97085935483871,
                    'heuresComplFa'               => 0.0,
                    'heuresComplFc'               => 0.07430193548387097,
                    'heuresPrimes'                => 0.0,
                    'heuresComplReferentiel'      => 0.0,
                ],
            ],
        ];

        $fi = new FormuleIntervenant();

        $hydrator = new FormuleIntervenantHydrator($em);

        $hydrator->hydrate($oriData, $fi);

        $resData = $hydrator->extract($fi);

        $this->assertArrayEquals($oriData, $resData);
    }


}
