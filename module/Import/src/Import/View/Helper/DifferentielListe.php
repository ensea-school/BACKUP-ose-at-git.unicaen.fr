<?php
namespace Import\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Import\Service\Differentiel;
use Import\Entity\Differentiel\Ligne;
use Import\Exception\Exception;
use Import\View\Helper\DifferentielLigne\DifferentielLigne;

/**
 * Aide de vue permettant d'afficher une liste de données différentielles
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DifferentielListe extends AbstractHelper
{
    /**
     * Lignes de différentiel
     *
     * @var Ligne[]
     */
    protected $lignes;





    /**
     * Helper entry point.
     *
     * @param Ligne[]|Differentiel  $lignes
     * @return self
     */
    final public function __invoke( $lignes )
    {
        $this->setLignes($lignes);
        return $this;
    }

    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render(){
        $aucunEcart = 'Il n\'y a aucun écart entre les sources de données et OSE';
        if (empty($this->lignes)) return $aucunEcart;
        $out = '';
        foreach( $this->lignes as $ligne ){
            $dl = $this->getView()->differentielLigne( $ligne );
            if ($ligne->getAction() != 'update' || $dl->getDetails()){
                $out .= '<tr>'
                            .'<td>'.$dl->getType().'</td>'
                            .'<td>'.$dl->getSujet().'</td>'
                            .'<td>'.ucfirst($dl->getAction()).'</td>'
                            .'<td>'.$dl->getSource().'</td>'
                            .'<td>'.ucfirst(implode( ', ', $dl->getDetails() )).'</td>'
                       .'</tr>'."\n";
            }
        }
        if ($out){
            $out  = '<table class="table">'."\n"
                   .'<tr><th>Type</th><th>Sujet</th><th>Action</th><th>Source</th><th>Détails</th></tr>'
                   .$out
                   .'</table>'."\n";
        }else{
            return $aucunEcart;
        }
        return $out;
    }

    /**
     * Retourne la liste des lignes
     *
     * @return Ligne[]
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    public function addLigne( Ligne $ligne )
    {
        $this->lignes[] = $ligne;
    }

    /**
     *
     *
     * @param Ligne[]|Differentiel $lignes
     * @return DifferentielLigne
     */
    public function setLignes($lignes)
    {
        $this->lignes = [];
        if( $lignes instanceof Differentiel ){
            while( $ligne = $lignes->fetchNext() ){
                $this->addLigne($ligne);
            }
        }elseif(is_array($lignes)){
            foreach( $lignes as $ligne ){
                if (! $ligne instanceof Ligne){
                    throw new Exception('La ligne de différentiel transmise n\'est pas au bon format.');
                }
                $this->addLigne( $ligne );
            }
        }
        return $this;
    }


}