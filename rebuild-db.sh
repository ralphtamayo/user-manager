#!/usr/bin/env bash

php bin/console doctrine:database:drop -n --if-exists --force \
	&& php bin/console doctrine:database:create -n \
	&& php bin/console doctrine:migrations:migrate -n --env=test \
	&& php bin/console doctrine:fixtures:load -n -v --env=test
