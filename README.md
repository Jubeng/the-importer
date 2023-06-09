# The Importer

A simple website to import .xls and .xlsx and manage their data.
The Importer uses a queue to handle big data imports so that it can track the importing progress and manage the import process properly.

Technical Analysis: https://docs.google.com/document/d/1104JKfRIUSEjgJQgRbyPDMb0cF6id-pOlfhDc13Dqic/edit?usp=sharing

## For local setup:
1. Clone this repository: https://github.com/Jubeng/the-importer
2. Add the `.env` file that was sent through email.
2. Run `composer install`.
    - if an error occurred like `requires ext-gd * -> it is missing from your system. Install or enable PHP's gd extension.`, if you are using XAMPP, go to php.ini and uncomment `;extension=gd` and restart XAMPP then run `composer install` again.
3. Create a database for The Importer: `the_importer` for the main database and `the_importer_test` for testing database, you can change the database configuration on `config/database.php` and `.env` that was sent on the email.
4. after that, run `php artisan migrate:fresh` to create the tables.
5. after that, install the required npm packages, run `npm install && npm run build`.
5. To process the queue, run `php artisan queue:work`.
5. in the other terminal, to run the app run `php artisan serve`.
7. Go to the provided url. e.g.: http://127.0.0.1:8000/
8. To check the feature test, open another terminal and run `php artisan test`.

## To use The Importer:
1. In the welcome page, click Get Started button.
2. If you are not logged in, you can log in your account.
3. If you don't have an account, you can register
    - User Authentication whole process is provided by Laravel. I used Laravel/ui for this authentication.
4. After logging in, you will be redirected to the home page, where you can:
    - Import an .xlsx and .xls file.
        - Laravel Queue is used in this functionality to be able to track the importing progress of the user and to process big data properly.
    - Export the data you uploaded.
        - You can export all the data or export the current page
    - Manage the data you uploaded.
        - Edit and Delete functionality are available to the table.
5. During import, you will not be able to do anything other than wait for the import process to finish.
6. Pagination is provided to be able to display all the data of the user.
5. You can populate your database with the provided seeder using `php artisan db:seed --class=ImportSeeder`, this will insert 100 rows, you can change it based on your preference seeder is located in the `database/seeders/ImportSeeder.php`. Please note that you need to have an account first in the app before using the seeder.

