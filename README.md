Basic Details:

The project was created on:

Operating System: Debian 8

Apache version: 2.4.25

PHP versions: 7.0, 7.1, 7.3

MySQL version: 5.7.24

The expected root path of the project is : /var/www/html/bootstrap_proj/

The project includes:

Javascript: 

Bootstrap 3
Reason: it comes with useful modals - the modals were used for the forms and nice css classes

jQuery 1.11.1 (+ jQuery datepicker)
Reason: Selectors, DOM manipulation functionalities, datepicker, plus Bootstrap requires jQuery

handlebars 1.3.0
Reason: Handlebars is very useful for displaying results from MySQL SELECT queries into HTML tables.

PHP:

none, pure vanilla

Important notice about apache:
The rewrite module needs to be enabled for this app to work correctly.

Important notice about PHP/mail():
As the application makes use of PHP's mail() function, please make sure that your settings in /etc/mail.rc and apache's php.ini are set up correctly.
If you are unsure, please check the included mail_instructions.txt for the necessary steps.

Additional Details about SQL:

There is an additional queries.sql file provided in the root directory of the project. 
This .sql file will create some stored procedures and execute rights for the webapp user.
The .sql needs to be executed with a root/dba user after the mysqldump (bootstrap_dump.sql) has been imported.

The schema for this project is called 'bootstrap' and the webapp user's username is also 'bootstrap'
The assumed database host for the project is 'localhost'

The user 'bootstrap' only has SELECT privileges  on the schema, and EXECUTE privileges on the specific stored procedures.
The user 'bootstrap_admin' has full privileges to the schema, and is the security definer of the stored procedures. The webapp does not directly interact with this db user.

Application 'structure':

bootstrap_proj/init/init.php is prepended using apache, which then loads all of the other files needed to give the application some base structure
All .php files in bootstrap_proj/base get loaded from init.php as well as all .php files in bootstrap_proj/app/api 

bootstrap_proj/base/api.php mimics the behaviour of an api, so one can make HTTP requests in the following format:

curl -s "Content-Type: application/json" -X POST 'http://localhost/bootstrap_proj/api/client/<name_of_endpoint_method>' -d '{"params":"etc..","controller":<name_of_endpoint_class>}'

A controller param needs to be given, as the param given in controller is mapped to a file (and therefore class) in bootstrap_proj/app/api and then the <name_of_endpoint_method> is mapped to a function if the api class exists

How to navigate the app:

Login with the credentials admin admin
If the message is Login Accepted... 
Feel free to navigate using the top navigation bar, or stay on the login screen.

Missing (but important) features:
CSRF tokens. 
Password encryption (they are now stored as plaintext).
Secure authentication.
