# Problématique des taux de mixité
Dans OSE, au niveau de l’offre de formation, il est possible de spécifier, au niveau de chaque élément pédagogique, 
s'il est éligible :
- à la formation initiale (FI),
- à la formation en apprentissage (FA)
- ou bien à la formation continue (FC).

Dans OSE, FI, FA et FC sont appelés des types d’heures. Il y a aussi le référentiel, mais il est traité à part, 
car il n’est pas adossé à l’offre de formation.

Des témoins permettent d’avoir cette information, à travers les colonnes FI, FA et FC de la table 
[ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md) dont les valeurs, booléennes, peuvent être respectivement de 0 ou 1.

Et il est possible d'avoir un mix des trois, s’exprimant en pourcentage.
Par exemple, un élément peut être 50% FI, 25% FA et 25% FC. 
Les colonnes TAUX_FI, TAUX_FA et TAUX_FC de la table [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md)
doivent contenir des valeurs de type flottantes comprises entre 0 et 1, 
avec en contrainte  TAUX_FI +  TAUX_FA +  TAUX_FC = 1. 50% FI se traduit par TAUX_FI = 0,5. Ce sont les taux de mixité.

L’autre contrainte est que si le témoin FI est à 0, alors TAUX_FI doit aussi être à 0. 
Idem pour les deux autres types d’heures.
En résumé, pour la table ELEMENT_PEDAGOGIQUE les contraintes sont les suivantes :
- FI = {0;1}
- FA = {0;1}
- FC = {0;1}
- 1 <= FI + FA + FC <= 3.
- TAUX_FI = \[0 .. 1,0\] et 0 si FI = 0
- TAUX_FA = \[0 .. 1,0\] et 0 si FA = 0
- TAUX_FC = \[0 .. 1,0\] et 0 si FC = 0
- (TAUX_FI + TAUX_FA + TAUC_FC) = 1

# Pour renseigner ces données : principe général

Si vous utilisez le [connecteur Apogée](Apogée/Connecteur.md), les témoins FI, FA et FC sont peuplés à partir du connecteur.
Les taux de mixité peuvent être saisis directement dans OSE, dans la partie Offre de formation, 
puis en cliquant sur une formation et enfin «Taux de mixité». 
Là, une page vous permettra de saisir ces taux pour tout ou partie des éléments de cette formation.

Compte tenu du nombre de données à saisir, il est plus simple de mettre en place un traitement automatique pour renseigner ces informations, 
la saisie manuelle pouvant être réservée aux cas particuliers.

A Caen, nous initialisons ces taux de mixité en nous basant sur les effectifs de l’année passée.

# Architecture

En base de données, les effectifs par élément pédagogique sont stockés dans la table [EFFECTIFS](Création-tables/EFFECTIFS.md), 
avec en colonnes FI, FA et FC le nombre d’étudiants respectifs.

Les taux de mixité sont stockés dans la table [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md).
Les informations stockées dans [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) sont ensuite transférées 
dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md).
Ces sont les taux stockéss dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md) qui sont utiles pour l’exploitation.

[ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) ne sert que d’intermédiaire. 
Cette table est néanmoins nécessaire, car on peut avoir des taux de mixité saisis manuellement pour un élément pédagogique issu du connecteur Apogée. 
Si tout n’était stocké que dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md), nous ne pourrions pas faire cela.

# En pratique

## Les vues

Les exemples ci-dessous sont tirés du [connecteur Apogée](Apogée/Connecteur.md).

La table [EFFECTIFS](Création-tables/EFFECTIFS.md) est peuplée au moyen de la vue source 
[SRC_EFFECTIFS](Apogée/SRC_EFFECTIFS.sql).

Il va falloir peupler la table [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md). 
La vue [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql) va fournir les données nécessaires pour cela.

Ensuite, il faut spécifier, dans la vue [SRC_EFFECTIFS](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql), qu’il faut recourir à
[ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) si la donnée existe. 
Si au contraire aucune répartition n’est spécifiée, alors un taux de mixité est calculé automatiquement à partie des témoins FI, FA et FC avec:
- l’un des trois types d’heures ==> 100% sur le taux correspondant;
- deux des trois témoins ==> 50% chacun;
- les trois témoins, 33,33% FA et FC, 33,34% pour la FI, histoire de faire strictement 100%.

## Le calcul des taux de mixité

Il existe trois méthodes qui permettent de calculer les taux de mixité en fonction de nombres et des témoins
- OSE_DIVERS.CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);
- OSE_DIVERS.CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);
- OSE_DIVERS.CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);

eff_fi, eff_fc et eff_fa sont des nombres flottants. Ils seront convertis en pourcentages. C'est-à-dire que vous pouvez saisir \[250, 0, 49\].
fi, fa et fc correspondent aux témoins. Vous devrez transmettre 0 ou 1. CALCUL_TAUX_FI retournera le taux de mixité correspondant pour la FI.
Idem pour CALCUL_TAUX_FC avec la FC et CALCUL_TAUX_FA pour la FA.

## Explications sur le contenu des vues

### [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql)

Ligne 5 :
`to_number(e.annee_id) + 1   annee_id,`

`+ 1` signifie qu'on prend les effectifs d'une année pour en faire les taux de mixité de l'année suivante.
A vous d'adapter cela à vos besoins si vous souhaitez un autre mode de calcul.

Pour le reste, dans cette vue on se base directement sur `ose_element_effectifs@apoprod`.

En fin de vue, nous avons :
```sql
JOIN ELEMENT_PEDAGOGIQUE ep ON ep.source_code = aq.z_element_pedagogique_id AND ep.annee_id = aq.annee_id
WHERE
NOT EXISTS( -- on évite de remonter des données issus d'autres sources pour le pas risquer de les écraser!!
SELECT * FROM element_taux_regimes aq_tbl WHERE
aq_tbl.element_pedagogique_id = ep.id
AND aq_tbl.source_id <> s.id
)
```

Si des taux de mixité existent déjà et qu'ils ne proviennent pas de la même source, alors il ne faut pas les écraser.
Sans ce filtre, vos saisies manuellement dans OSE pourraient être remplacées par les données issues de votre connecteur.

### [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql)

Ligne 9 : `CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi`.
Si aucun témoins n'est activé, alors on force l'élément en FI pour respecter la règle 1 <= FI+FA+FC <= 3.

Au niveau des jointures :
```sql
  LEFT JOIN element_pedagogique         ep ON ep.source_code             = aq.source_code
                                          AND ep.annee_id                = aq.annee_id
  LEFT JOIN element_taux_regimes       etr ON etr.element_pedagogique_id = ep.id
                                          AND etr.histo_destruction      IS NULL
```

On va chercher les taux de répartition s'ils existent.

Et au niveau du SELECT on a :

```sql
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fi( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fi,
```
Idem pour FA et FC.

Cela signifie : si on a un taux de mixité, alors on s'appuie dessus, sinon on calcule des taux de mixité à partir des témoins (100%, 50/50 ou 33/33/33).

## Conclusion

A Caen, nous nous basons sur les effectifs de l'année passée pour initialiser nos taux de répartition.
Une fois initialisés, ils ne sont pas synchronisés régulièrement et automatiquemenet comme le reste des données issues des connecteurs.
Concrètement, la table ELEMENT_TAUX_REGIMES ne fait pas partie du job de synchronisation comme les autres. Sa synhronisation est traitée manuellement,
en passant une ou deux fois par an par le différentiel d'import 
(Administration/Synchronisation/Écarts entre les données de l'application et ses sources), puis en cliquant sur le bouton "Synchroniser".

Les taux de mixité sont aussi personnalisés manuellement en passant par "Offre de formation", au besoin.

Ces taux ne doivent idéalement pas bouger toute l'année, car cela impacte les demandes de mise en paiement. Mieux vaut donc les figer le plus tôt possible.

Les effectifs et les éléments pédagogiques sont, eux, synchronisés régulièrement comme le reste des données en import automatique.

Si vous ne souhaitez pas fonctionner de la même manière, alors il vous faudra adapter les vues sources présentées ci-dessus.
Attention toutefois à bien peupler les taux de mixité des éléments pédagogiques à partir des taux de régime.
Le mieux est de ne pas toucher à [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql) et de vous concentrer à fournir 
les bonnes données dans [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql).

Attention ausi à bien respecter les règles décrites dans cette page!