# To Do Notes project - Lumen API

A simple To Do notes application that consists of APIs created using the Lumen framework and a UI built in Vue3.

## Installation Steps


1. Clone the repo: `git clone git@github.com:scrawlr/onboarding-empty.git`
2. Switch to the IN-49 branch: `git checkout IN-49`
3. Move into the onboarding-empty folder and execute `composer install`
4. Configure the environment file: `cp .env.example .env`

## Setup new Database and Seeding

1. First connect and create a database in PostGres on port 5433. For an example root user:
    `sudo -i -u root`
    `psql`
    `CREATE DATABASE todo_notes_app;`
2. Navigate back to the `onboarding-empty` folder on your system.
3. Create test data on the database: `php artisan migrate --seed`.

## Start the servers

1. Start the develop ment server for the APIs: `php -S localhost:8000 -t public`
2. Type `http://localhost:8000/` in a browser. You should see "ToDo Notes App" as the main title of the page.
3. To enable the UI for testing:
    `cd onboarding-empty/frontend` \
    `npm run serve`
4. Open the link `http://localhost:8080/` and you will see the UI where one can log in and use the To Do notes application.

## API Description and Testing

### Create a User ###
```
curl --location --request POST 'http://localhost:8000/api/users' \
--form 'name="name"' \
--form 'username="username"' \
--form 'password="password"'
```
**Response**: 201 Created
```
{
    "name": "name",
    "username": "username",
    "uuid": "11569e9a-8e2b-446b-bdcf-e19db22efa7d",
    "updated_at": "2021-09-07T16:02:33.000000Z",
    "created_at": "2021-09-07T16:02:33.000000Z"
}
```

### Authenticate a user ###
```
curl --location --request GET 'http://localhost:8000/api/users?username=username&password=password'
```
**Response**: 200 OK
```
{
    "status": "Successfully logged in",
    "api_key": "random string"
}
```

### Create a TODO note ### 
```
curl --location --request POST 'http://localhost:8000/api/todonotes' \
--header 'Authorization: {api_key}' \
--form 'content="some new content to add"'
```
**Response**: 201 Created
```
{
    "userid": "38eaafc2-651c-4062-aff5-b93d194e232d",
    "content": "some new content to add",
    "uuid": "c097e79e-10aa-4cef-94e8-1eaa444b86c8",
    "updated_at": "2021-09-07T16:14:48.000000Z",
    "created_at": "2021-09-07T16:14:48.000000Z"
}
```

### Delete a TODO Note ### 
**URL Parameter**:
{id} = ID of the To Do note to be deleted 
```
curl --location --request DELETE 'http://localhost:8000/api/todonotes/2e4fc89e-a2e1-4176-a011-b25541f17893' \
--header 'Authorization: {api_key}'
```
**Response**: 200 OK 
`To-Do Note Deleted Successfully`

### Mark TODO Note as complete ### 
**URL Parameter**:
{id} = ID of the To Do note to be marked as complete
```
curl --location --request PUT 'http://localhost:8000/api/todonotes/complete/4d6c3551-8b4f-4553-8632-1f7fef2cd0fb' \
--header 'Authorization: {api_key}'
```
**Response**: 200 OK
```
{
    "uuid": "c097e79e-10aa-4cef-94e8-1eaa444b86c8",
    "userid": "38eaafc2-651c-4062-aff5-b93d194e232d",
    "content": "Design new feature again",
    "completion_time": "2021-09-07T16:16:00.235571Z",
    "created_at": "2021-09-07T16:14:48.000000Z",
    "updated_at": "2021-09-07T16:16:00.000000Z"
}
```

### Mark TODO Note as incomplete ### 
**URL Parameter**:
{id} = ID of the To Do note to be marked as incomplete
```
curl --location --request PUT 'http://localhost:8000/api/todonotes/incomplete/4d6c3551-8b4f-4553-8632-1f7fef2cd0fc' \
--header 'Authorization: {api_key}'
```
**Response**: 200 OK
```
{
    "uuid": "c097e79e-10aa-4cef-94e8-1eaa444b86c8",
    "userid": "38eaafc2-651c-4062-aff5-b93d194e232d",
    "content": "Design new feature again",
    "completion_time": null,
    "created_at": "2021-09-07T16:14:48.000000Z",
    "updated_at": "2021-09-07T16:16:21.000000Z"
}
```

### List all TODO notes for logged in user ### 
```
curl --location --request GET 'http://localhost:8000/api/todonotes/' \
--header 'Authorization: {api_key}'
```
**Response**: 200 OK
```
\[ List of all available todo notes for logged in user \]
```


### List all TODO notes for arbitrary user ###  
**URL Parameter**:
{userid} = User ID of user whose notes to be viewed
```
curl --location --request GET 'http://localhost:8000/api/todonotes/199c6377-16c2-44ab-9f7c-3fa8b4602ff1' \
--header 'Authorization: {api_key}'
```
**Response**: 200 OK
```
\[ List of all available todo notes for given user \]
```
