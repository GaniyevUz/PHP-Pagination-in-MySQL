<?php
/**
 * @author Jakhongir Ganiev (https://ganiyev.uz)
 * @license MIT
 * @date 5/11/2022 11:47 PM
 */
session_start();
$db = mysqli_connect("localhost", "root", "", "data") or die(mysqli_connect_error());

// search query using the keyword 'q'  and set it to session
if(isset($_POST['q']) && !empty($_POST['q'])) {
    $_SESSION['q'] = $_POST['q'];
}
// current page: if it isn't INT or lower than 1 it automatically sets 1 to this variable
$current_page = (int) ($_GET['page'] ?? 1);

// the number of items which are displayed in per page
$per_page = 10;

// basic query
$q = mysqli_real_escape_string($db, $_SESSION['q']);
$sql = "SELECT * FROM `people` WHERE `name` LIKE '%$q%' or `job` LIKE '%$q%' or `country` LIKE '%$q%'";

// the number of all the records which are equal to district_id
$all_records = mysqli_num_rows(mysqli_query($db, $sql));

// calculate the total pages using the function ceil()
$total_pages = ceil($all_records / $per_page);

// calculate the offset value of the next page
$offset = $per_page * ($current_page - 1);

// add limit and offset if page greater than 1
if($current_page > 1){
    $sql .= " LIMIT $per_page OFFSET $offset;";
}else{
    $sql .= " LIMIT $per_page;";
}
// send the final query to database and the second one calculates the number of all the rows which the query gave
$query = mysqli_query($db, $sql);
$num_rows = mysqli_num_rows($query);

// set custom table caption
if($all_records === 0 || $num_rows === 0){
    $table_caption = "<h5 class='text-danger'>the quarters that you are requesting are not fount from our database, please make sure your query is correct</h5>";
}else{
    $table_caption = $current_page.' from '.$total_pages;
}

// redirect the user to the first page if current page number will be greater than total pages
if ($current_page > $total_pages){
    header("location: search.php");
}

?>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="dist/css/style.css">
        <title>Document</title>
    </head>
    <body>
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-8 col-md-7">
                <table class="table table-hover table-dark" >
                    <caption><?=$table_caption?></caption>
                    <caption>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <?php
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
                                   // echo '... ';
                                    echo "<li class='page-item'><span  class='page-link'> ... </span></li>";
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
                        ?>
                            </ul>
                        </nav>
                    </caption>
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Job</th>
                        <th scope="col">Country</th>
                        <th scope="col">Birthday</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- dynamic table: in this case we use while loop in order to get all records in our database and show them quickly  -->
                    <?php $i=$offset; while($row = mysqli_fetch_assoc($query)):  $i++;?>
                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td class="text-light"><?php echo $row['name']; ?></td>
                            <td class="text-light"><?php echo $row['job']; ?></td>
                            <td class="text-light"><?php echo $row['country']; ?></td>
                            <td class="text-light"><?php echo $row['birthday']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <!-- Search Section-->
            <div class="col-sm">
                <form action="search.php" class="form" method="POST">
                    <input type="text" name="q" placeholder="Search here...">
                    <input type="submit" class="btn btn-secondary">
                </form>
                <a href="index.php" class="btn btn-secondary"> &larr; Back </a>
            </div>
        </div>
    </div>
    <script src="dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
<?php
