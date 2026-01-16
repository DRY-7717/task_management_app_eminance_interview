
# 🧩 Task Management Application

A simple task management application built using Laravel 10, PHP 8.3.29, and Filament v3.
This project was created as part of a technical assessment to demonstrate backend development, role-based authorization, and admin panel implementation.

## 🚀 Tech Stack

- PHP: 8.3

- Laravel: 10.x

- Filament: v3.x

- Database: MySQL

- Authentication: Filament Authentication

- Email: Mailtrap (for email testing)

## ✨ Features

### Authentication & Roles

- Admin and Developer roles

- Default user accounts provided via seeder

### Task Management

- Admin can create, update, delete, and assign tasks to Developers

- Developers can only view tasks assigned to them

- Task status transitions are restricted based on user roles

- When a Developer updates a task status to Completed, an email notification is sent to the Admin

### Statuses & Severities

- Admin can manage task statuses and severities

- Drag-and-drop ordering using sort_order

- Safe deletion handling when referenced by tasks

- Active and inactive status support

### Comments

- Admin and the assigned Developer can post comments

- Supports nested replies (one level)

### Filtering & Search

- Filter tasks by Status and Severity

- Search functionality available on the task list

### Notifications

- Email notifications using Mailtrap when a task is in progress or completed by a Developer

## 📂 Database Schema (Core Entities)

- **users**: user accounts with roles

- **tasks**: tasks with status, severity, and assignment

- **statuses**: ordered task statuses with active/inactive state

- **severities**: severity levels with optional colors

- **comments**: comments with threaded replies

## 🛠 Installation

```bash
  git clone https://github.com/DRY-7717/task_management_app_eminance_interview.git
```

```bash
  cd task_management_app_eminance_interview
```

```bash
  composer install
```

```bash
  cp .env.example .env
```

```bash
  php artisan key:generate
```
```bash
  npm install
```

### Database Configuration
update .env:

```bash
DB_HOST=your_db_host
DB_PORT=your_db_port
DB_DATABASE=your_db_name
DB_USERNAME=username_db 
DB_PASSWORD=password_db

```
Run migrations and seeders:

```bash
php artisan migrate --seed
```


## ▶️ Running the Application (Local)

**Start Laravel Development Server**

```bash
  php artisan serve
```

**Start Vite (Hot Reload)**
In a separate terminal, run:

```bash
  npm run dev
```

## 🚀 Accessing the Admin Panel

This project uses Filament Admin Panel.

Open the following URL:

```bash
http://localhost:8000/admin

```
You will be redirected to the Filament login page.

**Note**: The default "/" route still displays the Laravel welcome page.

## 📧 Email Configuration (Mailtrap)
This project uses Mailtrap for email testing in the local development environment.

Add the following configuration to .env:

```bash
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_TO=your_email_mailtrap
MAIL_FROM_NAME="${APP_NAME}"
```

An email will be sent when a Developer updates a task status to **In Progress** or **Completed**.

## 👤 Default Users (Seeder)

| Role      | Email                                                 | Password |
| --------- | ----------------------------------------------------- | -------- |
| Admin     | [administrator@gmail.com](mailto:administrator@gmail.com)         | password |
| Developer One | [developerone@gmail.com](mailto:developerone@gmail.com) | password |
| Developer Two | [developertwo@gmail.com](mailto:developertwo@gmail.com) | password |


## 🧪 Technical Notes

- Authorization is handled using Laravel Policies

- Filament v3 is used for admin panel and resource management

- Status change rules are enforced at the page level

- Email notifications are triggered after form submission

## 📝 License
This project is intended for learning and technical assessment purposes.
