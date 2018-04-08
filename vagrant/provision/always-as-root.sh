#!/usr/bin/env bash

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Restart web-stack"
sudo service php7.1-fpm restart
sudo service nginx restart
sudo service mysql restart

service elasticsearch restart
service redis restart
service supervisor restart