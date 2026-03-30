
---

# ✦ heyToday! — Task Management App

### CMSC 129 Laboratory Assignment 2

> *Stay focused, get it done.*

A full-stack task management web application built using the **MVC (Model-View-Controller)** architecture with the **Laravel** framework and **PostgreSQL** database.

---

## 📋 Overview

**heyToday!** is a productivity-focused task manager that helps users organize tasks into multiple lists, monitor progress, and manage workflows efficiently.

It features a **clean dark-themed dashboard**, real-time task tracking, priority levels, due dates, and **soft-delete (archive)** functionality.

---

## 🛠️ Tech Stack

| Layer           | Technology              |
| --------------- | ----------------------- |
| Framework       | Laravel 13 (PHP 8.3)    |
| Database        | PostgreSQL              |
| ORM             | Eloquent                |
| Views           | Blade Templating Engine |
| Styling         | Tailwind CSS (CDN)      |
| Build Tool      | Vite + Node.js          |
| Version Control | Git + GitHub            |

---

## 🏗️ Project Structure (MVC)

```
lab2/
├── app/
│   ├── Http/Controllers/
│   │   ├── TaskController.php
│   │   └── TaskListController.php
│   └── Models/
│       ├── Task.php
│       └── TaskList.php
├── database/migrations/
│   ├── ..._create_task_lists_table.php
│   └── ..._create_tasks_table.php
├── resources/views/
│   ├── layouts/app.blade.php
│   └── tasks/
│       ├── index.blade.php
│       └── _card.blade.php
└── routes/web.php
```

---

## 🔁 MVC Architecture Explained

* **Models** (`Task`, `TaskList`)
  Handle database structure, relationships, and data operations using Eloquent ORM.
  `Task` implements **SoftDeletes** for archiving.

* **Views** (Blade Templates)
  Responsible for UI rendering.
  Uses a master layout (`layouts/app.blade.php`) with reusable sections.

* **Controllers** (`TaskController`, `TaskListController`)
  Process HTTP requests, interact with models, and return views.
  Maintains separation of concerns (no business logic in views).

---

## 🗄️ Database Schema

### `task_lists`

| Column     | Type      | Description |
| ---------- | --------- | ----------- |
| id         | bigint    | Primary key |
| name       | string    | List name   |
| created_at | timestamp | Auto        |
| updated_at | timestamp | Auto        |

### `tasks`

| Column      | Type            | Description                                     |
| ----------- | --------------- | ----------------------------------------------- |
| id          | bigint          | Primary key                                     |
| list_id     | foreign key     | References `task_lists`                         |
| task        | string          | Task name                                       |
| description | text (nullable) | Task details                                    |
| priority    | enum            | Low / Medium / High                             |
| status      | tinyint         | 0 = Not Started, 1 = In Progress, 2 = Completed |
| due_date    | date (nullable) | Due date                                        |
| deleted_at  | timestamp       | Soft delete (archive)                           |
| created_at  | timestamp       | Auto                                            |
| updated_at  | timestamp       | Auto                                            |

---

## 🚀 Installation & Setup

### Prerequisites

* PHP 8.5+
* Composer
* Node.js & npm
* PostgreSQL
* Git

### Steps

#### 1. Clone the Repository

```bash
git clone https://github.com/hungrychef-bytescode/CMSC129-Lab2-JaniolaAM_VerdeM.git
cd CMSC129-Lab2-JaniolaAM_VerdeM/lab2
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Configure Database

Update `.env` with your PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

#### 5. Run Migrations

```bash
php artisan migrate
```

#### 6. Start Development Servers

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

#### 7. Access the App

```
http://localhost:8000
```

---

## 📸 Screenshots

<img width="1919" height="870" src="https://github.com/user-attachments/assets/42f449f2-bf46-4cfa-ac87-6da25993fc5b" />
<img width="1919" height="862" src="https://github.com/user-attachments/assets/d8e4ec8f-1d9d-4958-b8d1-a9c0f51ab42f" />

---

## ✨ Features

| Feature                                      | Status |
| -------------------------------------------- | ------ |
| Create tasks (name, priority, due date)      | ✅      |
| View tasks (active, completed, archived)     | ✅      |
| Update tasks                                 | ✅      |
| Soft delete (archive)                        | ✅      |
| Restore archived tasks                       | ✅      |
| Permanent delete                             | ✅      |
| Validation (store & update)                  | ✅      |
| Search tasks                                 | ✅      |
| Filter by status/priority                    | ✅      |
| Sort tasks (date, priority)                  | ✅      |
| Task-list relationship (hasMany / belongsTo) | ✅      |
| Task Lists (create/delete)                   | ✅      |
| Task progress dashboard                      | ✅      |
| Pagination (10 per page)                     | ✅      |

---

## ⚠️ Notes

* PostgreSQL configuration must be completed before running migrations.
* README finalized for project documentation and submission.

---

## 👥 Contributors

| Name        | Role                                            |
| ----------- | ----------------------------------------------- |
| Verde, M    | Backend (Models, Controllers, Database, Routes) |
| Janiola, AM | Frontend (Blade Views, UI/UX Design)            |

---

## 📚 References

* Laravel Documentation
* Eloquent ORM
* Blade Templates
* PostgreSQL Documentation

---

