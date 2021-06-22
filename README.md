# symfony-test

Для запуска проекта
1. Скопируйте .env.example в .env
2. Запустите из склонированной папки docker-compose up -d
3. Перейдите в контейнер с php-fpm
4. Запустите composer install из php-fpm контейнера
5. Запустите команду php bin/console doctrine:migrations:migrate
6. Перейдите на localhost:3180

Для того чтобы данные логировались запустите приложение с параметром request_log=1 например localhost:3180?request_log=1
Таблица логов находится по адресу localhost:3180/admin/http-log
Для фильтрации по ip укажите необходимый ip в форме
