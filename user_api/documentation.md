# User Management API

This is a RESTful API for managing user accounts built with PHP and MySQL. It provides endpoints for user registration, login, authentication, and CRUD (Create, Read, Update, Delete) operations on user data.

# API Documentation

## Authentication (Login)

- **Endpoint:** `POST /user_api/index.php?endpoint=login`
- **Body:**
  ```
  {
      "username": "your_username",
      "password": "your_password"
  }
  ```
