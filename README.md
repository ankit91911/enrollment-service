# Enrollment Service

This service provides API endpoints for managing student enrollments in courses. It validates the existence of students and courses by communicating with the User Service and Course Service respectively.

## Technology Stack

* **Backend Framework:** Laravel
* **Database:** MySQL
* **HTTP Client:** Guzzle HTTP Client (for inter-service communication)

## Database Configuration

* **Database Name:** `enrollment_service_db`
* Ensure that your MySQL server is running and you have configured the database connection details in your Laravel `.env` file.

## API Endpoints

### 1. Enroll Student in a Course

* **Endpoint:** `POST /api/enrollments`
* **Description:** Enrolls a student in a specific course. It validates if the provided `student_id` exists in the User Service and if the `course_id` exists in the Course Service.
* **Request Body (JSON):**
    ```json
    {
        "student_id": 1,
        "course_id": 1
    }
    ```
    * `student_id`: (integer, required) The ID of the student to enroll.
    * `course_id`: (integer, required) The ID of the course to enroll the student in.

* **Validation:**
    * The `student_id` must exist in the User Service and the corresponding user must have the role `student`.
    * The `course_id` must exist in the Course Service.

* **Inter-service Communication:**
    * **User Service:** Makes a `GET` request to `http://localhost:8001/api/users` (adjust port if needed) to check if the student exists and has the 'student' role.
    * **Course Service:** Makes a `GET` request to `http://localhost:8002/api/courses/{course_id}` (adjust port if needed) to check if the course exists.

* **Response (JSON - Success - Enrollment Created):**
    ```json
    {
        "id": 1,
        "student_id": 123,
        "course_id": 456,
        "created_at": "2025-05-18T06:45:00.000000Z",
        "updated_at": "2025-05-18T06:45:00.000000Z"
    }
    ```

* **Response (JSON - Error - Student Not Found):**
    ```json
    {
        "error": "Student not found"
    }, 404
    ```

* **Response (JSON - Error - Course Not Found):**
    ```json
    {
        "error": "Course not found"
    }, 404
    ```

### 2. List Enrollments

* **Endpoint:** `GET /api/enrollments`
* **Description:** Retrieves a list of all enrollments.
* **Request Parameters:** None
* **Response (JSON - Success):**
    ```json
    [
        {
            "id": 1,
            "student_id": 123,
            "course_id": 456,
            "created_at": "2025-05-18T06:45:00.000000Z",
            "updated_at": "2025-05-18T06:45:00.000000Z"
        },
        {
            "id": 2,
            "student_id": 789,
            "course_id": 101,
            "created_at": "2025-05-18T06:46:00.000000Z",
            "updated_at": "2025-05-18T06:46:00.000000Z"
        }
        // ... more enrollments
    ]
    ```

## Setup Instructions

1.  **Ensure User Service and Course Service are running:** This service depends on the User Service running on `http://localhost:8001` and the Course Service running on `http://localhost:8002` (or their respective configured ports).

2.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd <repository-name>
    ```

3.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

4.  **Copy the `.env.example` file to `.env` and configure your database connection details:**
    ```bash
    cp .env.example .env
    ```
    Edit the `.env` file with your MySQL database credentials:
    ```
    `DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=course_service_db
 DB_USERNAME=root
 DB_PASSWORD=hrhk
    ```

5.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

7.  **Start the Laravel development server:**
    ```bash
    php artisan serve --port=8003
    ```
    The API will be accessible at `http://127.0.0.1:8003/api`.

## Usage

You can use tools like Postman, Insomnia, or `curl` to interact with the API endpoints.

**Example using `curl`:**

* **Enroll a student in a course:**
    ```bash
    curl -X POST -H "Content-Type: application/json" -d '{"student_id": 3, "course_id": 1}' [http://127.0.0.1:8003/api/enrollments](http://127.0.0.1:8003/api/enrollments)
    ```
    *(Ensure that student with ID 3 exists in the User Service and course with ID 1 exists in the Course Service)*

* **List all enrollments:**
    ```bash
    curl [http://127.0.0.1:8003/api/enrollments](http://127.0.0.1:8003/api/enrollments)
    ```

## Further Development

Potential future enhancements could include:

* **Retrieving enrollments by student or course:** Implementing `GET` requests with query parameters (e.g., `/api/enrollments?student_id=123`).
* **Deleting enrollments:** Implementing a `DELETE /api/enrollments/{id}` endpoint.
* **Getting details of an enrollment by ID:** Implementing a `GET /api/enrollments/{id}` endpoint.
* **Handling enrollment limits or prerequisites:** Adding more complex enrollment rules.
* **Asynchronous communication with other services:** Using queues for better performance and resilience.
* **Error handling for inter-service communication:** Implementing retries or circuit breaker patterns.
* **Unit and integration tests:** Ensuring the reliability of the service, including testing the interaction with the User and Course services (mocking external requests).