#!/bin/sh
set -e

echo "$1"
echo "🚀 Entrypoint lancé"
if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ]; then

	if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
	  echo "Début composer install"
	  composer install --prefer-dist --no-progress --no-interaction
	  echo "Fin composer install" >&2
	fi
fi

exec docker-php-entrypoint "$@"
