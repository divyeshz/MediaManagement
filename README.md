<a href="https://github.com/divyeshz/MediaManagement.git"> <h1 align="center">Media Management</h1></a>

## About

This project aims to provide a platform for users to perform various operations like Social Login, User and Media Management, Post Sharing, and Commenting on posts. It offers seamless integration with Facebook and Google for user authentication and media-sharing capabilities.

> **Note**
> Work in Progress

## Requirements

Package | Version
--- | ---
[Composer](https://getcomposer.org/)  | V2.6.3+
[Php](https://www.php.net/)  | V8.0.17
[Laravel](https://laravel.com/)  | V10.28.0
[Npm](https://laravel.com/)  | V10.2.5

## Getting Started

To get the MediaManagement project up and running, follow these steps:

## Prerequisites

Before you begin, make sure you have the following software installed:

- PHP
- Composer
- MySQL database

## Installation

> **Warning**
> Make sure to follow the requirements first.

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/divyeshz/MediaManagement.git
   ```

2. Change the working directory:

   ```bash
   cd MediaManagement
   ```

3. Install PHP dependencies using Composer:

   ```bash
   composer install
   ```

4. Create a copy of the `.env.example` file and rename it to `.env`. Update the file with your database configuration, Facebook and Google settings.

5. Generate an application key:

   ```bash
   php artisan key:generate
   ```

6. Migrate the Database and Seeding:

   ```bash
   php artisan migrate --seed
   ```

7. Start the development server:

   ```bash
   php artisan serve
   ```

The Media Management application should now be accessible at [http://localhost:8000](http://localhost:8000).

## Database Setup

You will need to configure your database connection in the `.env` file. Here's an example configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=MediaManagement
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

Make sure you create the `MediaManagement` database in your MySQL server before running migrations.

## Facebook And Google Configuration

To log in with Facebook And Google, you need to configure your Facebook And Google settings in the `.env` file. Here's an example :

```dotenv
FACEBOOK_CLIENT_ID='your client id'
FACEBOOK_CLIENT_SECRET='your client secret key'
FACEBOOK_CALLBACK_URL=http://localhost:8000/auth/facebook/callback

GOOGLE_CLIENT_ID='your client id'
GOOGLE_CLIENT_SECRET='your client secret key'
GOOGLE_CALLBACK_URL=http://localhost:8000/auth/google/callback
```

## Features


### Social Login

- Users can sign in using their Facebook and Google accounts.
- Fetches and stores user information (name, email, profile photo) in a table.

### User Management (CRUD)

- Listing, updating, and deleting operations for users.

### Media Management (CRUD)

- Listing, creating, updating, and deleting operations for media.

### Post Sharing

- Seamless sharing options for users to share their images with others.

### User Interface

- User-friendly interface for browsing posts.
- Commenting feature on posts.

### Personalized Content

- Users can comment on their posts and others' images based on certain conditions.
- Deletion rights limited based on ownership and comment conditions.

### Thumbnail Preview

- Generates thumbnails for uploaded images.
- Thumbnails are used for previews; click to view the original image.
