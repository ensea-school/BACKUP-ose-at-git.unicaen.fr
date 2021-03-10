# Connecteur Mangue

Ce connecteur Mangue correspond à la vue matérialisée MV_INTERVENANT écrite pour Harpège et adaptée pour obtenir les mêmes colonnes en interrogeant les données de Grhum et Mangue (Cocktail) :
Elle contient en l'état des notes sur les "Usages" de l'Université du HAVRE et on y trouve aussi des appels à des vues/fonction/Table créés historiquement ou pour les besoins du connecteur.
Il faudra donc les adapter aussi selon vos convenances.

## Mise en place du DbLink

Le lien avec Grhum et Mangue se fait au moyen d'un DbLink.
Dans la vue, le DbLink s'appelle `DBL_GRHUM` et utilise le USER `GRHUM`.

## Création de la vue matérialisée MV_INTERVENANT

Voici la vue matérialisée qui remonte les données de Grhum et Mangue :
[MV_INTERVENANT](MV_INTERVENANT.sql)

Les objets personnalisés sont les suivants :

**Sous l'utilisateur GRHUM** :
* Vues :

- [ ] [ULH_V_STRUCT_AFF_TOUS](ULH_V_STRUCT_AFF_TOUS.sql)
- [ ] [ULH_V_ADR_CONN_OSE](ULH_V_ADR_CONN_OSE.sql)   (Champ adresse_precisions utilisé uniquement )
- [ ] [V_ULH_INDIVIDU_BANQUE](V_ULH_INDIVIDU_BANQUE.sql)

* Fonction pour la récupération du grade en cours :

- [ ] [ULH_IND_GRADE_EN_COURS](ULH_IND_GRADE_EN_COURS.sql)

* Table :
[ULH_LDAP](Pas_de_sql.sql)

| Colonne       | Type      | Longueur  | Nullable  | Commentaire                   |
| -------       | -----     | --------  | --------  | ------------                  |
|NO_INDIVIDU    | NUMBER    | 8         |Non        | supannEmpId                   |
|LOGIN          |VARCHAR2   |50         |Non        | login LDAP                    |
|MAIL           |VARCHAR2   |125        |Non        |adresse mail définie dans LDAP |
|LEOCODE        |VARCHAR2   |13         |Oui        |                               |
|NO_TELEPHONE   |VARCHAR2   |20         |Oui        |                               |


**Sous l'utilisateur MANGUE :**
* Vue :
- [ ] [ULHN_V_DERNIER_CONTRAT](ULHN_V_DERNIER_CONTRAT.sql)

## Remarque sur la vue matérialisée MVT_INTERVENANT et la vue source SRC_INTERVENANT
Les données en sortie de MV_INTERVENANT sont préparées pour être exploitées par la vue source [SRC_INTERVENANT](../Générique/SRC_INTERVENANT.sql) qu'il est fortement conseillé de ne pas modifier.
Cependant pour l'ULHN nous avons modifié la ligne 260 concernant la jointure sur la structure qui chez nous se fait sur le libellé court et non le source_code.
```sql
LEFT JOIN structure             str ON str.libelle_court  = s.z_structure_id
remplace
LEFT JOIN structure             str ON str.source_code    = s.z_structure_id
```

