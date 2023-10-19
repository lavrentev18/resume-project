## Тестовое задание

### Что нужно сделать?

Разработать веб-сервис "Библиотека".

3 роли: администратор, библиотекарь, клиент

Администратор:
- Может добавлять и удалять пользователей, устанавливать пароли.

Библиотекарь:
- Может добавлять и удалять книги  
- Может выдавать и принимать книги от клиентов

Клиент:
- Может просматривать имеющиеся книги  
- Искать по автору, жанру, издателю  
- Бронировать книги, снимать бронь

Бизнес-процесс:
1. Клиент заходит на сервис, бронирует книгу, затем приходит в библиотеку.  
2. Далее библиотекарь выдает книгу клиенту.  
3. Через некоторое время библиотекарь принимает книгу от клиента.  
При этом:
- Забронированную и выданную книгу нельзя забронировать и выдать  
- Время бронирования ограничено.  


Стек технологий:  
- Laravel 8 (last version)  
- PostgreSQL  


### Реализованные маршруты

#### Авторизация

- POST api/login - авторизация пользователя
- POST api/register - авторизация пользователя

#### Книги

- GET api/books - список всех книг
- POST api/books - создание новой книги
- DELETE api/books/{book} - удаление книги
- GET api/books/search - поиск книг
- PUT api/books/{book:slug}/reserve/{user} - бронирование книги для пользователя
- PUT api/books/free/{book:slug} - освобождение забронированной книги
- PUT api/books/{book:slug}/take/{user} - взятие книги пользователем
- PUT api/books/return/{book:slug} - возвращение книги пользователем

#### Пользователи

- GET api/me - информация о текущем пользователе
- GET api/users - список всех пользователей
- POST api/users - создание нового пользователя
- PUT api/users/{user} - изменение информации о пользователе
- DELETE api/users/{user} - удаление пользователя



