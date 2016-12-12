# Sample code - instructions

To install this code on the university servers, follow these instructions.

1. Download a zip file by clicking on the green 'Clone or download' button. Then select 'Download ZIP'.
2. When the ZIP file has downloaded, unzip it.
3. Edit the config/database.php file with your university database connection details.
4. Edit the config/config.php file to set the base_url value.
5. Use an FTP program to copy it into a directory in your public_html directory on the server
6. Set up your database tables on the university database server.  Copy the contents of create.sql.  Then open PHPMyAdmin for your university database account.  Click on the 'SQL' tab and paste the SQL code into the SQL editor.  Run the code to set up the tables.
7. Now check that the application runs by typing a URL such as https://YOUR_USER_ID.users.ecs.westminster.ac.uk/DIRNAME/index.php/Post/ (where YOUR_USER_ID is your university account name and DIRNAME is the name of the directory in your public_html directory that contains the application).

If you have any questions or problem, then contact me by email.

Good luck,

Simon
