<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# WB Parser

A Laravel-based parser application that fetches data from WB API and stores it in a database.

## Features

- Fetch data from multiple WB API endpoints
- Automatic pagination handling
- Database storage for fetched data
- Models and migrations for all data types
- Command-line interface
- Testable architecture

## Installation

1. Clone the repository:

```bash
git clone https://github.com/diat01/wb-parser.git
cd wb-parser
```

2. Install dependencies:

```bash
composer install
```

3. Create and configure the `.env` file:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Configure database and run migrations:

```bash
php artisan migrate
```

## Configuration

Configure these settings in your `.env` file:

```env
WB_API_BASE_URL=https://wb-api.ru/
WB_API_KEY=your_api_key_here
WB_API_DEFAULT_LIMIT=500
```

## Usage

### Command Line Usage

To fetch all data:

```bash
php artisan wb:fetch
```

For a specific date range:

```bash
php artisan wb:fetch --dateFrom=2023-01-01 --dateTo=2023-01-31
```

To set a record limit:

```bash
php artisan wb:fetch --limit=100
```

### API Endpoints

- `GET /api/sales` - Sales data
- `GET /api/orders` - Orders data
- `GET /api/stocks` - Stocks data
- `GET /api/incomes` - Incomes data

## Database Structure

- `sales` - Sales data
- `orders` - Orders data
- `stocks` - Stocks data
- `incomes` - Incomes data

## Testing

To run tests:

```bash
./vendor/bin/pest
```

## Running with Docker

To run in a Docker environment:

```bash
docker-compose up -d
```

## Contributing

1. Fork it (https://github.com/diat01/wb-parser/fork)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request
