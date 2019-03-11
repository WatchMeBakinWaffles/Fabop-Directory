Make differences:

docker-compose exec php bin/console --no-interaction doctrine:migrations:diff

Make migrations on database:

docker-compose exec php bin/console --no-interaction doctrine:migrations:migrate

BASH in container :

docker exec -it <mycontainer> bash