# Data Management Project

This project is a web application for managing user data. It uses Docker for containerization, PHP for the backend, and MySQL for the database. The frontend is built with HTML, CSS, Bootstrap, and JavaScript with Toaster notifications.

## Technologies Used

- **Docker** & **Docker Compose**: For containerization
- **PHP**: Backend scripting
- **MySQL**: Database
- **HTML**, **CSS**, **Bootstrap**: Frontend styling
- **JavaScript**: Interactivity
- **Toaster.js**: Notifications

## Features

- Add, edit, and delete users
- Responsive design with Bootstrap
- Notifications for user actions

## Getting Started

1. **Clone the Repository**

    ```bash
    git clone https://github.com/your-username/data-management.git
    cd data-management
    ```

2. **Set Up Environment Variables**

    Create a `.env` file in the root directory with the following content:

    ```plaintext
    DB_HOST=db
    DB_USER=root
    DB_PASSWORD=yourpassword
    DB_NAME=shopdb
    ```

3. **Build and Run the Project**

    ```bash
    docker-compose up --build
    ```

4. **Access the Application**

    Open your browser and go to `http://localhost:8080`.

5. **Stop the Containers**

    ```bash
    docker-compose down
    ```

## Project Structure

- `docker-compose.yml` - Docker Compose configuration
- `Dockerfile` - Dockerfile for PHP

## Usage

- **Add a User**: Go to the Add User section and fill in the details.
- **Edit a User**: Click the Edit button next to a user in the list.
- **Delete a User**: Click the Delete button next to a user and confirm.

## Contributing

1. Fork the repository
2. Create a new branch
3. Make your changes
4. Submit a pull request
