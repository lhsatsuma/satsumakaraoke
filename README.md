# Author

Author: Luis Henrique Minoru Satsuma

# Installation

## Server Requirements

PHP version 7.3.x or 7.4.x is required, with the following extensions installed: 

- intl
- mbstring
- json
- mysqlnd
- mysqli
- openssl
- soap
- xmlrpc (Optional)
- xml
- fileinfo
- gd2

## Configuration

Run composer update to create all vendor required.

See .env_example file for requireds settings.

## Database

For now the system it's compatible only for MySQL (5.7+).

If it's the first installation, run base_table.sql on your server. For updates, see docs for Admin Internal Utilities.

# Technologies used

- Composer
- Framework PHP CodeIgniter 4+
- Bootstrap 4.5.2
- jQuery 3.6
- SweetAlert2 11.3.10
- Smarty 3.1
- PHPMailer 6.5
- MPDF 8.0+
- JsShrink 1.4