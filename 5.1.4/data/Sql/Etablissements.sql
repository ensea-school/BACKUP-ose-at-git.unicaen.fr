INSERT INTO ETABLISSEMENT(
    LIBELLE,
--    LOCALISATION,
--    DEPARTEMENT,
    SOURCE_CODE,
    ID, SOURCE_ID, HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
    'Formations continues inter-entreprises',
--    '',
--    '',
    'fc-inter-entreprises',
    ETABLISSEMENT_id_seq.nextval, OSE_IMPORT.GET_SOURCE_ID('OSE'), 1, 1
);
