# AsyncWebFrontend
FrontEnd installation for AsyncWeb

Installation: 
1) Install composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

2) Install project
```bash
mkdir /srv/www/vhosts/MyProject
cd /srv/www/vhosts/MyProject
git clone https://github.com/scholtz/AsyncWebFrontend.git
cd /srv/www/vhosts/MyProject/AsyncWebFrontend
cp composer.json.default composer.json
composer update
```

3) Set permissions 
```bash
chown -R www-data:users .
# or
chown -R user:www-data .

find . -type d -exec chmod 770 {} \; && find . -type f -exec chmod 660 {} \;
```

4) Set up webserver
then add path your virtual host for the domain in Apache, Nginx, or other webserver to /srv/www/vhosts/MyApp/htdocs

For example:
```
server {

	root /srv/www/vhosts/MyProject/AsyncWebFrontend/htdocs;
	index index.html index.php;

	server_name www.myproject.com ru.myproject.com;

	listen 443;
	ssl on;
	ssl_certificate /etc/nginx/ssl/myproject.crt;
	ssl_certificate_key /etc/nginx/ssl/myproject.key;
    ssl_protocols TLSv1 TLSv1.1; 
	ssl_ciphers "ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES256-GCM-SHA384:AES128-GCM-SHA256:AES256-SHA256:AES128-SHA256:AES256-SHA:AES128-SHA:DES-CBC3-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!MD5:!PSK:!RC4"; #Disables all weak ciphers
	ssl_prefer_server_ciphers on;

	location ~ \.php$ {
		location ~ \..*/.*\.php$ {return 404;}
		include fastcgi_params;
		fastcgi_pass  127.0.0.1:9000;
	}

	location / {
		try_files $uri $uri/ /index.php;
	}
}
```

Do not forget to reload apache or nginx, for example: 
```
nginx -t 				# test nginx config
service nginx reload 	# reload nginx config
```


5) Set up project
Set up your settings.php file. Use settings.example.php as example usage file.

You can alternativly use the web setup.

6) To upgrade project do the following:
```bash
git fetch origin master
git reset --hard FETCH_HEAD
git clean -df
```
