# 📦 Inventory Management System

A full-featured, role-based Inventory Management System built with **Laravel 12**, **PostgreSQL**, **Blade**, and **Tailwind CSS**. Designed to manage products, categories, suppliers, stock transactions, and generate reports with CSV export.

---

## 🛠️ Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Backend    | PHP 8.2, Laravel 12                 |
| Database   | PostgreSQL                          |
| Frontend   | Blade Templates, Tailwind CSS 4     |
| Auth       | Laravel Breeze                      |
| JS         | Alpine.js, Vanilla JS (Fetch/Ajax)  |
| Build Tool | Vite                                |

---

## ✨ Features

### 🔐 Authentication & Role-Based Access
- Login / Register with Laravel Breeze
- Two roles: **Admin** and **Staff**
- Custom `AdminMiddleware` for route protection
- Staff cannot access Categories, Suppliers, or Reports

### 📁 Categories (Admin only)
- Full CRUD — Create, Read, Update, Delete
- Unique name validation
- Cannot delete category if products are assigned (Foreign key protection with user-friendly error message)
- Pagination (10 per page)

### 🏭 Suppliers (Admin only)
- Full CRUD — Create, Read, Update, Delete
- Email uniqueness validation
- Cannot delete supplier if products are assigned
- Pagination (10 per page)

### 📦 Products (Admin + Staff)
- Full CRUD with SKU uniqueness validation
- Linked to Category and Supplier (Eloquent relationships)
- **Real-time Ajax search** by product name or SKU
- **Category filter** via dropdown
- Low stock badge (⚠ red) when quantity ≤ min threshold
- Pagination with query string persistence

### 📊 Stock Transactions (Admin + Staff)
- Stock In / Stock Out operations
- Shows current stock on product select (dynamic)
- **Atomic operations** using `DB::transaction()` — quantity update + transaction log happen together or not at all
- Insufficient stock validation before Stock Out
- Full transaction history with pagination

### 🏠 Dashboard (Admin + Staff)
- Summary cards: Total Products, Categories, Suppliers, Low Stock count
- Low Stock alert table with direct "Add Stock" link
- Recent 5 transactions
- Color-coded indicators (red for alerts, green for healthy stock)

### 📈 Reports (Admin only)
- Transaction history with **date range filter** (From / To)
- Summary: Total transactions, Total Stock In, Total Stock Out
- **Export Transactions to CSV**
- **Export Stock Summary to CSV** (with LOW STOCK / OK status)
- Current low stock products section

### 🔔 UI/UX
- Auto-dismissing flash messages (4 seconds) with manual close button (×)
- Custom 403 error page with "Go to Dashboard" button
- Reusable `<x-alert>` Blade component
- Responsive layout with Tailwind CSS

---

## 🗄️ Database Schema

```
users
├── id, name, email, password
├── role (enum: admin | staff)
└── timestamps

categories
├── id, name, description
└── timestamps

suppliers
├── id, name, email, phone, address
└── timestamps

products
├── id, name, sku (unique), price, quantity
├── min_threshold, description
├── category_id (FK → categories)
├── supplier_id (FK → suppliers)
└── timestamps

stock_transactions
├── id, type (enum: in | out), quantity, note
├── product_id (FK → products)
├── user_id (FK → users)
└── timestamps
```

---

## ⚙️ Local Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js 22+
- PostgreSQL
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/inventory-app.git
cd inventory-app

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate
```

### Database Setup

Create a PostgreSQL database:
```sql
CREATE DATABASE inventory_db;
```

Update `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=inventory_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

### Start Development Server

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Visit: `http://localhost:8000`

---

## 👥 Default Users

| Name       | Email                | Password    | Role  |
|------------|----------------------|-------------|-------|
| Admin User | admin@inventory.com  | password123 | admin |
| Staff User | staff@inventory.com  | password123 | staff |

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── CategoryController.php
│   │   ├── SupplierController.php
│   │   ├── ProductController.php
│   │   ├── StockTransactionController.php
│   │   ├── DashboardController.php
│   │   └── ReportController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Supplier.php
│   ├── Product.php
│   └── StockTransaction.php
resources/
└── views/
    ├── components/
    │   └── alert.blade.php
    ├── layouts/
    │   └── navigation.blade.php
    ├── categories/
    ├── suppliers/
    ├── products/
    ├── stock_transactions/
    ├── reports/
    ├── errors/
    │   └── 403.blade.php
    ├── dashboard.blade.php
    └── welcome.blade.php
database/
├── migrations/
└── seeders/
    └── AdminSeeder.php
routes/
└── web.php
```

---

## 🔑 Key Laravel Concepts Used

| Concept | Where Used |
|---|---|
| Resource Controllers | Categories, Suppliers, Products, StockTransactions |
| Eloquent ORM | All models with relationships |
| hasMany / belongsTo | Category→Products, Supplier→Products, Product→Transactions |
| Migrations & Seeders | Database setup and default users |
| Form Validation | All create/update forms |
| Middleware | AdminMiddleware for role-based access |
| Blade Components | `<x-alert>`, `<x-app-layout>`, `<x-nav-link>` |
| Pagination | All listing pages |
| Route Model Binding | All edit/update/delete routes |
| DB::transaction() | Stock In/Out atomic operations |
| Ajax / Fetch API | Real-time product search and filter |
| CSV Export | `response()->stream()` for reports |
| Flash Messages | Session-based success/error feedback |

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Prashant**
Full-Stack PHP Developer | Laravel · PostgreSQL · JavaScript · Bootstrap

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue)](https://linkedin.com/in/prashantyadav8301)
[![GitHub](https://img.shields.io/badge/GitHub-Follow-black)](https://github.com/prashantyadav8301)