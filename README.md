## About Cow And Bulls Game
Here are basic terms of the game - https://bg.wikipedia.org/wiki/%D0%91%D0%B8%D0%BA%D0%BE%D0%B2%D0%B5_%D0%B8_%D0%BA%D1%80%D0%B0%D0%B2%D0%B8

The game is made with help of Laravel web application framework. + some custom functionality

App info: Laravel v8.83.27 | (PHP v8.2.4) , MySQL

Structure of the app: cowsandbulls-app/

Model folder: 

- \app\Models\Game.php
- \app\Models\Guess.php
- \app\Models\User.php

View folder: /resources/views/

- auth/ - Authentication pages;
- game/ - index/show/topplayers/ - pages of the Game;
- layouts/app.blade.php - default layout of the app;
- layouts/navigation.blade.php - navigation bar;
- dashboard.blade.php - /dashboard; 
- welcome.blade.php - /;

Controller folder: (back-end)
- \app\Http\Controllers\GameController.php - the functionality for the game.

Migration folder:
- \database\migrations\

Public folder (CSS/JavaScript scripts/fonts):
- \public\css 
- index.php - main;
- css\ - (compiled css from resources/css/app.css) (bootstrap 4.1 / tailwindcss)
- js\ - (compiled css from resources/js/app.js) (jquery + bootstrap 4.1 js + related js)
- images\ - images;

Routes: 
- \routes\web.php

## 1. Create a Virtual Host (xammp and hosts)

- C:\xamp\apache\conf\extra\httpd-vhosts.conf 
- add lines for vhost (you can setup correctly the folder path D:\cowsandbulls-app) ->

<VirtualHost *:80>
    DocumentRoot "D:\cowsandbulls-app\public"
    ServerAdmin admin@localhost
    ServerName cowsandbulls.local
    ServerAlias cowsandbulls.local
	ErrorLog "logs/cowsandbulls.log"
	CustomLog "logs/custom.cowsandbulls.log" combined
	
    <Directory "D:\cowsandbulls-app\public">
       AllowOverride All
       Options Indexes FollowSymLinks

       Require local
       # if you want access from other pc's on your local network
       #Require ip 192.168.1
       # Only if you want the world to see your site
       #Require all granted
    </Directory>
</VirtualHost>

- C:\Windows\System32\drivers\etc\hosts 
- add line for vhost->
 
- 127.0.0.1       cowsandbulls.local

## 1.1 OR Just start laravel server 
-run commands: php artisan serve

## 2. Go to Project directory (D:\cowsandbulls-app) 

## 2.1 Run the command line (bash)

- run commands:

- composer install - to install the packages from composer.json
- composer update - to update them
- npm install - to install npm packages
- npm run dev - to re-compile the js/css files
- php artisan migrate - to run the migrations to create the new tables in MySql database

## 3. Open the web app and test pages/functionality

- http://cowsandbulls.local/ - Welcome (Links to Dashboard or Authentication - Login / Register )
- http://cowsandbulls.local/login - Login  (Authentication with the help of Breeze package)
- http://cowsandbulls.local/register - Register;

After Login in:

- http://cowsandbulls.local/dashboard - Dashboard
- http://cowsandbulls.local/games - My Games
- http://cowsandbulls.local/game/new - New Game
- http://cowsandbulls.local/game/{id} - After the new game is created, you can Play the game.   
- http://cowsandbulls.local/game/topplayers - Top Players 
- post http://cowsandbulls.local/game/end - End current game  


## 3.1 Register -> Login -> New Game -> Try to Guess the answer (Play the game) (or End the game)

- do 3.1 and repeat for some users (eg. 5-10) and for some games(eg. 5-10)

- and see 
- http://cowsandbulls.local/game/topplayers - Top Players 

# cowandbulls-app
