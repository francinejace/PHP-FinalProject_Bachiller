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

A simple PHP library management system with role-based access, dynamic UI, and MySQL integration.

## Quickstart

### Local Setup (XAMPP or any PHP host)
1. Import the SQL schema from `/database/library_mysql.sql` into your MySQL server.
2. Update your database credentials in `utils/config.php` if needed.
3. Place the project folder in your web server's root (e.g., `htdocs` for XAMPP).
4. Access the app at `http://localhost/PHP_FinalProject` (or your chosen path).

### Default Admin User
- Username: admin
- Password: admin123

## Features
- Admin & Student Dashboards
- User Login and Registration
- Book Borrowing and Returning
- Simple, modern UI with your logo (`assets/img/mochi-mochi.png`)

## Notes
- No Docker, Composer, or .env required.
- All configuration is in `utils/config.php`.
- Works on XAMPP, InfinityFree, or any standard PHP/MySQL host.
