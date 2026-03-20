# ============================================
# Makefile - Kasirin Aja
# ============================================
# Makefile = kumpulan shortcut command
# Cara pakai: ketik "make <nama_command>" di terminal
#
# Contoh:
#   make docker       → Build & jalankan semua container
#   make down         → Stop semua container
#   make logs         → Lihat log semua container
# ============================================

# Variabel default
COMPOSE = docker compose

# ============================================
# COMMAND UTAMA
# ============================================

# 🚀 Build & Start semua container
# Ini command yang kamu mau jalankan!
docker: build up setup
	@echo ""
	@echo "======================================"
	@echo "  ✅ Kasirin Aja is running!"
	@echo "  🌐 Open: http://localhost:8080"
	@echo "======================================"
	@echo ""

# 🔨 Build image Docker
build:
	@echo "📦 Building Docker images..."
	$(COMPOSE) build

# ▶️ Start containers (background mode)
up:
	@echo "🚀 Starting containers..."
	$(COMPOSE) up -d

# ⏹️ Stop semua containers
down:
	@echo "🛑 Stopping containers..."
	$(COMPOSE) down

# 🔄 Restart semua containers
restart: down up
	@echo "🔄 Containers restarted!"

# 🧹 Stop & hapus semua (termasuk volumes/data)
clean:
	@echo "🧹 Cleaning everything..."
	$(COMPOSE) down -v --rmi all --remove-orphans

# ============================================
# SETUP & MIGRATION
# ============================================

# ⚙️ Setup awal setelah container running
setup:
	@echo "⚙️ Setting up Laravel..."
	$(COMPOSE) exec app cp -n .env.docker .env 2>/dev/null || true
	$(COMPOSE) exec app php artisan key:generate --force
	$(COMPOSE) exec app php artisan migrate --force
	$(COMPOSE) exec app php artisan db:seed --force 2>/dev/null || true
	$(COMPOSE) exec app php artisan storage:link 2>/dev/null || true
	@echo "✅ Setup complete!"

# 🗃️ Jalankan migration
migrate:
	$(COMPOSE) exec app php artisan migrate

# 🌱 Jalankan seeder
seed:
	$(COMPOSE) exec app php artisan db:seed

# 🔄 Fresh migration + seed (reset database)
fresh:
	$(COMPOSE) exec app php artisan migrate:fresh --seed

# ============================================
# DEVELOPMENT
# ============================================

# 📋 Lihat log semua container
logs:
	$(COMPOSE) logs -f

# 📋 Lihat log app saja
logs-app:
	$(COMPOSE) logs -f app

# 🐚 Masuk ke shell container app
shell:
	$(COMPOSE) exec app bash

# 🎯 Jalankan artisan command
# Contoh: make artisan CMD="route:list"
artisan:
	$(COMPOSE) exec app php artisan $(CMD)

# 🎯 Jalankan Tinker (Laravel REPL)
tinker:
	$(COMPOSE) exec app php artisan tinker

# 📦 Install Composer dependencies
composer-install:
	$(COMPOSE) exec app composer install

# 📦 Install NPM dependencies
npm-install:
	$(COMPOSE) exec app npm install

# 🔨 Build frontend assets
npm-build:
	$(COMPOSE) exec app npm run build

# 🔍 Jalankan npm run dev (Vite dev server)
npm-dev:
	$(COMPOSE) exec app npm run dev

# ============================================
# STATUS & INFO
# ============================================

# 📊 Lihat status containers
status:
	$(COMPOSE) ps

# ℹ️ Tampilkan bantuan
help:
	@echo ""
	@echo "╔══════════════════════════════════════════╗"
	@echo "║       🧾 Kasirin Aja - Makefile Help     ║"
	@echo "╠══════════════════════════════════════════╣"
	@echo "║                                          ║"
	@echo "║  UTAMA:                                  ║"
	@echo "║    make docker    → Build & start all    ║"
	@echo "║    make down      → Stop all             ║"
	@echo "║    make restart   → Restart all          ║"
	@echo "║    make clean     → Remove everything    ║"
	@echo "║                                          ║"
	@echo "║  DATABASE:                               ║"
	@echo "║    make migrate   → Run migrations       ║"
	@echo "║    make seed      → Run seeders          ║"
	@echo "║    make fresh     → Fresh migrate+seed   ║"
	@echo "║                                          ║"
	@echo "║  DEVELOPMENT:                            ║"
	@echo "║    make logs      → View all logs        ║"
	@echo "║    make shell     → Enter app shell      ║"
	@echo "║    make tinker    → Laravel Tinker        ║"
	@echo "║    make status    → Container status     ║"
	@echo "║                                          ║"
	@echo "╚══════════════════════════════════════════╝"
	@echo ""

.PHONY: docker build up down restart clean setup migrate seed fresh logs logs-app shell artisan tinker composer-install npm-install npm-build npm-dev status help
