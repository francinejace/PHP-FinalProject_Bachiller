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

## 📖 Overview

This web application allows users to manage library activities like book searching, borrowing, and returning. Admin and student dashboards are built with PHP and styled for accessibility and usability.

### 🔑 Key Components

- Admin & Student Dashboards
- User Login and Registration
- Book Management (via MySQL)
- Modular Includes (Header, Footer, Navbar)
- Demo login/register files
- SQLite and MySQL compatibility (SQL scripts provided)

### ⚙️ Technology

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JS](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## ✅ Rules, Practices and Standards

1. `config.php` is used for local, `config_production.php` for deployment.
2. Pages are categorized by role: `admin/`, `student/`, `user/`.
3. Reusable layouts in `includes/`.
4. Only `index.php` is at root for entry point.
5. SQL files: 
   - `library.sql` for MySQL 
   - `init_sqlite.sql` for optional SQLite testing
6. Use `.htaccess` to enable clean URLs and security headers.
7. File naming follows camelCase or snake_case.

## 📁 File Structure

```
PHP_FinalProject/
├── admin/
│   └── dashboard.php
├── assets/
│   ├── css/
│   │   ├── example.css
│   │   └── style.css
│   ├── img/
│   │   └── mochi-mochi.png
│   └── js/
│       ├── example.js
│       ├── script.js
│       └── style.css
├── components/
│   ├── componentGroup/
│   │   └── example.component.php
│   └── templates/
│       ├── example.component.php
│       ├── foot.component.php
│       ├── footer.component.php
│       ├── head.component.php
│       └── nav.component.php
├── database/
│   ├── images.model.sql
│   ├── init_sqlite.sql
│   ├── library_mysql.sql
│   ├── library.db
│   ├── library.sql
│   ├── nameOfModels.model.sql
│   └── users.model.sql
├── docs/
│   ├── vsCode/
│   │   └── PHP-CI4-AITS.code-profile
│   ├── Database VS Code Manual.md
│   ├── Docker Manual.md
│   ├── Git Commits.md
│   ├── Initial Checklist.md
│   ├── Issues.md
│   ├── PHP Dev Manual.md
│   ├── PHP File Structure Manual.md
│   ├── Request.md
│   ├── VS Code Profile Manual.md
│   └── workbook activity 3 updated.md
├── errors/
│   ├── .404.error.php
│   ├── errorName.error.php
│   └── unauthorized.error.php
├── handlers/
│   ├── auth.handler.php
│   ├── example.handler.php
│   ├── mongodbChecker.handler.php
│   ├── postgreChecker.handler.php
│   ├── signup.handler.php
│   ├── updateAccount.handler.php
│   └── userView.handler.php
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── navbar.php
├── layouts/
│   ├── example.layout.php
│   └── main.layout.php
├── pages/
│   ├── account/
│   │   ├── assets/
│   │   └── index.php
│   ├── ExamplePage/
│   │   ├── assets/
│   │   │   ├── css/ → example.css
│   │   │   ├── img/ → nyeebe_white.png
│   │   │   └── js/ → example.js
│   │   └── index.php
│   ├── login/
│   │   ├── assets/css/login.css
│   │   └── index.php
│   ├── logout/
│   │   └── index.php
│   ├── signup/
│   │   └── index.php
│   └── users/
│       └── index.php
├── sql/
│   ├── New Table Auto Increment Script.sql
│   └── Old Table Auto Increment.sql
├── staticDatas/
│   ├── dummies/
│   │   ├── users.staticData.php
│   │   ├── example.staticData.php
│   │   ├── feature.staticData.php
│   │   ├── footer.staticData.php
│   │   ├── models.staticData.php
│   │   └── navPages.staticData.php
│   └── example.staticData.php
├── student/
│   └── dashboard.php
├── user/
│   ├── login_demo.php
│   ├── login.php
│   ├── logout.php
│   ├── register_demo.php
│   └── register.php
├── utils/
│   ├── auth.util.php
│   ├── config.php
│   ├── dbMigratePostgresql.util.php
│   ├── dbResetPostgresql.util.php
│   ├── dbSeederPostgresql.util.php
│   ├── envSetter.php
│   └── config.php (multiple copies, consider cleanup)


---

## 📚 Resources

| Title | Purpose | Link |
|-------|---------|------|
| PHP Manual | Language reference | https://www.php.net |
| MySQL Docs | DB reference | https://dev.mysql.com/doc/ |
| InfinityFree | Hosting | https://infinityfree.net |
| W3Schools | Web dev help | https://www.w3schools.com |
| Color Hunt | UI palette inspiration | https://colorhunt.co |
