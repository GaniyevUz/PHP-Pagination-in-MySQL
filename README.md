# PHP-Pagination-in-MySQL

**A simple PHP pagination example using MySQL with fully written comments.**

**Simple PHP Pagination With MySQL **
--

[live  DEMO version is here](http://jahongir.ga/pagination-example/)

**Database Credentials**

You will need to change some variable values in the Class, that represent those of your own database. Change the following -

```php
	$host = 'localhost';        // Change as required
	$username = 'username';     // Change as required
	$password = 'password';     // Change as required
	$database = 'database';     // Change as required

```

**Test MySQL**

Start by creating a test table in your database -

```mysql
CREATE TABLE IF NOT EXISTS people (
	id int(11) NOT NULL AUTO_INCREMENT, 
	name VARCHAR(50) NOT NULL, 
	job VARCHAR(50) NOT NULL, 
	country VARCHAR(50) NOT NULL, 
	brithday DATE,
	PRIMARY KEY (id)
); 

insert into people (id, name, job, country, brithday) values (1, 'Buiron', 'Systems Administrator II', 'China', '30-Oct-2008'); 
insert into people (id, name, job, country, brithday) values (2, 'Ursulina', 'Accountant IV', 'Chile', '23-Sep-2002'); 
insert into people (id, name, job, country, brithday) values (3, 'Meriel', 'Human Resources Assistant II', 'Indonesia', '06-Apr-2005'); 
insert into people (id, name, job, country, brithday) values (4, 'Wilton', 'Computer Systems Analyst II', 'Sweden', '11-May-2005'); 
insert into people (id, name, job, country, brithday) values (5, 'Gwenora', 'Computer Systems Analyst I', 'Czech Republic', '10-Dec-2006'); 
```

**Create Example**

Use the following code to select * rows from the databse using this class

```php
<?php
// current page: if it isn't INT or lower than 1 it automatically sets 1 to this variable
$current_page = (int) ($_GET['page'] ?? 1);

// the number of items which are displayed in per page
$per_page = 10;

// the number of all the records which are equal to district_id
$all_records = mysqli_num_rows(mysqli_query($db, "SELECT * FROM `people`"));

// calculate the total pages using the function ceil()
$total_pages = ceil($all_records / $per_page);

// calculate the offset value of the next page
$offset = $per_page * ($current_page - 1);

            // if current page will be greater than 1 it shows <- Previous text otherwise it wil be changed to disabled
        if($current_page > 1){
            echo "<li class='page-item'><a class='page-link'  href='?page=" . ($current_page - 1) . "'>&laquo; Previous</a></li>";
        }else{
            echo "<li class='page-item disabled'><span class='page-link'>&laquo; Previous</span></li>";
        }
                $win = 2; //window size
                $gap = false; // default value should be false
        for ($i=1; $i <= $total_pages; $i++){
             //skipping long paginations and showing 3 buttons from first and last row and showing 2 buttons before the current page and after that as well
            if(abs($i - $current_page) > $win){
                if(!$gap){
                    $gap = true;
                }
                    continue;

            }
           // indicating the current page and highlighting it
            if ($current_page == $i){
                echo "<li class='page-item active'><a  class='page-link' href='?page=" . ($i) . "'> $i </a></li>";
             }else{
                echo "<li class='page-item'><a  class='page-link' href='?page=" . ($i) . "'> $i </a></li>";
            }
                $gap = false;
        }
           // if current page will be lower than total pages it shows Next -> text otherwise it wil be changed to disabled
        if($current_page < $total_pages){
            echo "<li class='page-item'><a class='page-link'  href='?page=" . ($current_page + 1) . "'>Next &raquo</a></li>";
        }else {
            echo "<li class='page-item disabled'><span class='page-link'>Next &raquo;</span></li>";
        }
```
