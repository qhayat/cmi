# Feature available

### Authentication
    - [POST] /api/login (internal user authentication)

### Comment api endpoint
    - [GET] /api/comments (get a comment collection)
    - [POST] /api/comments (create a comment)
    - [DELETE] /api/comments/{id} (delete a comment)
    - [GET] /api/comments/{id} @TODO
    - [PUT] /api/comments/{id} @TODO

# Technologies

    - php 8.1
    - Symfony 6.3.3
    - Postgres
    - Docker / Docker-compose
    - Open API spécification
    - nelmio/api-doc-bundle
    - jwt

# Instalation

make init

# Swagger access

https://localhost::8443/api/docs
