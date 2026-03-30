# heyToday! ✦ — Task Management App
### CMSC 129 Laboratory Assignment 2

> *Stay focused, get it done.*

A full-stack task management web application built with the **MVC (Model-View-Controller)** architectural pattern using the **Laravel** PHP framework and **PostgreSQL** database.

---

## 📋 Application Description

**heyToday!** is a productivity-focused task manager that lets users organize tasks into multiple lists, track progress, and manage their workflow efficiently. It features a clean dark-themed dashboard with real-time task status tracking, priority levels, due dates, and soft-delete (archive) functionality.

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.3) |
| Database | PostgreSQL |
| ORM | Eloquent |
| Views | Blade Templating Engine |
| Styling | Tailwind CSS (CDN) |
| Build Tool | Vite + Node.js |
| Version Control | Git + GitHub |

---

## 🏗️ MVC Architecture

```
lab2/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── TaskController.php       # Handles all task CRUD operations
│   │       └── TaskListController.php   # Handles list creation and deletion
│   └── Models/
│       ├── Task.php                     # Task model with SoftDeletes, belongs to TaskList
│       └── TaskList.php                 # TaskList model, has many Tasks
├── database/
│   └── migrations/
│       ├── ..._create_task_lists_table.php   # task_lists schema
│       └── ..._create_tasks_table.php        # tasks schema (with soft deletes)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Master layout (sidebar + topbar)
│       └── tasks/
│           ├── index.blade.php          # Main dashboard view
│           └── _card.blade.php          # Reusable task card component
└── routes/
    └── web.php                          # All application routes
```

### How MVC Works in This Project

- **Models** (`Task`, `TaskList`) — Define database structure, relationships, and handle all data operations via Eloquent ORM. `Task` uses `SoftDeletes` for archive functionality.
- **Views** (Blade templates) — Display data to the user. The master layout (`layouts/app.blade.php`) contains the sidebar and shared UI. Individual views use `@extends` and `@section` to inject content.
- **Controllers** (`TaskController`, `TaskListController`) — Receive HTTP requests from routes, interact with Models to fetch/modify data, and return Views. No business logic in views, no HTML in controllers.

---

## 🗄️ Database Schema

### `task_lists` table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | List name |
| created_at | timestamp | Auto |
| updated_at | timestamp | Auto |

### `tasks` table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| list_id | foreign key | References task_lists |
| task | string | Task name |
| description | text (nullable) | Task description |
| priority | enum | Low / Medium / High |
| status | tinyint | 0=Not Started, 1=In Progress, 2=Completed |
| due_date | date (nullable) | Due date |
| deleted_at | timestamp (nullable) | Soft delete timestamp |
| created_at | timestamp | Auto |
| updated_at | timestamp | Auto |

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.5+
- Composer
- Node.js & npm
- PostgreSQL
- Git

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/hungrychef-bytescode/CMSC129-Lab2-JaniolaAM_VerdeM.git
cd CMSC129-Lab2-JaniolaAM_VerdeM/lab2
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node dependencies**
```bash
npm install
```

**4. Set up environment file**
```bash
cp .env.example .env
php artisan key:generate
```

**5. Configure your database**

Edit `.env` and set your PostgreSQL credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

**6. Run database migrations**
```bash
php artisan migrate
```

**7. Start the development server**

In two separate terminals:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

**8. Visit the app**
```
http://localhost:8000
```

---
## 👥 Screenshot of Project
<img width="1917" height="878" alt="image" src="https://github.com/user-attachments/assets/8e3ba72b-4cb7-400f-a710-07f1b32896de" />

---
## 👥 Contributors

| Name | Role |
|------|------|
| Verde, M | Backend (Models, Controllers, Database, Routes) |
| Janiola, AM | Frontend (Blade Views, UI/UX Design) |

---

## 📚 References
- [Laravel Documentation](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
