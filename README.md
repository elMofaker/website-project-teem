# University Website Project with PHP Backend

This project is a university website with a PHP backend using native PHP and MySQLi for database connectivity.

## Setup Instructions

### Requirements
- PHP 7.0 or higher
- MySQL 5.6 or higher
- Apache/Nginx web server

### Installation Steps

1. **Clone the repository to your web server's document root**
   ```
   git clone [repository-url] /path/to/web/root
   ```

2. **Set up the database**
   - Make sure your MySQL server is running
   - Navigate to the project folder in your browser: `http://localhost/website-project-teem/config/db_init.php`
   - This will automatically create the database, tables, and default admin user

3. **Configuration**
   - If needed, modify the database connection settings in `config/db_config.php`
   - Default settings:
     - Host: localhost
     - Username: root
     - Password: (empty)
     - Database: university_db

4. **Default Admin Login**
   - Username: admin
   - Password: admin123

## Features

- User authentication system (login/register)
- Dashboard for users
- News/posts management
- Contact form
- Responsive design

## Direct Access Routes

The dashboard and logout functionality are accessible only via direct routes and are not shown in the public navigation:

- **Dashboard**: `/dashboard/index.php` (requires login)
- **Logout**: `/login/logout.php` (requires active session)
- **View Posts**: `/dashboard/posts/view_posts.php` (requires login)
- **Edit Post**: `/dashboard/posts/edit_post.php?id=[post-id]` (requires admin login)
- **Delete Post**: `/dashboard/posts/delete_post.php?id=[post-id]` (requires admin login)

## File Structure

- `/config` - Database configuration and setup files
- `/dashboard` - User dashboard area
- `/login` - Authentication pages
- `/includes` - PHP helper files
- `/page` - Website content pages
- `/resource` - Images and other resources

## Security Notes

- Passwords are hashed using PHP's password_hash() function
- Prepared statements are used for all database queries to prevent SQL injection
- Input validation and sanitization is implemented
- Session-based authentication protects dashboard routes
- Role-based access control for admin functions

## Development

To modify or extend the project:

1. Edit the PHP files as needed
2. For database schema changes, update the `config/db_init.php` file
3. For styling changes, modify the CSS files in the respective directories

## Troubleshooting

- If you encounter database connection issues, verify your MySQL server is running and check the credentials in `config/db_config.php`
- For permission issues, ensure your web server has write access to the necessary directories
- Clear browser cache if you experience display issues after making changes

## License

This project is licensed under the MIT License. 