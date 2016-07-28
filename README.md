# mashdrop api
built with [Eden-Json-Rest v0.2.0](https://github.com/javinc/eden-json-rest)

### requirements
- PHP 5.6+
- Apache 2
- composer

### setup
- `sudo a2enmod headers rewrite setenvif`
- `sudo apt-get install php5-curl`
- `composer install`
- `mkdir upload && chmod 777 upload` for files
- point your VirtualHost to `/repo/Api/public`

### features
- JWT authentication
- Lazy CRUD
- RESTful Module
- File Upload
- Image Render
- CSV Tool
