#Mysql Setup
CREATE DATABASE icard_gallery
GRANT ALL PRIVILEGES ON  icard_gallery.* TO 'icard_gallery'@'localhost'  IDENTIFIED BY 's9J#UT.[v.3.]7E';

#apache - centos 6.5
<VirtualHost *:80>
     ServerAdmin webmaster@example.com
     DocumentRoot /var/www/html/icard_gallery/web
     ServerName  icard_gallery.loc
     ServerAlias icard_gallery.loc
     ErrorLog  /var/www/html/icard_gallery/error.log
     CustomLog /var/www/html/icard_gallery/access.log common
     DirectoryIndex index.php
     <Directory  /var/www/html/icard_gallery  >
           AllowOverride All
           Allow from All
     </Directory>
</VirtualHost>


#http://bootstrap1.braincrafted.com/getting-started
Boostrap Assets
$ mkdir -p web/bootstrap/css
$ cd web/bootstrap/css
$ ln -s ../../../vendor/twitter/bootstrap/docs/assets/css/bootstrap.css bootstrap.css
$ ln -s ../../../vendor/twitter/bootstrap/docs/assets/css/bootstrap-responsive.css bootstrap-responsive.css
$ cd ..
$ mkdir -p js
$ cd js
$ ln -s ../../../vendor/twitter/bootstrap/docs/assets/js/bootstrap.min.js bootstrap.min.js
$ cd ..
$ ln -s ../../vendor/twitter/bootstrap/img img

$ mkdir web/uploads
$ chmod 777 web/uploads/ -R
$ mkdir web/uploads/thumbnail
$ chmod 777  web/uploads/thumbnail/ -R

#symofony 2 commands
php app/console generate:bundle --namespace=Digger/Icard/GalleryBundle --no-interaction
php app/console generate:doctrine:form DiggerIcardGalleryBundle:Image



php app/console doctrine:schema:update --force
php app/console doctrine:schema:update --dump-sql


