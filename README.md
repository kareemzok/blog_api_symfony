# Introduction

Foobar is a Python library for dealing with word pluralization.

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