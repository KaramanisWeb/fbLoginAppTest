# Facebook Login App Test
Facebook login App Test for Social Sweethearts

- Facebook PHP SDK v5
- Laravel 5.5
- PHP 7.1.4
- MySQL

## Routes for the facebook app:
- Login Callback: https://www.yourWebsite.com/login/callback
- DeAuth Callback:  https://www.yourWebsite.com/deAuth
    
## Configure (.env file)

    Don't forget to copy .env.example to .env
    Generate a new Application key, configure the the rest of the .env file
    and run php artisan migrate:fresh to create the database tables. 

Add the following to your .end file:

    FACEBOOK_CLIENT_ID=Your Facebook App Client ID
    FACEBOOK_CLIENT_SECRET=Your Facebook App Client Secret
    FACEBOOK_CALLBACK=Your Facebook App Login Callback URL
 
 