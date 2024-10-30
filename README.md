# Introduction

A blog API application is a software application that provides a set of programming interfaces to interact with a blog's content and functionality. It allows developers to access and manipulate blog data programmatically, such as retrieving posts, comments, and user information

## Requirements

 * Symfony 7
 * PHP 8.2 or later
 * Database (e.g., MySQL)
 * Composer

## Installation

1. Clone the Repository
```bash
git clone https://github.com/your-username/blog-api.git
```
2. Install Dependencies
```bash
composer install
```
3. create & Configure Database: Update the database configuration in .env 

4. Run Migrations
```bash
php bin/console doctrine:migrations:migrate
```

5. Setup JWT and generate token
```bash
https://github.com/lexik/LexikJWTAuthenticationBundle?tab=readme-ov-file
```

## Usage

```python
symfony server:start
```
OR
```python
php bin/console server:run
```

# API Documentation
The API documentation is available in OpenAPI/Swagger format. You can access in the folder screens-documents in this repo

# Testing
To run the unit tests:

```python
php bin/console test
```
OR
```python
vendor/bin/phpunit
```

## Contributing

If you would like to contribute to the project, please read the CONTRIBUTING.md: CONTRIBUTING.md file.

## License

[MIT](https://choosealicense.com/licenses/mit/)