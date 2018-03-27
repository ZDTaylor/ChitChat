# Chit Chat

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
 - We will create many smaller .js files for other AngularJS functionality as we go.

#### Note
In the fonts, css, and js folders, there are **vendor folders**.  These contain the outside resources that we are using and **should not be edited!!**

## On our list of things to do first:
 - I will finish creating the fromework for the front-end and the file structure
 - Anubhaw and Dylan should implement the four classes.  Ask me for any help in constructing SQL queries or specifics on what exactly different methods should do.  Start with the User and Message classes as UserManager and Messenger will depend on them.  Please make sure to use sanitize_input (May be more important in the api code, but we'll see.  It's stored in the lib folder, so you can require it and use it.) and use prepared statements for SQL queries (I can throw up some demo code on how to use them.).
 - Austin should read up on [Bootstrap](https://getbootstrap.com/docs/3.3/) and [AngularJS](https://angularjs.org/).  We can procede with the front-end as soon as I finish the groundwork.  Once you understand Bootstrap, we will technically be using [Angular-UI](https://angular-ui.github.io/bootstrap/), which is just an AngularJS-compliant framework for the js part of Bootstrap.  Same drag-n-drop methodology.

## Resources
 - [Bootstrap](https://getbootstrap.com/docs/3.3/) (css framework)
 - [AngularJS](https://angularjs.org/) (js framework for dynamic front-end)
 - [Angular-UI](https://angular-ui.github.io/bootstrap/) (Bootstrap for AngularJS)
 - [PHP REST API tutorial](https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html) (info for how to make PHP communicate with js)