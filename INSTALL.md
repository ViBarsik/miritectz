LogicalQuiz Web Aplication
============================
### Required software

- Nginx 1.10+
- PHP 7.0+
- PHP-FPM 7.0+
- MySql 5.7
- [Composer](http://getcomposer.org/)

INSTALLATION
------------

### 1.Clone project and install packages
Clone a project from the repository

In Nginx site config file correct param:
~~~
...
root  /path/to/project/web;
...
~~~

Open the folder into which the project was cloned and run custom cmd (if you OS is Windows)
Input next command:

~~~
composer install
~~~


### 2.Create database
When the installation of packages is completed, it is necessary to prepare the Mysql database for the project. 
You must connect to your server in superuser privileges
~~~
mysql --user={root_user} --password={root_password} --host={localhost or ip}
~~~

After a successful connection, you need to create a database:
~~~
CREATE DATABASE `miritec` CHARACTER SET utf8 COLLATE utf8_general_ci;
~~~

and database user with grant privileges by this database:
~~~
GRANT ALL PRIVILEGES ON miritec.* TO miritec@localhost IDENTIFIED BY 'xxxzzzaaawww' WITH GRANT OPTION;
~~~

`miritec@localhost` can be replaced by `miritec@'%'` if the database located on remote server

After executing the previous command, you should check the connection to the created database. 
To do this, run the following command in the console:

logout root
~~~
quit;  
~~~
sign in as created user:
~~~
mysql --user=miritec --password=xxxzzzaaawww --host=localhost
~~~
If login is success, input:
~~~
quit; 
~~~
Else, carefully read the message that sent the server Lusk in the console and eliminate the errors.

If you did not correct the user and database creation commands and the process completed successfully, the following configurations can be 
left unattended in the config file (/path/to/project/config/params.php):

    'mysql_host' => 'localhost',
    'mysql_user' => 'miritec',
    'mysql_password' => 'xxxzzzaaawww',
    'mysql_database' => 'miritec',



This completes the installation. If everything was done correctly in the previous steps and the necessary requirements are met, 
then the application will start working successfully. If you get errors after installation or something does not work out, 
you can contact the author by email `ilativ.oknepilip@gmail.com`


