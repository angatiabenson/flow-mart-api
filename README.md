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

-   PHP >= 8.2
-   Composer
-   Laravel 11.x
-   MySQL or other supported database
-   Postman or cURL for API testing

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/flowmart-api.git
    ```

2. Navigate to the project directory:

    ```bash
    cd flow-mart-api
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Copy `.env.example` to `.env` and configure your environment settings:

    ```bash
    cp .env.example .env
    ```

    Update the following parameters in your `.env` file:

    - `DB_DATABASE`: The name of your MySQL database.
    - `DB_USERNAME`: Your database username.
    - `DB_PASSWORD`: Your database password.

5. Generate an application key:

    ```bash
    php artisan key:generate
    ```

6. Run database migrations:

    ```bash
    php artisan migrate
    ```

7. Start the Laravel development server:

    ```bash
    php artisan serve
    ```

    The API will be available at `https://flowmart.banit.co.ke/`.

### Authentication

Authentication is done via API key, which is generated upon user login. The API key must be passed in the `Authorization` header for all requests that require authentication.

```http
Authorization: Bearer {API_KEY}
```

### Endpoints

#### 1. Register a New Account

**URL**: `/register`

**Method**: `POST`

**Description**: Creates a new user account.

**Request Body**:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "0712345678",
    "password": "password123"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Account created successfully.",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "0712345678"
    }
}
```

#### 2. Login

**URL**: `/login`

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

#### 3. Create a Product Category

**URL**: `/categories`

**Method**: `POST`

**Description**: Creates a new product category. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Request Body**:

```json
{
    "name": "Electronics"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Category created successfully."
}
```

#### 4. Add Products to a Category

**URL**: `/products`

**Method**: `POST`

**Description**: Adds a new product under the specified category. Requires authentication.

**Headers**:

```http
Authorization: Bearer {API_KEY}
```

**Request Body**:

```json
{
    "category_id": 1,
    "name": "Smartphone",
    "quantity": "10 devices"
}
```

**Response**:

```json
{
    "status": "success",
    "message": "Product added successfully."
}
```

#### 5. View Products by Category

**URL**: `/categories/{category_id}/products`

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
    "products": [
        {
            "id": 1,
            "name": "Smartphone",
            "quantity": "10 devices",
            "category": {
                "id": 1,
                "name": "Electronics"
            }
        }
    ]
}
```

### 6. Fetch All Categories

**URL**: `/categories`

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
    "categories": [
        {
            "id": 1,
            "name": "Electronics"
        },
        {
            "id": 2,
            "name": "Furniture"
        }
    ]
}
```

### 7. Fetch All Products

**URL**: `/products`

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
    "products": [
        {
            "id": 1,
            "name": "Smartphone",
            "quantity": "10 devices"
        },
        {
            "id": 2,
            "name": "Dining Table",
            "quantity": "10 pieces"
        }
    ]
}
```

## Error Handling

All error responses follow a consistent structure:

```json
{
    "code": 404,
    "status": "error",
    "message": "Error message here"
}
```

### Accessing the API

FlowMart API is an open API, and developers can access it using the following base URL:

```
https://flowmart.banit.co.ke/
```

All API endpoints described above can be accessed by appending the relevant paths to this base URL. For example, to fetch all product categories, you would use:

```
GET https://flowmart.banit.co.ke/categories
```

Ensure to include any necessary authentication tokens, where required, as per the endpoint descriptions.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
