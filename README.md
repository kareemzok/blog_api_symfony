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

## Usage

```python
symfony server:start
```
OR
```python
php bin/console server:run
```

# API Documentation
The API documentation is available in OpenAPI/Swagger format. You can access it by navigating to http://localhost:8000/api/doc.

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

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)