### Описание
Демонстрационный проект RESTful-сервиса на Laravel 5 с использованием БД для работы с графами Neo4j, через ORM NeoEloquent и напрямую. Хранилище представляет собой граф пользователей со связями между ними. Возможности:

1. Показ списка заявок в друзья;
2. Добавление заявки в друзья;
3. Принятие заявки (добавление в друзья);
4. Отклонение заявки;
5. Показ списка друзей, друзей друзей, друзей друзей друзей и.т.д. конкретного уровня глубины (от 1 до N).

### API
[Описание API на Apiary](http://docs.socialgraph2.apiary.io/)

### Системные требования
* PHP >= 5.5.9
* [Neo4j](http://neo4j.com/) >= 2.2.3

### Инструкция по установке
1. Создать локальную копию репозитория
2. Выполнить composer install
3. Копировать файл .env.example в .env
4. Задать в .env-файле параметры DB_USERNAME и DB_PASSWORD от сервера Neo4j
5. (Опционально) Выполнить php artisan db:seed для заполнения базы тестовыми случайными данными (пользователи и связи). Может занять порядка минуты времени.
