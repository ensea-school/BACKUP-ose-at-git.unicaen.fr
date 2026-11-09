#!/usr/bin/env bash
# Bascule une bibliothèque unicaen entre la version installée par composer (vendor)
# et un clone local de développement.
#
# Usage : lib-switch.sh <conteneur php> <dossier des clones locaux> [<lib> [local|remote|toggle]]
#   sans <lib> : affiche l'état de toutes les libs unicaen
#
# Principe :
#   - local  : vendor/unicaen/<lib> est renommé en <lib>.orig et remplacé par un lien
#              symbolique vers le clone local (~/www/unicaen/<lib> par défaut)
#   - remote : le lien est supprimé et <lib>.orig remis en place
# Le dossier des clones doit être monté dans le conteneur au même chemin (cf. docker-compose.yaml).
# vendor appartient à root : les mv/ln sont faits dans le conteneur.

set -euo pipefail

CONTAINER="$1"
LOCAL_DIR="$2"
LIB="${3:-}"
MODE="${4:-toggle}"

PROJECT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
VENDOR_HOST="$PROJECT_DIR/vendor/unicaen"
VENDOR_CT="/var/www/html/vendor/unicaen"

ct() { docker exec "$CONTAINER" sh -c "$1"; }

state() {
    local l="$1"
    if [ -L "$VENDOR_HOST/$l" ]; then
        echo "local   -> $(readlink "$VENDOR_HOST/$l")"
    elif [ -d "$VENDOR_HOST/$l" ]; then
        echo "distant ($(composer_version "$l"))"
    else
        echo "absent"
    fi
}

composer_version() {
    grep -A3 "\"name\": \"unicaen/$1\"" "$PROJECT_DIR/composer.lock" 2>/dev/null \
        | grep -m1 '"version"' | sed 's/.*"version": "\(.*\)".*/\1/' || true
}

# Racine du paquet dans le clone local : le dossier dont le composer.json déclare unicaen/<lib>
# (peut être un sous-dossier, ex. framework/framework)
local_root() {
    local f
    f=$(find "$LOCAL_DIR/$1" -maxdepth 2 -name composer.json -not -path '*/vendor/*' \
            -exec grep -l "\"name\"[[:space:]]*:[[:space:]]*\"unicaen/$1\"" {} + 2>/dev/null | head -1)
    [ -n "$f" ] && dirname "$f"
}

if [ -z "$LIB" ]; then
    for d in "$VENDOR_HOST"/*; do
        l=$(basename "$d")
        case "$l" in *.orig) continue ;; esac
        printf "  %-22s %s\n" "$l" "$(state "$l")"
    done
    exit 0
fi

if [ ! -e "$VENDOR_HOST/$LIB" ] && [ ! -L "$VENDOR_HOST/$LIB" ]; then
    echo "unicaen/$LIB n'est pas installée dans vendor" >&2
    exit 1
fi

if [ "$MODE" = "toggle" ]; then
    if [ -L "$VENDOR_HOST/$LIB" ]; then MODE=remote; else MODE=local; fi
fi

case "$MODE" in
local)
    if [ -L "$VENDOR_HOST/$LIB" ]; then
        echo "unicaen/$LIB est déjà en local : $(readlink "$VENDOR_HOST/$LIB")"
        exit 0
    fi
    ROOT=$(local_root "$LIB") || true
    if [ -z "${ROOT:-}" ]; then
        echo "Aucun clone local de unicaen/$LIB trouvé dans $LOCAL_DIR/$LIB" >&2
        exit 1
    fi
    if ! ct "test -d '$ROOT'"; then
        echo "$ROOT n'est pas visible dans le conteneur $CONTAINER." >&2
        echo "Ajoutez le volume '$LOCAL_DIR:$LOCAL_DIR' au service php de docker-compose.yaml puis 'docker compose up -d'." >&2
        exit 1
    fi
    if [ -e "$VENDOR_HOST/$LIB.orig" ]; then
        echo "$VENDOR_HOST/$LIB.orig existe déjà, abandon (à vérifier à la main)" >&2
        exit 1
    fi
    ct "cd '$VENDOR_CT' && mv '$LIB' '$LIB.orig' && ln -s '$ROOT' '$LIB'"
    echo "unicaen/$LIB : local -> $ROOT"
    ;;
remote)
    if [ ! -L "$VENDOR_HOST/$LIB" ]; then
        echo "unicaen/$LIB est déjà en version distante"
        exit 0
    fi
    if [ -d "$VENDOR_HOST/$LIB.orig" ]; then
        ct "cd '$VENDOR_CT' && rm '$LIB' && mv '$LIB.orig' '$LIB'"
        echo "unicaen/$LIB : distant ($(composer_version "$LIB"))"
    else
        ct "rm '$VENDOR_CT/$LIB'"
        echo "Pas de copie $LIB.orig : lien supprimé, lancez 'composer install' dans le conteneur pour réinstaller unicaen/$LIB" >&2
    fi
    ;;
*)
    echo "Mode inconnu : $MODE (local, remote ou toggle)" >&2
    exit 1
    ;;
esac
