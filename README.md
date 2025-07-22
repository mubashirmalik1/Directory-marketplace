# Directory Marketplace API

This is the backend API for the Directory Marketplace application, built with Laravel 11. It provides a robust, scalable, and maintainable foundation for managing users, companies, and other core features.

## Core Architectural Pattern

The application follows a clean, layered architecture designed for separation of concerns, making it easier to test, maintain, and scale. The primary layers are Controllers, Services, and Repositories.

### 1. Layers

-   **Controllers (`app/Http/Controllers`)**: The outermost layer. Controllers are responsible for handling HTTP requests, validating incoming data using Form Requests, and returning structured JSON responses. They are kept "thin" by delegating all business logic to the service layer.

-   **Services (`app/Domains/Services`)**: This is where the core business logic resides. Services orchestrate the application's use cases, coordinating between repositories and other services. They are completely decoupled from the HTTP layer and work with simple data arrays or DTOs.

-   **Repositories (`app/Domains/Repositories`)**: This layer is responsible for all database interactions. It abstracts the data source (Eloquent, in this case) from the rest of the application. This centralizes data access logic and makes it easy to switch out the underlying storage mechanism if needed.

### 2. Key Components

-   **Form Requests (`app/Http/Requests`)**: Used for validating all incoming request data before it hits the controller's main logic. This keeps controllers clean and consolidates validation rules.

-   **JSON Resources (`app/Http/Resources`)**: Transform Eloquent models into a consistent, public-facing API format. This ensures that internal database structures are not leaked and that the API contract is strictly defined.

-   **Standardized API Responses**: All API responses, whether for success or error, are standardized using custom `response()->success()` and `response()->error()` macros. This provides a consistent and predictable response structure for all endpoints.

-   **UUIDs for Public IDs**: All models use UUIDs (`uuid` column) for public-facing identifiers, preventing enumeration of resources and hiding internal database IDs.

## Implemented Features

### 1. Authentication
A complete authentication system using Laravel Sanctum for token-based authentication.
-   **User Registration**: Allows new users to create an account.
-   **User Login**: Authenticates users and issues an API token.
-   **User Logout**: Revokes the user's current API token.

### 2. Welcome Email
-   Upon successful registration, a `UserRegistered` event is dispatched.
-   A listener, `SendWelcomeEmail`, handles this event by queuing and sending a welcome email to the new user.

### 3. Company CRUD
Full Create, Read, Update, and Delete functionality for companies, accessible via a RESTful API.
-   **Create Company**: Add a new company.
-   **List Companies**: Retrieve a list of all companies.
-   **Show Company**: View the details of a single company by its UUID.
-   **Update Company**: Modify the details of an existing company.
-   **Delete Company**: Remove a company.

### 4. Company Access Policy (Authorization Rules)

Only admins can mutate companies; regular users can only read.

**Rules:**
- **viewAny / view**: Allowed for any authenticated user.
- **create / update / delete / restore / forceDelete**: Allowed **only** for users with the `admin` role.


## Getting Started

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-repo/directory-marketplace.git
    cd directory-marketplace
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Setup environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Configure `.env` file:**
    Update your database credentials, mail driver settings, and other environment variables.

5.  **Run migrations:**
    ```bash
    php artisan migrate
    ```

6.  **Start the server:**
    ```bash
    php artisan serve
    ```

The API will be available at `http://127.0.0.1:8000/api/`.

## Future Improvements

### 1. Domain-Centric Validation
To further decouple domain logic from the framework, validation rules currently in `app/Http/Requests` could be moved into a dedicated directory like company etc, grouping them with the domain they belong to rather than the transport layer.
