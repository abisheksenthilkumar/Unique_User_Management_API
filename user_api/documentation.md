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
  
**Response**
|Status Code	|Description|
|---|---|
|200	|Login OK, token should be returned in the response.|
|401	|Unauthorized: Invalid credentials.|
|400	|Bad Request: Incorrect request format or missing attributes.|

**Response Examples**
**200 OK:**
```
{
    "message": "Login success",
    "tokens": {
        "access_token": "a.your_access_token",
        "refresh_token": "r.your_refresh_token"
    }
}
```
**401 Unauthorized:**
```
{
    "error": "Invalid credentials"
}
```
**400 Bad Request:**
```
{
    "error": "Missing username or password"
}
```
```
{
  "username": "new_username", 
  "email": "new_email", 
  "password": "new_password"
}
```
**GET**` /user_api/index.php?endpoint=get_all_users `(Get all users)

**GET**` /user_api/index.php?endpoint=get_user_by_id&id=123 `(Get user by ID)

**PUT**`/user_api/index.php?endpoint=update_user&id=123 `(Update user by ID)

**Body:** (Similar to create user)

**DELETE**` /user_api/index.php?endpoint=delete_user&id=123 `(Delete user by ID)

|Attribute| Description|
|---|---|
| `update_user`    | PUT    | Updates an existing user account by ID. Requires the `id` query parameter and JSON data in the request body (similar to `create_user`). |
| `delete_user`    | DELETE | Deletes a user account by ID. Requires the `id` query parameter.                |

### Response Format

The API returns responses in JSON format.  The structure of the response will depend on the endpoint and the success or failure of the operation.

**Successful Responses:**

Typically include a `message` field indicating success and may contain additional data, such as the created or updated user object.

**Error Responses:**

*   **400 Bad Request:**  Returned when the request is malformed or missing required parameters. The response body will include an `error` field with a description of the issue.
*   **404 Not Found:**  Returned when the requested endpoint or resource does not exist.
*   **405 Method Not Allowed:**  Returned when the HTTP method used is not supported for the requested endpoint.
*   **500 Internal Server Error:** Returned when an unexpected error occurs on the server side.


**Example Error Response:**

```json
{
    "error": "Missing endpoint parameter"
}
```

