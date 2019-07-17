update parametre SET valeur = to_char((SELECT id FROM etat_sortie WHERE code='export_services')) WHERE nom = 'es_services_pdf';
/--