<?php

namespace OffreFormation\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Service\Traits\SourceServiceAwareTrait;
use OffreFormation\Entity\Db\Discipline;
use OffreFormation\Form\Traits\DisciplineFormAwareTrait;
use OffreFormation\Service\Traits\DisciplineServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;


/**
 * Description of DisciplineController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineController extends AbstractController
{
    use DisciplineServiceAwareTrait;
    use SourceServiceAwareTrait;
    use DisciplineFormAwareTrait;
    use ParametresServiceAwareTrait;


    public function indexAction()
    {
        // Les données sont chargées par le composant Vue via indexJsonAction
        return [];
    }

    public function indexJsonAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Discipline::class,
        ]);

        $qb = $this->getServiceDiscipline()->initQuery()[0];

        $this->getServiceDiscipline()->join(
            $this->getServiceSource(),
            $qb,
            'source',
            true
        );

        $disciplines = $this->getServiceDiscipline()->getList($qb);
        $disciplinesListe = [];
        $result = [];

        //Libelle colonnes
        $libellesCodesCorresp = [];
        for ($i = 1; $i <= 4; $i++) {
            $lcc = $this->getServiceParametres()->get('discipline_codes_corresp_' . $i . '_libelle');
            if ($lcc) {
                $libellesCodesCorresp[$i] = $lcc;
            }
        }


        //Données tableau disciplines
        foreach ($disciplines as $discipline) {
            $disciplinesListe[] = [
                'id' => $discipline->getId(),
                'sourceCode' => $discipline->getSourceCode(),
                'libelleCourt' => $discipline->getLibelleCourt(),
                'libelleLong' => $discipline->getLibelleLong(),
                'codesCorresp1' => $discipline->getCodesCorresp1(),
                'codesCorresp2' => $discipline->getCodesCorresp2(),
                'codesCorresp3' => $discipline->getCodesCorresp3(),
                'codesCorresp4' => $discipline->getCodesCorresp4(),
                'source' => (string)$discipline->getSource(),
                'sourceImportable' => $discipline->getSource()->getImportable(),
            ];
        }


        $result[] = [
            $libellesCodesCorresp,
            $disciplinesListe];

        return new AxiosModel($result);
    }


    public function voirAction()
    {
        return [];
    }


    public function modifierJsonAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Méthode HTTP non autorisée.'],
            ]);
        }

        $id = $request->getPost('id');

        try {
            if ($id) {
                // Modification
                $discipline = $this->getServiceDiscipline()->get($id);

                if (!$discipline) {
                    return new AxiosModel([
                        'success' => false,
                        'errors' => ['Discipline introuvable.'],
                    ]);
                }
            } else {
                // Création
                $discipline = $this->getServiceDiscipline()->newEntity();
            }

            $form = $this->getFormDiscipline();

            $form->bind($discipline);
            $form->setData($request->getPost());

            if (!$form->isValid()) {
                return new AxiosModel([
                    'success' => false,
                    'errors' => $form->getMessages(),
                ]);
            }

            $this->getServiceDiscipline()->save($discipline);

            return new AxiosModel([
                'success' => true,
                'id' => $discipline->getId(),
            ]);
        } catch (\Exception $e) {
            return new AxiosModel([
                'success' => false,
                'errors' => [$this->translate($e)],
            ]);
        }
    }

    public function supprimerJsonAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Méthode HTTP non autorisée.'],
            ]);
        }

        $id = $request->getPost('id');

        if (!$id) {
            return new AxiosModel([
                'success' => false,
                'errors' => ['Identifiant de discipline manquant.'],
            ]);
        }

        try {
            $discipline = $this->getServiceDiscipline()->get($id);

            if (!$discipline) {
                return new AxiosModel([
                    'success' => false,
                    'errors' => ['Discipline introuvable.'],
                ]);
            }

            $this->getServiceDiscipline()->delete($discipline);

            return new AxiosModel([
                'success' => true,
            ]);


        } catch (\Exception $e) {
            return new AxiosModel([
                'success' => false,
                'errors' => [$this->translate($e)],
            ]);
        }
    }
}