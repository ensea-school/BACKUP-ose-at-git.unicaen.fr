# On s'appuie sur le .env
include .env

ifeq ($(APP_ENV),dev)
    PROFILS := --profile dev
else
    PROFILS := --profile prod
endif

ifeq ($(MAIL_ENABLED),true)
    ifeq ($(MAIL_SMTP_HOST),)
        # Si le MAIL_SMTP_HOST n'est pas renseigné, alors on va utiliser un mailer local pour récupérer les mails
        # S'il est renseigné, alors on considère qu'on va utiliser un serveur SMTP externe
        PROFILS += --profile mail
    endif
endif

ALL_PROFILS := --profile dev --profile prod --profile bdd --profile mail



# Commandes
help:
	@echo "Commandes disponibles:"
	@echo ""
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo ""
	@echo "Utilisez 'make <commande>' avec une des commandes ci-dessus."
.PHONY: help



install: ## Build des conteneurs de l'application
	docker compose $(PROFILS) up -d
.PHONY: install



uninstall: ## Désinstallation
	docker compose $(ALL_PROFILS) down --rmi all --volumes --remove-orphans
	docker network rm $(APP_NAME)-network -f
.PHONY: uninstall



start: ## Démarre les conteneurs de l'application
	docker compose $(PROFILS) start
.PHONY: start



stop: ## Stoppe les conteneurs de l'application
	docker compose $(PROFILS) stop
.PHONY: stop



bash: ## Entrer dans le bash du container php
	docker exec -it $(APP_NAME)-php /bin/bash
.PHONY: bash



bash-apache: ## Entrer dans le container Apache
	docker exec -it $(APP_NAME)-apache /bin/sh
.PHONY: bash-apache



bash-node: ## Entrer dans le container Node.js
	docker exec -it $(APP_NAME)-nodejs /bin/sh
.PHONY: bash-node



logs: ## Afficher les logs des containers docker
	docker compose $(PROFILS) logs -f --tail=100
.PHONY: logs



update-bdd: ## Mise à jour de la base de données
	docker exec -it $(APP_NAME)-php /var/www/html/bin/ose-code test6 update-bdd
.PHONY: update-bdd



update-ddl: ## Mise à jour des définitions de la base de données
	docker exec -it $(APP_NAME)-php /var/www/html/bin/ose update-ddl
	docker exec -it $(APP_NAME)-php chown -R $(DEV_HOST_USER_ID):$(DEV_HOST_USER_ID) *
.PHONY: update-bdd



clear-cache: ## Vide le cache de l'application
	docker exec -it $(APP_NAME)-php /var/www/html/bin/ose clear-cache
.PHONY: clear-cache



rebuild: ## Reconstruit tous les conteneurs
	docker compose $(ALL_PROFILS) down -v
	docker compose $(PROFILS) build --no-cache
	docker compose $(PROFILS) up -d
.PHONY: rebuild



clean: ## Vide les caches Docker
	@echo "==========================================================="
	@echo "  /!\ ATTENTION: DESTRUCTIVE Docker cleanup operation /!\  "
	@echo "==========================================================="
	@echo "Cette opération va supprimer tous les objets suivants de votre instance Docker:"
	@echo "  - Tous les containers non utilisés"
	@echo "  - Tous les réseaux non utilisés"
	@echo "  - Toutes les images dockers non utilisées"
	@echo "  - Tous les volumes non utilisés"
	@echo "  - Tous les caches seront vidés"
	@echo ""
	@echo "CETTE ACTION NE POURRA PAS ÊTRE ANNULÉE!"
	@echo ""
	@read -p "Confirmez-vous ce nettoyage complet ? [o/N] " confirm; \
	if [ "$$confirm" = "o" ] || [ "$$confirm" = "O" ]; then \
		docker compose $(PROFILS) down -v; \
		docker system prune -af --volumes; \
		echo "Ménage fait"; \
	else \
		echo "Ménage annulé"; \
	fi
.PHONY: clean