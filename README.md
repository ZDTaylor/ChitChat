# Chit Chat

---

## Folder Structure

```bash
├── lib
│   ├── login.php
│   ├── Message.php
│   ├── Messenger.php
│   ├── sanitize_input.php
│   ├── UserManager.php
│   ├── User.php
│   └── [Other helper functionns].php
├── api
│   ├── login.php
│   └── [Other api nodes].php
├── css
│   ├── app.css
│   └── vendor
├── fonts
│   └── vendor
├── js
│   ├── app.js
│   ├── app.chatCtrl.js
│   ├── app.navCtrl.js
│   ├── app.[Other controllers].js
│   └── vendor
└── index.php
```

### lib folder
Contains the database login information and the php class definitions, as well as any helper functions we want to write and leave here instead of replicating them in multiple files.
 - We will need to complete the class definitions.

### api folder
Will contain many individual php files that will act as the endpoints for the front-end to connect to.
 - For instance, login.php will be all of the code required to log a user in.  It will make use of the files in the lib folder to do this.  It will respond to the front end by returning a json_encode()'d response.

### fonts folder
Contains fonts that we will use.  Currently only has Glyphicons which will be used by Bootstrap.

### css folder
Contains stylesheets for making the front-end look pretty.
 - app.css is any custom css used for Chit Chat.

### js folder
Contains the JavaScript that will run on the front-end for functionality, prettiness, and connecting with the back-end.
 - app.js is the definition of our AngularJS app.
 - app.[x]Ctrl.js is the definition of a controller, which will be in charge of connecting to the PHP api and setting variables that will be displayed on the page.
    - We will probably create quite a few controllers to keep functionality separated.

#### Note
In the fonts, css, and js folders, there are **vendor folders**.  These contain the outside resources that we are using and **should not be edited!!**

---

## Logical Structure

### Backend
 - The backend will serve as an api for the frontend to connect to.
    - This means that only the index.php page will return any HTML.  The other php pages will act more like normal code that just takes in input, does something, and returns an answer.
 - All of the class definitions, important helper functions, and database login info will be stored in the lib folder.
 - All of the php pages that will actually do the work will be stored in the api folder.

### Frontend
 - The frontend will be the application that the user can see.
 - The css, fonts, and js folders store the assets that index.php will use to display everything to the user.
 - Bootstap will supply our css framework and allow for drag-n-drop components
 - UI Bootstrap will replace some of the Bootstrap components with ones that work with AngularJS
 - AngularJS will provide our js framework and allow the frontend to be dynamic

---

## On our list of things to do first:
 - I will finish creating the fromework for the front-end and the file structure
 - Anubhaw and Dylan should implement the four classes.  Ask me for any help in constructing SQL queries or specifics on what exactly different methods should do.  Start with the User and Message classes as UserManager and Messenger will depend on them.  Please make sure to use sanitize_input (May be more important in the api code, but we'll see.  It's stored in the lib folder, so you can require it and use it.) and use prepared statements for SQL queries (I can throw up some demo code on how to use them.).
 - Austin should read up on [Bootstrap](https://getbootstrap.com/docs/3.3/) and [AngularJS](https://angularjs.org/).  We can procede with the front-end as soon as I finish the groundwork.  Once you understand Bootstrap, we will technically be using [Angular-UI](https://angular-ui.github.io/bootstrap/), which is just an AngularJS-compliant framework for the js part of Bootstrap.  Same drag-n-drop methodology.

---

## Backend
### Example PHP api page (login.php):
```php
<?php
    require_once "../lib/UserManager.php";
    require_once "../lib/sanitize_input.php";
    header('Content-type: application/json');

    $userManager = new UserManager();
    $response = false;
    // Check for variables that got posted, and use userManager to try to login.

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = sanitize_input($_POST["username"]);
        $password = sanitize_input($_POST["password"]);

        // $response should be a user object or false if there was an issue
        $user = $userManager->login($username, $password);

        if ($user != false) {
            session_start();
            $_SESSION["user"] = $user;
            $response = true;
        }

    }

    // Use json_encode() to return whatever we decide needs to be returned
    // This will ensure that the js on the client side will get data it can understand
    echo json_encode($response);
?>
```
### Reasoning:
```php
    require_once "../lib/UserManager.php";
    require_once "../lib/sanitize_input.php";
```
This is where we will pull in any classes and helper functions we need.

```php
    header('Content-type: application/json');
```
This is how we will let the front end know that we are returning JSON instead of HTML.

```php
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = sanitize_input($_POST["username"]);
        $password = sanitize_input($_POST["password"]);
```
In this case, we will check to make sure that we received a username and password and sanitize them before try to login.

```php
        // $response should be a user object or false if there was an issue
        $user = $userManager->login($username, $password);

        if ($user != false) {
            session_start();
            $_SESSION["user"] = $user;
            $response = true;
        }
```
Then we will try to login.  If successful, we will have a user object.  If unsuccessful, we will receive false.
If successful, we will start a session with the user as the only stored variable, and we will set $response to true.

```php
    echo json_encode($response);
```
Finally, we use json_encode() to correctly encode our response and then use echo to actually write the response.

### lib class files
The class files shouldn't need a large example, they will just be plain PHP classes implemented based on our design document.

---

## Frontend

TODO

---

## Resources
 - [Bootstrap](https://getbootstrap.com/docs/3.3/) (css framework)
 - [AngularJS](https://angularjs.org/) (js framework for dynamic front-end)
 - [Angular-UI](https://angular-ui.github.io/bootstrap/) (Bootstrap for AngularJS)
 - [PHP REST API tutorial](https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html) (info for how to make PHP communicate with js)