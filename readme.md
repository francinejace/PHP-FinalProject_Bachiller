<a name="readme-top">

<br />

<div align="center">
  <a href="https://github.com/francinejace/PHP_FinalProject">
    <img src="././assets/img/mochi-mochi.png" alt="mochi-mochi" width="130" height="100">
  </a>
  <h3 align="center">Library Management System</h3>
</div>

<div align="center">
  A final requirement for <strong>CCS0043 – Application Development and Emerging Technologies</strong>. This PHP project demonstrates a simple yet secure library management system with role-based access, dynamic UI, and MySQL integration.
</div>

<br />

![](https://visit-counter.vercel.app/counter.png?page=francinejace/PHP_FinalProject)

---

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#overview">Overview</a>
      <ol>
        <li><a href="#key-components">Key Components</a></li>
        <li><a href="#technology">Technology</a></li>
      </ol>
    </li>
    <li><a href="#rules-practices-and-standards">Rules, Practices and Standards</a></li>
    <li><a href="#resources">Resources</a></li>
  </ol>
</details>

---

# Library Management System

A modern PHP library management system with role-based access, dynamic UI, and MySQL integration.

## Quickstart

### Local/Docker
1. Copy `.env.example` to `.env` and adjust if needed (defaults are set for local dev and Docker):
   - DB_HOST=db
   - DB_NAME=library
   - DB_USER=admin
   - DB_PASS=admin123
   - ADMIN_EMAIL=admin@example.com
2. Run `docker compose up --build`
3. Access the app at http://localhost:8000
4. Access MySQL (phpMyAdmin or client):
   - Host: db (from inside Docker), or localhost:3306 (from host)
   - User: admin
   - Password: admin123
   - Database: library

### Hosting
- Upload all files except those in `.gitignore`.
- Set up your `.env` with your production database credentials.
- Import the SQL schema from `/database/library.sql`.

### Default Admin User
- Username: admin
- Password: admin123

## Features
- Admin & Student Dashboards
- User Login and Registration
- Book Management (MySQL)
- Modular Includes (Header, Footer, Navbar)
- Role-based permissions
- Responsive design

## File Structure
- See the project folders for organization. Only necessary files and folders are included.

## Environment Variables
- See `.env.example` for all required variables.

## License
MIT
