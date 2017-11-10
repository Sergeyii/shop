#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Allocate swap for MySQL 5.6"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
update-locale LC_ALL="C"
dpkg-reconfigure locales

info "Configure timezone"
echo ${timezone} | tee /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password password \"''\""
debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password_again password \"''\""
echo "Done!"

info "Add PHp 7.1 repository"
sudo add-apt-repository ppa:ondrej/php -y

info "Update OS software"
sudo apt-get update
sudo apt-get upgrade -y

info "Install additional software"
#apt-get install -y git php5-curl php5-cli php5-intl php5-mysqlnd php5-gd php5-fpm nginx mysql-server-5.6
sudo apt-get install -y php7.1-curl php7.1-cli php7.1-intl php7.1-mysqlnd php7.1-gd php7.1-fpm php7.1-mbstring php7.1-xml php7.1-memcached unzip nginx mysql-server-5.6 memcached


info "Configure MySQL"
sudo sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
#sed -i 's/user = www-data/user = vagrant/g' /etc/php5/fpm/pool.d/www.conf
#sed -i 's/group = www-data/group = vagrant/g' /etc/php5/fpm/pool.d/www.conf
#sed -i 's/owner = www-data/owner = vagrant/g' /etc/php5/fpm/pool.d/www.conf


sudo sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sudo sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sudo sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sudo sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
sudo ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
sudo mysql -uroot <<< "CREATE DATABASE shop"
sudo mysql -uroot <<< "CREATE DATABASE shop_test"
echo "Done!"

info "Install composer"
sudo curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer