# Activer et synchroniser une table

## Activer une table

Par défaut, les tables sont désactivées. Vous devrez activer la synchronisation pour que celle-ci puisse se faire.

Allez dans OSE, menu Administration / Synchronisation, Page Tables.

![Tables](tables.png)

Le bouton "Mise à jour des vues et procédures d'import" vous permet de recréer les vues différentielles et les
procédures de mise à jour.
Utile si vous avez à modifier vos vues sources.
Cette opération est faite implicitement si vous activez/désactivez la synchronisation pour une table.

La commande `./bin/ose build-synchronisation` met à jour de la même manière vos vues et procédures d'import.

## Synchroniser une table

### Dans l'application

Allez dans OSE, menu Administration / Synchronisation, Page Différentiel.

Vous verrez ici toutes les tables dont la synchronisation a été activée.

![Synchro](synchro.png)

Vous pouvez :

- Constater le différentiel s'il y en a un. Dans l'exemple ci-dessus, tous lesp ays sont à importer.
- Réaliser les opérations une par une (petits boutons à droite de nouveau, mise à jour, etc.).
- Ou bien tout synchroniser d'un coup (bouton Synchroniser).

En cas d'erreur, l'application vous affichera les enregistrements qui ont posé problème.

### Directement en base de données

#### Afficher le différentiel

Il existe une vue différentielle par table synchronisée.
Ces vues sont nommées V_DIFF_*nom-de-la-table*

Par exemple,

```sql
SELECT *
FROM V_DIFF_PAYS
```

va vous afficher le différentiel existant entre la vue source et la table PAYS.
Attention : ce différentiel ne prend en compte que les données synchronisables. Si vous ajoutez un pays à la main dans
la base de données avec OSE comme source, celui-ci n'apparaitra pas dans cette vue.

Dans cette vue, vous retrouverez les colonnes suivantes :

- ID qui vous renseigne sur l'ID dans la table destination si l'enregistrement existe déjà.
- IMPORT_ACTION qui vous donne l'action à effectuer (insert, update, delete, undelete).
- La liste des colonnes synchronisables de la table avec comme valeurs celles de la vue.
- La même liste des colonnes synchronisables de la table avec "U_" comme préfixe (par exemple U_CODE pour la colonne
  CODE) et en valeur 1 s'il y a une différence entre la vue source et la table et 0 si la donnée ne change pas.

#### Opérations de synchronisation

Pour effectuer toutes les opérations de synchronisation :

```sql
BEGIN
  unicaen_import.synchronisation
('PAYS');
END;
```

Pour n'effectuer que les insertions de nouvelles données :

```sql
BEGIN
  unicaen_import.synchronisation
('PAYS', 'WHERE import_action=''insert''');
END;
```

Pour ne synchroniser qu'un sous-ensemble de données (ici uniquement le pays FRANCE dont le code est 100)

```sql
BEGIN
  unicaen_import.synchronisation
('PAYS', 'WHERE code=''100''');
END;
```

### Forcer la synchronisation d'un element pédagogique ou d'une étape

Cette procédure permet de forcer manuellement la synchronisation d’une étape ou d’un élément pédagogique lorsque la
synchronisation automatique est désactivée.

#### Prérequis

Avant de forcer la synchronisation d’un élément pédagogique, il est obligatoire que l’étape associée existe déjà en base
de données.

Si l’étape n’existe pas, il faut d’abord forcer la synchronisation de l’étape, puis seulement ensuite celle de l’élément
pédagogique.

---

#### 1. Vérifier si l’étape est présente dans le connecteur

```sql
SELECT *
FROM src_etape
WHERE code LIKE '%PROANGMOBHEH1%';
```

---

#### 2. Forcer manuellement la synchronisation d’une étape

```sql
BEGIN
    unicaen_import.synchronisation
(
        'ETAPE',
        'WHERE code =''FCA-PROANGMOBHEH2-2025''
           AND import_action <> ''delete''
           AND annee_id = 2025'
    );
END;
/
```

---

#### 3. Vérifier si l’élément pédagogique est présent dans le connecteur

```sql
SELECT *
FROM src_element_pedagogique
WHERE code LIKE 'FCA-PROANGMOBHEH2-2025%';
```

---

#### 4. Forcer manuellement la synchronisation d’un élément pédagogique

```sql
BEGIN
    unicaen_import.synchronisation
(
        'ELEMENT_PEDAGOGIQUE',
        'WHERE code =''FCA-PROANGMOBHEH1-2025''
           AND import_action <> ''delete''
           AND annee_id = 2025'
    );
END;
/
```

#### 5. Forcer manuellement la synchronisation de tous les éléments pédagogiques d'une étape

```sql
BEGIN
    FOR r IN (
        SELECT code
        FROM src_element_pedagogique
        WHERE code LIKE 'M1PRO5%'
    )
    LOOP
        unicaen_import.synchronisation(
            'ELEMENT_PEDAGOGIQUE',
            'WHERE code = ''' || r.code || '''
               AND import_action <> ''delete''
               AND annee_id = 2025'
        );
    END LOOP;
END;
/
```

---

#### Points d’attention

- L’étape doit impérativement exister avant de synchroniser un élément pédagogique.
- Vérifier que la donnée est bien présente dans les tables sources du connecteur avant de lancer la synchronisation.
- Adapter le `code` et l’`annee_id` en fonction de l’année universitaire et de l’objet à synchroniser.
- La condition `import_action <> 'delete'` permet d’éviter de synchroniser une donnée marquée comme supprimée.

#### Log

En cas de soucis, vous avez à votre disposition la table SYNC_LOG recense toutes les erreurs qui ont pu se produire lors
d'une synchronisation.

```sql
SELECT *
FROM SYNC_LOG
```

N'hésitez pas à purger ce log de temps en temps :

```sql
TRUNCATE TABLE SYNC_LOG
```
