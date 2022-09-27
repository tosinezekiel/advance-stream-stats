# Streamlabs task
This application is aimed at providing stats to twitch streamers and more detailed analytics if they are subscribed to the service

# Requirements
```sh
    "php": "^8.0"
```
# Setting it up
These are the steps to get the app up and running. Once you're using the app.

1. Clone the repository
2. `composer install`
3. Rename `.env.example` to `.env` and run `php artisan key:generate`
4. Create a MySQL database. Add the database name to your `.env`
6. Run migrations: `php artisan migrate --seed`
9. Run `php artisan test`

## Request samples
```sh
Login
Endpoint : /api/auth/login
HTTP Verb: `POST`
{
	"email":"email@example.co",
    "password":"xxxxxxx"
}
```

```sh
Logout
Endpoint : /api/auth/logout
HTTP Verb: `POST`
```

```sh
Get braintree authorization token
Endpoint : /api/auth/token/generate
HTTP Verb: `GET`
```

```sh
Refresh Token
Endpoint : /api/auth/refreshtoken
HTTP Verb: `POST`
```

```sh
Get plans
Endpoint : /api/plans/
HTTP Verb: `GET`
```

```sh
Get subscriptions
Endpoint : /api/subscriptions/
HTTP Verb: `GET`
```


```sh
Subsribe to plan
Endpoint : /api/subscriptions/
HTTP Verb: `POST`
{
	"plan":{plan},
    "token": {token},
    "type": {type}
}
```

```sh
Cancel subscriptions
Endpoint : /api/subscriptions/
HTTP Verb: `DELETE`
```
