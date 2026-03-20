# Kasirin Aja

Kasirin Aja is a modern, responsive Point of Sale (POS) web application built using the Laravel framework. The application provides an essential set of tools designed specifically for retail and food & beverage businesses, focusing on streamlined checkout processes, inventory management, and robust sales analytics.

## Core Features

- **Dashboard & Analytics:** Real-time visibility into daily transaction volumes, monthly revenue, weekly sales charts, and top-selling products.
- **Cashier (POS) Interface:** Interactive user interface enabling cashiers to instantly filter by categories, construct carts, manage product quantities, and handle various transaction types (Cash, Debit, QRIS) including automatic change calculation.
- **Inventory Management:** Full CRUD operations allowing administrators to manage items, pricing, remaining stock, and categorization efficiently.
- **Transaction History:** Comprehensive ledger of past transactions. Includes filtering by specific dates, payment methods, transaction invoice lookups, and direct raw data export to CSV format.
- **Receipt Generation:** Dedicated, printable transaction receipt view optimized for thermal printers and standard A4 output.
- **Modern Stack:** Constructed using Laravel 10, Tailwind CSS, Alpine.js, and standardized via Laravel Pint. Fully containerized leveraging Docker Compose for immediate deployment.

## Requirements

The project uses Docker for a streamlined and isolated development environment. Ensure the following dependencies are installed on your host machine:

- Docker Desktop (or Docker Engine & Docker Compose plugin)
- Make (optional, but highly recommended for executing commands easily)

## Development Setup

Follow these steps to set up the application using the automated Docker environment.

### 1. Clone the repository and navigate to the directory

```bash
git clone https://github.com/naidrahiqa/Kasirin-Aja.git
cd "Kasirin Aja"
```

### 2. Run the application

If you have `make` installed on your machine, simply run:

```bash
make docker
```

This single command will sequentially:
1. Build the Docker images (PHP-FPM, Node.js, Composer).
2. Start the Nginx, App, and MySQL containers.
3. Install PHP dependencies via Composer.
4. Install and compile NPM assets via Vite.
5. Create and link environment configurations.
6. Generate application keys.
7. Run complete database migrations and seed default data.

### Manual Docker Initialization (Without Make)

If `make` is unavailable on your system, you can initialize the stack manually:

```bash
docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
docker compose exec app cp -n .env.docker .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan storage:link
```

## Accessing the Application

Once the initialization steps are successfully finished, the application will be accessible directly in your web browser:

- **Web Application URL:** http://localhost:8080

**Default Testing Credentials:**
- Email: admin@kasirin.test
- Password: password

## Application Architecture

The system operates across three core containers interacting within a private Docker network:
- `kasirin-nginx`: Reverse proxy directing HTTP traffic.
- `kasirin-app`: PHP 8.3 FPM container processing application logic and running Laravel.
- `kasirin-mysql`: MySQL 8.0 instance dedicated for data persistence stored safely in an isolated volume.

## Maintenance Commands

Standard commands provided via the Makefile for continuing development:
- `make up`: Start all containers in detached mode.
- `make down`: Stop and remove containers, networks, and images.
- `make logs`: Display output logs from the containers securely.
- `make npm-build`: Recompiles frontend assets tailored using Vite and Tailwind CSS.
- `make artisan CMD="your:command"`: Executes specific artisan commands against the active container.

## License

This project is open-sourced software distributed under the MIT license.
