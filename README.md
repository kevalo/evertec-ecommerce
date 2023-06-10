# Bootcamp project for Evertec

E-commerce site.

Check out the [Project definition](https://david-valbuena.notion.site/Reto-af876a2875a3408c8095ab62408030fa).

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![VueJs](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![MariaDb](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)

## Installation

- composer install
- npm install
- cp .env.example .env
- update the .env file with the DB_ and MAIL_ variables
- php artisan key:generate
- php artisan migrate --seed
- php artisan storage:link
- npm run build
- add to the server crontab: ```* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1```

## Development

- php artisan serve _or create a vhost in apache or nginx_
- npm run dev
