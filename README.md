# laravel-store

Project on Laravel

<a href="https://laravel-store.dragoon.pw">example</a>

user: OwnerYakoffKa<br>
login: yagithub+owner@mail.ru<br>
passw: 11111111<br>


# deploy
Разворачивание приложения на хостинге:

1. Выполняем подготовительные операции: проверка и приведение хостинга в соответствие требованиям фреймворка, создание базы данных, установка composer и прочее.

1. Клонируем проект: git clone git@github.com:yakoffka/laravel-store.git larastore

1. копируем .env.example в .env и прописываем в нём необходимые настройки

1. генерируем ключ приложения: php artisan key:generate

1. создаём символическую ссылку на публичную директорию приложения: php artisan storage:link

1. выполняем миграции с сидами: php artisan migrate:refresh --seed

1. Настраиваем планировщик Cron (импорт, очереди)

```
MAILTO="recipient@mail.ru"
25 09 * * * php /home/vagrant/projects/laravel_store/_watchdog_queue_larastore.php
MAILTO=""
00 * * * * php /home/vagrant/projects/laravel_store/_watchdog_queue_larastore.php
20 * * * * php /home/vagrant/projects/laravel_store/_watchdog_queue_larastore.php
40 * * * * php /home/vagrant/projects/laravel_store/_watchdog_queue_larastore.php

* * * * * php /home/vagrant/projects/laravel_store/artisan import:check
```
