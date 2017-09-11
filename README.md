# Hermod
Crowd sourcing API for Navitia

## Documentation

[Swagger](http://petstore.swagger.io/?url=https://raw.githubusercontent.com/CanalTP/Hermod/master/swagger-spec.yaml)

## Docker

## For development env

### Build & Run Hermod api

If this the first time you are building this API:
start the docker container used to generate local DNS entries:
```
docker pull zibok/docker-gen-hosts && docker run -d --restart=always -v /etc/hosts:/generated_hostfile -v /var/run/docker.sock:/var/run/docker.sock --name docker-gen-hosts zibok/docker-gen-hosts
```

*This docker image is responsible of updating your /etc/hosts file to create the hermod.local -> docker-container-ip-address resolution*

Build the Hermod docker image and install dependencies:
```
./docker/build_dev.sh
```

Start Hermod:
```
docker-compose up -d
```

Go to `http://hermod.local/v1/status`, the api should be up and running !

When you are done:
```
docker-compose down
```

## For production env (with traefik)

In this example, we suppose `hermod.localhost` should be your host
Configuration of Docker: Swarm mode (If not you need to do this `docker swarm init` in your shell)

### Configurations

Run only this command with `root` user
```
echo -e "127.0.0.1\thermod.localhost" >> /etc/hosts
```

Create `docker/config.env` file (see [docker/config.env.dist](docker/config.env.dist) for example) for symfony environment

### Traefik

Create network for treafik and run it (Be careful, port: 80 should be free)

```
docker network create traefik-net --driver overlay --attachable
```

```
docker run --rm -d --name traefik --network traefik-net --publish 80:80 --volume /var/run/docker.sock:/var/run/docker.sock containous/traefik:v1.3.0-rc2 --docker --docker.swarmmode --docker.watch --docker.domain=localhost
```

### Build & Run Hermod api

This command will build `hermod_php:master` and `hermod_nginx:master` images and run `composer install`
```
DOCKER_REGISTRY_HOST=YOUR_DOCKER_REGISTRY_HOST:5000 ./docker/build.sh
```

Now you can create hermod stack
```
DOCKER_REGISTRY_HOST=YOUR_DOCKER_REGISTRY_HOST:5000 VERSION=0.1.0 HOST=hermod.localhost docker stack deploy -c docker/docker-compose.prod.yml hermod
```

Follow [Authentification](#authentification) instruction and go to `http://hermod.localhost/v1/status`, the api should works

## Authentication

You need to be authenticated to use this API. We are using a token authentication.

You can add a new user by using the following command:

```
bin/console hermod:user:create <username>
```

This command will give you a token with roles, and on every request, you will need to provide this token in the `Authorization` header.

If you want to edit user roles you can use this command

```
bin/console hermod:user:role <username>
```


## Stop application

When you finished
```
docker stack rm hermod
docker stop traefik
docker network rm traefik-net
```

## License

This application is under [AGPL-3.0](LICENSE).
