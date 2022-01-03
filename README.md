# Square1 Blog
This project features a Blogging website with a management system created using [Filament Admin](https://filamentadmin.com/).

## Requirements

This blog works with PHP v8 (tested locally with v8.0.13 using Valet). It will NOT work with previous PHP versions.

## Installation

To install this app, we will need to run the following commands after cloning the project:

```
composer install
```

After installing the composer dependencies, we might as well install the NPM dependencies to compile the assets:

```
npm install
```

And then run

```
npm run prod
```

### Creating the .env file

We can start by cloning the example provided by Laravel and generating a new key. Let's run the following command:

```
cp .env.example .env && php artisan key:generate
```

### Setting up the database
For simplicity, this project was run locally using a sqlite database. To set it up, we just need to set the `DB_CONNECTION` env variable to `sqlite`. Also, we need to delete the following .env variables:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Once this has been done, we can run the following command to create an empty database:

```
touch database/database.sqlite
```

### Run the migrations

Once the database has been created, we need to run the migrations:

```
php artisan migrate
```

And we should be able to see a beautiful empty blog like this one:

![image](https://user-images.githubusercontent.com/3358390/147911991-0d4e761a-e7f8-475a-b67a-f460bb234f02.png)

### Running the tests
To run the tests, we can run `php artisan test`. This will wipe the local database, so we better do it before we create any blog post :)

### Seeding the database
If needed, we can pre-create 500 blog posts by running `php artisan db:seed`


## Using the platform

Once installed, a user named "admin@square1.io" has been created. We can now sign up on the platform using our own email, and we will be led to the Filament admin panel. We can then navigate to the Blog post list on the left side pane:

![image](https://user-images.githubusercontent.com/3358390/147912384-1f3d4e61-f563-4ebb-bde8-2bb990aebb89.png)


If we now click on "New blog post" we can create a new one. Once we finish, we can click on "Create" and then go back to the Blog posts list:

![image](https://user-images.githubusercontent.com/3358390/147912478-5f35e36a-6f43-4976-8bcd-7ba70e05bad3.png)


As we are a user without admin rights, we will only see our own blog posts, and we cannot delete or edit them. If we wanted to test out the admin rights, we can run the following commands:

```
php artisan tinker

> $user = User::where('email', 'your@email.com')->firstOrFail();
> $user->role = 'admin';
> $user->save();
```

Now, we just need to refresh the page and we will see every post along with its owner, and we can edit and delete them:

![image](https://user-images.githubusercontent.com/3358390/147912681-687d4b61-44df-4133-b056-1fa8821aa153.png)

## Importation system
Finally, we can run the import script manually (although it's in a cronjob to run every 15min) to test it out. To do it, we first need to add the following variable to our .env file:

```
IMPORT_API_URL="https://sq1-api-test.herokuapp.com/posts"
```

Now, we just need to run `php artisan posts:import` to execute the importation system.

We can then see the imported posts from the admin panel by toggling the `From api` filter:

![image](https://user-images.githubusercontent.com/3358390/147913531-e9fba24b-69fd-415f-a733-72c615b1cc75.png)

