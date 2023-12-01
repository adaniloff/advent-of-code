# @see the README.md on how to install / how to use.

# @see https://stackoverflow.com/questions/5947742/how-to-change-the-output-color-of-echo-in-linux
NC     := '\033[0m'    # No Color
RED    := '\033[0;31m'
LRED   := '\033[1;31m'
YELLOW := '\033[1;33m'

env := env_var_or_default('APP_ENV', '')
# @todo: throw an error if dependency is missing
docker := env_var_or_default('JUST.MAKE.DOCKER', 'docker-compose exec aoc_app')
docker_env := env_var_or_default('JUST.MAKE.DOCKER', 'docker-compose run --rm -e APP_ENV=$env aoc_app')

@_default:
  echo -e "Run {{YELLOW}}\"just <recipe>\"{{NC}} or {{YELLOW}}\"docker-compose run --rm app just <recipe>\"{{NC}} to run a recipe."
  just --list
  echo -e "\r\nRun {{YELLOW}}\"just -h\"{{NC}} or {{YELLOW}}\"docker-compose run --rm app just -h\"{{NC}} to see more commands."

# > (dev-tools): Run all the dev tools suite
doit:
  -@just app-dept
  -@just app-cs
  -@just app-stan
  -@just app-test

# > (dev-tools): Initiate the project (first time only !)
init:
  @echo '' >> api/.doit
  @just docker-up

# > (symfony): Run the symfony-cli binary
sf +cmd='':
  @{{docker}} symfony {{cmd}}

# > (symfony): Run the symfony-cli console
sf-c +cmd='':
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  {{docker}} symfony console {{cmd}} -e $env
alias sfc := sf-c

# > (composer): Run the composer binary
comp +cmd='':
  @{{docker}} composer {{cmd}}

# > (composer) shortcut: Install dependencies
comp-i +options='':
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  export APP_ENV=$env
  if [ "prod" == "$env" ] || [ "staging" == "$env" ]; then {{docker_env}} composer install {{options}} --no-dev; else {{docker_env}} composer install {{options}}; fi
  #if [ "prod" == "$env" ] || [ "staging" == "$env" ]; then restart_mode='yes'; else restart_mode='no'; fi
  restart_mode='no'
  read -p "Restart the containers? ($restart_mode if empty): " restart
  if ([ ! -z "$(whereis -b docker 2>/dev/null | sed -e 's/^.*://g')" ] && ([ -z "$restart" ] && [ "yes" == "$restart_mode" ]) || [ "y" == "${restart,,}" ] || [ "ye" == "${restart,,}" ] || [ "yes" == "${restart,,}" ]); then just docker-restart; fi
  if [ ! -z "$(whereis -b docker 2>/dev/null | sed -e 's/^.*://g')" ]; then just docker-up; fi;

# > (composer) shortcut: Update dependencies
comp-up +dependency='':
  @{{docker}} composer update {{dependency}}

# > (app): Clear all caches (symfony, composer...)
app-cc +options='':
  just sf-c cache:clear
  just comp cc
  just comp composer-dump-env-prod-only
alias cc := app-cc

# > (app): Run the migrations
app-dbz +options='':
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  just env=$env sf-c doctrine:database:create --if-not-exists
  just env=$env sf-c doctrine:migration:migrate --allow-no-migration --no-interaction {{options}}
alias dbz := app-dbz

# > (app): Run phpstan
app-stan +options='':
  @{{docker}} vendor/bin/phpstan analyse --memory-limit 256M {{options}}
alias stan := app-stan

# > (app): Run cs-fixer
app-cs:
  @{{docker}} vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --allow-risky=yes
alias cs := app-cs

# > (app): Run the tests
app-test:
  @{{docker}} bin/phpunit
alias test := app-test

# > (app): Display the app logs
app-logs:
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  {{docker}} cat var/log/$env.log /var/log/php-fpm.error.log
alias log := app-logs

# > (app): Tail the app logs
app-tail:
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  {{docker}} tail -f var/log/$env.log /var/log/php-fpm.error.log
alias tail := app-tail

# > (symfony) shortcut: Clear the cache
sf-cc +options='':
  @just sf-c cache:clear

# > (docker): Start the containers
docker-up:
  #!/usr/bin/env bash
  env=`just env={{env}} _getenv`
  docker-compose -f docker-compose.yaml -f docker-compose.$env.yaml -f docker-compose.debug.yaml up -d
alias du := docker-up

# > (docker): Down the containers
docker-down:
  @docker-compose down
alias dd := docker-down

# > (docker) shortcut: Restart the containers
docker-restart:
  @docker-compose restart
alias drs := docker-restart

# > (docker) shortcut: Down & Start the containers
docker-doup:
  @just docker-down
  @just docker-up
alias doup := docker-doup

# > (docker): Display the container logs
docker-logs +options='':
  @docker-compose logs {{options}}
alias dlog := docker-logs

# > (docker): Tail the container logs
docker-tail +options='':
  @docker-compose logs -f {{options}}
alias dtail := docker-tail

# > (docker): Display the container status
docker-ps +options='':
  @docker-compose ps {{options}}
alias dps := docker-ps

# > (dev-tools): Fix permissions
[unix]
perms owner='1000':
  @{{docker}} find . ! -name 'justfile' -exec chown -v {{owner}}:{{owner}} {} \;

# > (dev-tools): Fix permissions
[windows]
perms owner='1000':
  @echo "This command is experimental ! It has not been tested."
  @takeown /F . /R

_getenv:
  #!/usr/bin/env bash
  env={{env}}
  if [ -z "$env" ]; then read -p "Env not detected. Please define your env (dev if empty): " env; env=${env:-dev}; fi
  echo $env
