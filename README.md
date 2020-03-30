# DDKits Facebook Page/Group Poster

By Sam Elayyoub

### 1: Install Laravel 5.x

### 2: Composer require ddkits/ddkitsfacebook

### 3: Add DDkits within the service provider

    - ...
    'providers' => [
        /*
         * Application Service Providers...
         */
         ...
        Ddkits\Ddkitsfacebook\DdkitsfacebookServicesProvider::class,
    ],

### 4: In case of changing the theme

    - php artisan vendor:publish --tag="Ddkits\Ddkitsfacebook\DdkitsfacebookServiceProvider"

### 5: Migrate the DB table needed for this Plugin

    - php artisan migrate

### 6: Check the configs

    - ddkitsfacebook.php got some extra configs for major uses
    - Path: when calling the function "publishToPageNew($feed, $id)" posts on facebook would be linked to a path located in your site

### requirements:

    - Facebook app and Access_token https://developers.facebook.com/tools/explorer/

    - Posts/blogs or feeds fields
        - title
        - path (slug of your site if using ID please create the slug field and copy the id into it)
        - image (your post image)

### Usage: ../admin-ddkist-facebook (must be a logged in user, using Auth::id())

    - ...
    use Ddkits\Ddkitsfacebook;

    ...
    $feed ==> is the page or node you are posting link to facebook

    Ddkitsfacebook::publishToPageNew($feed, $id);

    - Can be used as schedule cron too.

Done!!!
