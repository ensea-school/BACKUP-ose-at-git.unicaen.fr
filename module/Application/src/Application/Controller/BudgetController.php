<?php

namespace Application\Controller;

use Application\Entity\Db\Dotation;
use Application\Entity\Db\Structure;
use Application\Form\Budget\Traits\DotationSaisieFormAwareTrait;
use Application\Service\Traits\AnneeAwareTrait;
use Application\Service\Traits\FormuleResultatAwareTrait;
use Application\Entity\Db\TypeRessource;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DotationServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;
use Zend\Form\Element\Select;


/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class BudgetController extends AbstractController
{
    use StructureAwareTrait;
    use ContextAwareTrait;
    use TypeRessourceServiceAwareTrait;
    use DotationServiceAwareTrait;
    use FormuleResultatAwareTrait;
    use DotationSaisieFormAwareTrait;
    use AnneeAwareTrait;
    use MiseEnPaiementAwareTrait;



    public function indexAction()
    {
        return [];
    }



    public function tableauBordAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
            TypeRessource::class,
            Dotation::class,
        ]);

        $annee = $this->getServiceContext()->getAnnee();

        $tbl = $this->getServiceDotation()->getTableauBord();
        $prv = $this->getServiceFormuleResultat()->getTotalPrevisionnelValide();
        $liq = $this->getServiceMiseEnPaiement()->getTblLiquidation();

        $typesRessources = $this->getServiceTypeRessource()->getList(); /* @var $typesRessources TypeRessource[] */
        $qb = $this->getServiceStructure()->finderByEnseignement();
        $this->getServiceStructure()->finderByNiveau(2, $qb );
        $structures = $this->getServiceStructure()->getList( $qb ); /* @var $structures Structure[] */

        $data = [];
        foreach( $structures as $sid => $structure ){

            $hab = isset($tbl[$sid]['total']) ? $tbl[$sid]['total'] : 0;
            $hli = isset($prv[$sid]) ? $prv[$sid] : 0;

            $data[$sid]['prev'] = $hab - $hli; // Solde abondé - ce qui a été liquidé (dépensé)

            foreach( $typesRessources as $trid => $typeRessource ){

                $hab = isset($tbl[$sid][$trid]) ? $tbl[$sid][$trid] : 0;
                $hli = isset($liq[$sid][$trid]) ? $liq[$sid][$trid] : 0;

                $data[$sid]['dmep'][$trid] = $hab - $hli; // Solde abondé - ce qui a été liquidé (dépensé)
            }
        }

        return compact( 'annee', 'structures', 'typesRessources', 'data' );
    }



    public function engagementAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Structure::class,
            TypeRessource::class,
            Dotation::class,
        ]);

        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');
        /* @var $structure Structure */
        $structureElement = $this->getStructureElement($structure);

        $liquidation = [];
        if ($structure) {
            $dotations          = $this->getServiceDotation()->getDotations($structure);
            $previsionnelValide = $this->getServiceFormuleResultat()->getTotalPrevisionnelValide($structure);
            $ld = $this->getServiceMiseEnPaiement()->getTblLiquidation($structure);
            foreach( $dotations['typesRessources'] as $dtrId => $dtr ){
                $typeRessource = $dtr['entity']; /* @var $typeRessource TypeRessource */

                $dmep = isset($ld[$dtrId]) ? $ld[$dtrId] : 0;
                $liquidation[$dtrId] = [
                    'dmep' => $dmep,
                    'solde' => $dotations['typesRessources'][$dtrId]['total']['heures'] - $dmep,
                ];
            }
        } else {
            $dotations          = [];
            $previsionnelValide = 0;
        }

        return compact(
            'structureElement', 'structure', 'dotations', 'previsionnelValide', 'liquidation'
        );
    }



    public function saisieDotationAction()
    {
        $annee     = $this->getEvent()->getParam('annee');
        $anneePrec = $this->getServiceAnnee()->getPrecedente($annee);
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');
        $libelle   = $this->params()->fromQuery('libelle');
        /* @var $structure Structure */
        $typeRessource = $this->getEvent()->getParam('typeRessource');
        /* @var $typeRessource TypeRessource */

        $title = 'Saisie de dotation ' . $typeRessource . ' / ' . $structure;

        $dotation1 = $this->getServiceDotation()->get($this->params()->fromRoute('dotation1'));
        /* @var $dotation1 Dotation */
        if (!$dotation1) {
            $dotation1 = $this->getServiceDotation()->newEntity()
                ->setAnnee($anneePrec)
                ->setAnneeCivile($annee->getId())
                ->setStructure($structure)
                ->setTypeRessource($typeRessource);
        }

        $dotation2 = $this->getServiceDotation()->get($this->params()->fromRoute('dotation2'));
        /* @var $dotation2 Dotation */
        if (!$dotation2) {
            $dotation2 = $this->getServiceDotation()->newEntity()
                ->setAnnee($annee)
                ->setAnneeCivile($annee->getId())
                ->setStructure($structure)
                ->setTypeRessource($typeRessource);
        }

        if (!$this->isAllowed($dotation1, $typeRessource->getPrivilegeBudgetEdition())){
            $this->flashMessenger()->addErrorMessage('Vous n\'êtes pas autorisé(e) à éditer ces informations');
            $form = null;
        }else{
            $form = $this->getFormBudgetDotationSaisie();
            $form->get('annee1')->setValue($dotation1->getHeures())->setLabel('Dont, au titre de ' . $anneePrec);
            $form->get('annee2')->setValue($dotation2->getHeures())->setLabel('Dont, au titre de ' . $annee);
            $form->get('anneeCivile')->setValue($dotation1->getHeures() + $dotation2->getHeures())->setLabel('Année civile ' . ($annee->getId()));
            $form->get('libelle')->setValue($libelle);

            $form->requestSave($this->getRequest(), function ($data) use ($dotation1, $dotation2) {
                $h1 = (float)str_replace([',', ' '], ['.', ''], $data['annee1']);
                if ($dotation1->getId() && 0 == $h1){
                    $this->getServiceDotation()->delete($dotation1);
                }else{
                    $dotation1->setLibelle($data['libelle']);
                    $dotation1->setHeures($h1);
                    $this->getServiceDotation()->save($dotation1);
                }

                $h2 = (float)str_replace([',', ' '], ['.', ''], $data['annee2']);
                if ($dotation2->getId() && 0 == $h2){
                    $this->getServiceDotation()->delete($dotation2);
                }else{
                    $dotation2->setLibelle($data['libelle']);
                    $dotation2->setHeures($h2);
                    $this->getServiceDotation()->save($dotation2);
                }
            });
        }

        return compact('form', 'title', 'structure', 'typeRessource');
    }



    /**
     * @param $structure
     *
     * @return null|Select
     */
    protected function getStructureElement($structure)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($role->getStructure()) {
            $structureElement = null;
        } else {
            $structureElement = new Select('structure');
            $structureElement->setLabel('Composante');
            if ($structure) {
                $structureElement->setValue($structure->getId());
            } else {
                $structureElement->setEmptyOption('Veuillez sélectionner une composante s\'il vous plaît...');
            }
            $structureElement->setAttributes([
                'onchange' => 'window.location.href="' . $this->url()->fromRoute('budget/engagement') . '/"+this.value',
            ]);

            $serviceStructure = $this->getServiceStructure();
            $qb               = $serviceStructure->finderByEnseignement($serviceStructure->finderByNiveau(2));
            $structureElement->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));
        }

        return $structureElement;
    }

}