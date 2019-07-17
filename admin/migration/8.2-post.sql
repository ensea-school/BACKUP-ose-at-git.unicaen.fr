create unique index USER_PASSWORD_RESET_TOKEN_UN on utilisateur (PASSWORD_RESET_TOKEN);
/--
update parametre SET valeur = to_char((SELECT id FROM etat_sortie WHERE code='export_services')) WHERE nom = 'es_services_pdf';
/--