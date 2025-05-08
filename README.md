# 📊 WB Parser

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

> **A Laravel-based parser application that fetches data from Wildberries (WB) API and stores it in a database.**

---

## ✅ Features

- Fetch data from multiple WB API endpoints
- Automatic pagination handling
- Database storage for fetched data
- Models and migrations for all data types
- Command-line interface
- Testable architecture
- Asynchronous processing with Laravel queue system

---

## 🚀 Installation

1. Clone the repository:

```bash
git clone https://github.com/diat01/wb-parser.git
cd wb-parser
```

2. Install dependencies:

```bash
composer install
```

3. Create `.env` file and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database and run migrations:

```bash
php artisan migrate
```

---

## ⚙️ Configuration

Update these values in your `.env` file:

```env
WB_API_BASE_URL=https://wb-api.ru/
WB_API_KEY=your_api_key_here
WB_API_DEFAULT_LIMIT=500
```

---

## 🌐 Supported API Endpoints

The application uses the following WB API endpoints to fetch data:

| Endpoint           | Purpose     |
|--------------------|-------------|
| `GET /api/sales`   | Sales data  |
| `GET /api/orders`  | Order data  |
| `GET /api/incomes` | Income data |
| `GET /api/stocks`  | Stock data  |

All requests include automatic pagination handling to fetch full datasets.

---

## 📦 Command Line Usage

Each data type has its own command to allow fine-grained control.

---

### 🛒 Sales

**Command:**

```bash
php artisan wb:sync-sales
```

**Parameters:**

| Parameter    | Description                    | Example Value           |
|--------------|--------------------------------|-------------------------|
| `--dateFrom` | Start date (`Y-m-d`)           | `--dateFrom=2025-04-01` |
| `--dateTo`   | End date (`Y-m-d`)             | `--dateTo=2025-04-05`   |
| `--limit`    | Max number of records to fetch | `--limit=500`           |

**Example:**

```bash
php artisan wb:sync-sales --dateFrom=2025-04-01 --dateTo=2025-04-05 --limit=500
```

---

### 📦 Orders

**Command:**

```bash
php artisan wb:sync-orders
```

**Parameters:**

| Parameter    | Description                    | Example Value           |
|--------------|--------------------------------|-------------------------|
| `--dateFrom` | Start date (`Y-m-d`)           | `--dateFrom=2025-04-01` |
| `--dateTo`   | End date (`Y-m-d`)             | `--dateTo=2025-04-05`   |
| `--limit`    | Max number of records to fetch | `--limit=500`           |

**Example:**

```bash
php artisan wb:sync-orders --dateFrom=2025-04-01 --dateTo=2025-04-05 --limit=500
```

---

### 💰 Incomes

**Command:**

```bash
php artisan wb:sync-incomes
```

**Parameters:**

| Parameter    | Description                    | Example Value           |
|--------------|--------------------------------|-------------------------|
| `--dateFrom` | Start date (`Y-m-d`)           | `--dateFrom=2025-04-01` |
| `--dateTo`   | End date (`Y-m-d`)             | `--dateTo=2025-04-05`   |
| `--limit`    | Max number of records to fetch | `--limit=500`           |

**Example:**

```bash
php artisan wb:sync-incomes --dateFrom=2025-04-01 --dateTo=2025-04-05 --limit=500
```

---

### 📉 Stocks

**Command:**

```bash
php artisan wb:sync-stocks
```

**Parameters:**

| Parameter    | Description                    | Example Value           |
|--------------|--------------------------------|-------------------------|
| `--dateFrom` | Start date (`Y-m-d`)           | `--dateFrom=2025-04-01` |
| `--limit`    | Max number of records to fetch | `--limit=500`           |

**Example:**

```bash
php artisan wb:sync-stocks --dateFrom=2025-04-01 --limit=500
```

---

## ⏱️ Queue Worker

Laravel queues handle all heavy lifting to avoid blocking the main thread.

```bash
php artisan queue:work --tries=3
```

This ensures large datasets are processed in the background efficiently.

---

## 📦 Database Structure

| Table     | Purpose            |
|-----------|--------------------|
| `sales`   | Stores sales data  |
| `orders`  | Stores order data  |
| `incomes` | Stores income data |
| `stocks`  | Stores stock data  |

Each model uses proper `$casts` and `$fillable` fields for clean data handling.

---

## 🧩 Contributing

1. Fork the project: [https://github.com/diat01/wb-parser/fork](https://github.com/diat01/wb-parser/fork)
2. Create a feature branch:
   ```bash
   git checkout -b feature/my-feature
   ```
3. Commit your changes:
   ```bash
   git commit -am 'Add new feature'
   ```
4. Push to the branch:
   ```bash
   git push origin feature/my-feature
   ```
5. Open a Pull Request on GitHub

---

## 🤝 Contact

Have questions? Want to contribute?

- [GitHub Issues](https://github.com/diat01/wb-parser/issues)
- Email: didarov.atageldi@gmail.com
