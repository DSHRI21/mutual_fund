# mutual_fund
MUTUAL FUND CALCULATOR 
Framework : CI
Developer : Dhanshri Nandanwar

Prerequisites
1. config.php [applications/config/config.php]
	Change base url path as per your local url (if required)
	$config['base_url'] = 'http://localhost/mutual_fund';
2. database.php [applications/config/database.php]
	Change database details (if required)
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'mutual_fund'
3. Import Database [database/mutual_fund]
4. Set the cron for "update_data" function in Mf_calculator controller
	This function updates our database with the latest fund values.


NOTE : 
A] If you dont want to import database, create yours and update the table_name in 
Mf_calculator model wherever needed and just run the "update_data" function, 
this setup url is "http://localhost/mutual_fund/Mf_calculator/update_data" your can differ.
It will take time but imports all the data from given API.

B] I am not sure about the Current Amount Calculation Formula
I have used as following

Total units = Invested Amount/Nav at Invested Date

Current Nav = Nav for today date (if not found then selected for max available date)

Current Amount = Total units*Current Nav