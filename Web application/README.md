## Basic web application re-implementation (without JDBC)

Re-using the MySQL `MariaDB` database that was already created. 
(Database access will now be done using simple web server PHP script code.) 

## How to
Connect to DB and functions *SELECT/INSERT/UPDATE* have been packaged in the file ***MySqlDBHelper.class.php***

At first we should check if the account exists, and when **Withdraw** operation is in progress should Check if the balance is sufficient.

Check the input before any operation.