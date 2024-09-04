Certainly! Here's the updated README for your **FlowMart API**, now including endpoints 6 and 7:

---

# FlowMart API

FlowMart API is a RESTful API designed to help SMEs manage their stock efficiently. It allows users to create accounts, manage product categories, and add and view products by category. The API is built using the Laravel framework and is secured with session-based API keys.

## Features

-   **Create an Account**: Allows users to register new accounts.
-   **Login to Account**: Provides user authentication and generates an API key for authenticated sessions.
-   **Create a Product Category**: Allows users to create product categories for better stock organization.
-   **Create Products for a Category**: Lets users add products under specific categories.
-   **Display Products by Category**: Retrieves and displays all products in a specific category.
-   **Fetch All Categories**: Retrieves and displays all product categories.
-   **Fetch All Products**: Retrieves and displays all products across all categories.

## Getting Started

### Prerequisites

-   **PHP** >= 8.0
-   **Composer**
-   **Laravel** 9.x
-   **MySQL** or other supported database
-   **Postman** or **cURL** for API testing

### Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/yourusername/flowmart-api.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd flowmart-api
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Copy `.env.example` to `.env` and configure your environment settings:**

    ```bash
    cp .env.example .env
    ```

    Update the following parameters in your `.env` file:

    - `DB_DATABASE`: The name of your MySQL database.
    - `DB_USERNAME`: Your database username.
    - `DB_PASSWORD`: Your database password.

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Start the Laravel development server:**

    ```bash
    php artisan serve
    ```

    The API will be available at `http://localhost:8000`.

### Authentication

Authentication is done via an API key, which is generated upon user login. The API key must be passed in the `Authorization` header for all requests that require authentication.

```http
Authorization: Bearer {API_KEY}
```

## Endpoints

### 1. Register a New Account

**URL**: `/api/register`  
**Method**: `POST`  
**Description**: Creates a new user account.

**Request Body**:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Account created successfully.",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        }
    }
}
```

### 2. Login

**URL**: `/api/login`  
**Method**: `POST`  
**Description**: Authenticates the user and returns an API key.

**Request Body**:

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Login successful.",
    "api_key": "your-generated-api-key"
}
```

### 3. Create a Product Category

**URL**: `/api/categories`  
**Method**: `POST`  
**Description**: Creates a new product category. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Request Body**:

```json
{
    "name": "Electronics",
    "description": "All electronic products"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Category created successfully.",
    "data": {
        "category": {
            "id": 1,
            "name": "Electronics",
            "description": "All electronic products"
        }
    }
}
```

### 4. Add Products to a Category

**URL**: `/api/categories/{category_id}/products`  
**Method**: `POST`  
**Description**: Adds a new product under the specified category. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Request Body**:

```json
{
    "name": "Smartphone",
    "description": "Latest model",
    "price": 299.99,
    "stock": 50
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Product added successfully.",
    "data": {
        "product": {
            "id": 1,
            "name": "Smartphone",
            "description": "Latest model",
            "price": 299.99,
            "stock": 50,
            "category_id": 1
        }
    }
}
```

### 5. View Products by Category

**URL**: `/api/categories/{category_id}/products`  
**Method**: `GET`  
**Description**: Retrieves all products under the specified category. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Response**:

```json
{
    "status": "success",
    "data": {
        "category": {
            "id": 1,
            "name": "Electronics"
        },
        "products": [
            {
                "id": 1,
                "name": "Smartphone",
                "description": "Latest model",
                "price": 299.99,
                "stock": 50
            }
            // Additional products...
        ]
    }
}
```

### 6. Fetch All Categories

**URL**: `/api/categories`  
**Method**: `GET`  
**Description**: Retrieves all product categories. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Response**:

```json
{
    "status": "success",
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Electronics",
                "description": "All electronic products"
            },
            {
                "id": 2,
                "name": "Furniture",
                "description": "Various kinds of furniture"
            }
            // Additional categories...
        ]
    }
}
```

### 7. Fetch All Products

**URL**: `/api/products`  
**Method**: `GET`  
**Description**: Retrieves all products across all categories. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Response**:

```json
{
    "status": "success",
    "data": {
        "products": [
            {
                "id": 1,
                "category_id": 1,
                "name": "Smartphone",
                "description": "Latest model",
                "price": 299.99,
                "stock": 50
            },
            {
                "id": 2,
                "category_id": 2,
                "name": "Dining Table",
                "description": "Wooden dining table",
                "price": 199.99,
                "stock": 20
            }
            // Additional products...
        ]
    }
}
```

## Error Handling

All error responses follow a consistent structure:

```json
{
    "status": "error",
    "message": "Error message here"
}
```

-   **Common Error Messages:**
    -   **401 Unauthorized**: Missing or invalid API key.
    -   **404 Not Found**: Resource not found.
    -   **400 Bad Request**: Validation errors or malformed request.

**Example of Validation Error Response:**

```json
{
    "status": "error",
    "message": "Validation failed.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Feel free to customize the README further based on any additional details or features you'd like to include!
