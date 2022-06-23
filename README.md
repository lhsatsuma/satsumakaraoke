# Author

Author: Luis Henrique Minoru Satsuma

# Installation

## Server Requirements

PHP version 7.4.x is required, with the following extensions installed: 

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
- [Composer] Framework PHP CodeIgniter 4.2+
- Bootstrap 4.5.3
- FontAwesome 5.15.4
- jQuery 3.6
- SweetAlert2 11.4.18
- [Composer] Smarty 3.1+
- [Composer] PHPMailer 6.6+
- [Composer] MPDF 8.1+
- [Composer] JsShrink 1.4
- yt-dlp 2022.06.22.1