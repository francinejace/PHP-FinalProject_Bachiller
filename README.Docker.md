### Building and running your application

1. Copy `.env.example` to `.env` and adjust if needed (defaults are set for local dev and Docker):
   - DB_HOST=db
   - DB_NAME=library
   - DB_USER=admin
   - DB_PASS=admin123
   - ADMIN_EMAIL=admin@example.com

2. Start your application with Docker Compose:
   `docker compose up --build`

3. Your application will be available at http://localhost:8000.

4. The MySQL database will be available at port 3306 (default Docker network). You can use phpMyAdmin or any MySQL client to connect:
   - Host: db (from inside Docker), or localhost:3306 (from host)
   - User: admin
   - Password: admin123
   - Database: library

5. The default admin user is:
   - Username: admin
   - Password: admin123

6. To reset or seed the database, use the SQL files in `/database/` or `/sql/` via phpMyAdmin or the MySQL CLI.

### PHP extensions
- The Dockerfile installs only the required PHP extensions for MySQL (pdo_mysql, gd, intl, zip).

### Notes
- Remove or update `.env` before deploying to production.
- For hosting, upload all files except those in `.gitignore` and set up your `.env` with the correct production credentials.