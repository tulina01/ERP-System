# ERP System

## Project Overview
This ERP (Enterprise Resource Planning) system is designed to manage customers, inventory items, and generate various reports. It provides functionalities for adding, editing, and deleting customers and items, as well as generating invoice reports, invoice item reports, and item reports.

## Assumptions
1. The system is designed for a single-tenant environment.
2. Users have basic knowledge of PHP and MySQL.
3. The system is intended for use in a local development environment.
4. All dates are assumed to be in the format YYYY-MM-DD.
5. Customer contact numbers are assumed to be 10 digits long.
6. Item codes are unique identifiers for each item.
7. The system does not include user authentication or authorization.

## Technologies Used
- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (ES6+)
- Bootstrap 5
- MAMP (for local development environment)

## Setup Instructions

### Prerequisites
1. Install MAMP (https://www.mamp.info/)
2. Ensure you have a modern web browser (Chrome, Firefox, Safari, or Edge)

### Database Setup
1. Start MAMP and open phpMyAdmin (http://localhost:8888/phpMyAdmin/)
2. Create a new database named `assignment`
3. Import the provided SQL file (`assignment.sql`) into the `assignment` database

### Project Setup
1. Clone this repository or download the project files
2. Move the project folder to the MAMP htdocs directory (usually `/Applications/MAMP/htdocs/` on Mac or `C:\MAMP\htdocs\` on Windows)
3. Rename the project folder to `erp-system` (or your preferred name)

### Configuration
1. Open the file `config/database.php`
2. Update the database connection details if necessary:
   ```php
   private $host = "localhost";
   private $db_name = "assignment";
   private $username = "root";
   private $password = "root";

## Running the Application

1. Start MAMP
2. Open your web browser and navigate to `http://localhost:8888/erp-system/` (adjust the port if your MAMP is configured differently)
3. You should now see the ERP system dashboard