# ACSC476 Assignment

This repository contains the codebase for the ACSC476 assignment. The project is structured as a PHP web application with Docker support for local development and deployment.

## Project Structure

- `app/` - Main application code, including controllers, models, services, middleware, and routes.
- `public/` - Publicly accessible files, including the entry point (`index.php`), assets (CSS, JS, images).
- `vendor/` - Core framework code, facades, globals, interfaces, and base classes.
- `Docker/` - Docker configuration files for Nginx and PHP.
- `docker-compose.yml` - Docker Compose configuration for running the application stack.
- `package.json`, `pnpm-lock.yaml` - Node.js dependencies for frontend assets (if any).
- `structure.sql` - Database schema definition.

## Getting Started

### Prerequisites
- Docker & Docker Compose
- (Optional) Node.js and pnpm for frontend asset management

### Setup
1. **Clone the repository:**
   ```bash
   git clone https://github.com/Deathwalker9959/acsc476-assignment.git
   cd acsc476-assignment
   ```
2. **Start the application using Docker Compose:**
   ```bash
   docker-compose up --build
   ```
3. **Access the application:**
   - The app should be available at [http://localhost](http://localhost) (see `Docker/nginx/conf.d/site.conf` for details).

### Database
- The database schema is defined in `structure.sql`.
- Ensure the database service is running via Docker Compose.

### Frontend Assets
- CSS and JS files are located in `public/assets/`.
- If you need to build or manage frontend assets, use pnpm or npm:
   ```bash
   pnpm install
   pnpm run build
   ```

## Project Structure Details
- **Controllers:** Handle HTTP requests and responses.
- **Models:** Represent database entities.
- **Services:** Business logic and data manipulation.
- **Middleware:** Request filtering and authentication.
- **Routes:** Define API and web routes.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
This project is for educational purposes.
