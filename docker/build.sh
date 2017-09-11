#!/bin/sh

set -ex

# You should be on origin/release
VERSION=$(git describe --tags --exact-match)

if [ ! -f 'docker/config.env' ]; then
    echo "'docker/config.env' environment file is missing. Please create it, see docker/config.env.dist for example"
    exit 66 # EX_NOINPUT
else
    sed -Ei "s/VERSION=(.)*/VERSION=${VERSION}/g" config.env
fi

cp app/config/parameters.yml.dist app/config/parameters.yml
docker run --rm \
    --user $(id -u) \
    --volume /etc/passwd:/etc/passwd:ro \
    --volume /etc/group:/etc/group:ro \
    --volume ${HOME}/.composer/.config/composer:/composer:rw \
    --volume ${HOME}/.ssh:$HOME/.ssh:ro \
    --volume "${PWD}:/app" \
    --workdir /app \
    --env-file ./docker/config.env \
    --env SYMFONY_ENV=prod \
    --label "traefik.enable=false" \
    composer:latest \
    composer install --ignore-platform-reqs --no-interaction --prefer-dist --no-scripts --no-dev

rm -rf var/cache/* var/logs/* var/sessions/*

docker build --rm -t ${DOCKER_REGISTRY_HOST}/hermod_php:${VERSION} -f docker/php/Dockerfile .
docker build --rm -t ${DOCKER_REGISTRY_HOST}/hermod_nginx:${VERSION} -f docker/nginx/Dockerfile .
