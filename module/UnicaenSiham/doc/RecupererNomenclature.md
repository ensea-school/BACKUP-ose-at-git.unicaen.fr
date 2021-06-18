# Récupérer les nomenclatures SIHAM

## Introduction

Cette méthode vous permet de récupére les nomenclatures utilisées par SIHAM. Les nomenclatures suivantes sont accessibles :

| Type de coordonnées               | 
|-----------------------------------|
| Les grades      |
| Les corps          |
| Les section CNU, BAP et REFERENS  |
| Les spécialités      |
| Les familles professionnelles               |
| Les qualités statutaire        |
| Les catégories       |
| Les types de contrat       |
| Les types de statut       |
| Les types de modalité de service       |
| Les types de position       |
| Les types d'échelon       |
| Les modes d'accés de grades       |
| Les codes administration       |
| Les codes établissement       |

## Méthode et Paramétres

Nom de la méthode : recupererNomenclatureRH($params)

Paramètre(s) :

$params : Array contenant les clés d'entrées suivantes

| Nom de la clé          | Type    | Optionnel | Desc
|------------------------|---------|-----------|-----------------------------------------------------------------------------
| listeNomenclatures     | Array   | NON       |tableau contenant les codes des nomenclatures souhaitées
| dateObservation        | Date    | OUI       |Date permettant de remonter les nomenclatures dont la date de début est supérieure à la date d'observation et la date de fin est null
| codeAdministration     | String  | OUI       |Permet de remonter uniquement les valeurs des nomenclatures appartenant à une administration donnée

Retour :

Retourn un Array avec en clé le code administration et en valeur le libellé long, classé par ordre Alphabétique ASC.

## Exemple d'implémentation

```php
// Récupérer la nomenclature des codes administration de l'année en cours
$siham = new \UnicaenSiham\Service\Siham();
$result = $siham->recupererNomenclatureRH([
                    'listeNomenclatures' => ['UAA'],
                    'codeAdministration' => 'UCN',
                    'dateObservation'    => '2021-01-01'
                    ]);


/**
 array (size=3)
  'AUT' => string 'Autre administration' (length=20)
  'REL' => string 'Base de proches' (length=15)
  'UCN' => string 'Université de Caen Normandie' (length=29)
 **/                    
                    
 ```