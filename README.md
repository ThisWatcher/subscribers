subsriber_api
=============

How install project:
1) Navigate to folder and clone the repository:
```
git clone https://github.com/ThisWatcher/subscribers.git subcribers_api
```
2) Install composer following these instructions:
https://getcomposer.org/doc/00-intro.md
3) Run these composer commands to update dependencies:
```
php composer.phar install
php composer.phar update
```
4) install phpunit:
https://phpunit.de/getting-started/phpunit-7.html
5) Configure hosts to allow: localhost
in windows "C:\Windows\System32\drivers\etc\hosts" add this line:
```
127.0.0.1       localhost
```
6) Configure virtual host for your web server:
for Apache go to "\apache\conf\extra\httpd-vhosts.conf" file and add this virtual host
```
<VirtualHost *:80>
	DocumentRoot "path/to/web"
	ServerName localhost
</VirtualHost>
```
7) Configure parameters.yml file located in app/config (currently only database information is required)
  use parameters.yml.dist as example.
```
parameters:
    ...
    database_host: 127.0.0.1
    database_port: null
    database_name: symfony
    database_user: root
```
  
8) Create 2 databases 1 for production environment and 1 for testing. launch these 2 commands
```
php bin/console doctrine:database:create
php bin/console doctrine:database:create --env=test
```
9) Execute all migrations located in app/DoctrineMigrations folder (currently only 1 exists)
```
php bin/console doctrine:migration:execute 20181125112614    --up
php bin/console doctrine:migration:execute 20181125112614    --up --env=test
```
10) Seed database with data
```
php bin/console doctrine:fixtures:load
```
11) Run tests to make sure everything is working!!! :)
```
phpunit src/AppBundle/Tests/Controller/SubscriberControllerTest.php
```


Available paths:
```
to post: /subscriber , method POST
to get: /subscriber/{email} , method GET
to update: /subscriber/{email} , method PUT
to delete: /subscriber/{email}, method DELETE
```
Request data should look like this:
```
['email': "example@example.com",
'name': "example",
'state': "ACTIVE",
'fields': [
	'example': 'example'
	]
]
```
Response data looks like this:
```
['status': "success"
'code': 200
'data':['email': "example@example.com",
	'name': "example",
	'state': "ACTIVE",
	'fields': [
		'example': 'example'
		]
	] 
]

```

