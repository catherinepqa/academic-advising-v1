Installation Process

1. git clone https://github.com/cathpoli/academic-advising-v1.git
2. composer install
3. create .env file in the root folder. Paste this

==================================================================================
APP_NAME=AcademicAdvising
APP_ENV=local
APP_KEY=base64:1NODg6NcNJEOq60F1xGtN1a4eJOuS4ny2iODFwy5gXI=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=academic-db
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=hauacademicadvising@gmail.com
MAIL_PASSWORD=dbuwrgixpylpppzg
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=1368178
PUSHER_APP_KEY=c7c292fecf19b1d3358d
PUSHER_APP_SECRET=24f55faeb0363d4da8d6
PUSHER_APP_CLUSTER=ap1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

ZOOM_API_URL="https://api.zoom.us/v2/"
ZOOM_API_KEY="6tB2KuWeSxmlu2ilTmXsjw"
ZOOM_API_SECRET="DFsVrlfkeppFyo18aktNlaMsEY9oRWrSvFqi"
==================================================================================

4. run this command in terminal. make sure that you are inside of the project folder
   php artisan key:generate

5. create database and named it academic-db.
6. run php artisan config:cache
7. run php artisan migrate:fresh
8. run composer dump-autoload
9. run php artisan db:seed
10. go to config/app.php, comment the line 56 then uncomment the line 55
11. go to app/Providers/AppServiceProvider.php, remove the content under the function boot.
12. run php artisan config:cache then run php artisan serve

You can access your site in http://127.0.0.1:8000/