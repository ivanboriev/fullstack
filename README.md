# Установка
## Вариант 1

```shell
# Клонируем репозиторий в папку на веб сервере
git git@github.com:ivanboriev/fullstack.git

composer install

cp .env.example .env

Переходим на главную сайта и следуем инструкциям "установщика"
```

## Вариант 2

```shell
# Клонируем репозиторий в папку на веб сервере
git git@github.com:ivanboriev/fullstack.git

composer install

cp .env.example .env

Настраиваем подлючение к базе данных в .env

php artisan migrate --seed

```
