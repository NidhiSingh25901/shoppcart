<?php
/************************ Admin Draft Page ********************/
function draft_product() {
if(isset($_POST['publish_draft']) && empty($_POST['publish_draft']) === false) {
$product_title          = escape_string($_POST['product_title']);
$srt                    = (str_replace(" ","_","{$product_title}"));
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = $_FILES['file']['tmp_name'];
if(empty($product_image)) {
$get_pic = query("SELECT pimage FROM draft WHERE draft_id =" .escape_string($_GET['id']). " ");
confirm($get_pic);
while($pic = fetch_array($get_pic)) {
$product_image = $pic['pimage'];
$uploads_dir = uploads_dir($product_category_id );
$products_image = basename($product_image);
$uploads_dirs = $uploads_dir. $products_image;
rename($product_image  ,$uploads_dirs);
    }
}else{
$uploads_dir = uploads_dir($product_category_id );
$uploads_dirs = $uploads_dir. $product_image;
move_uploaded_file($image_temp_location  ,$uploads_dirs);
}
$send_draft_query = query("INSERT INTO products(pname, product_cat_id, pprice, pdesc, pquant, pimage) VALUES('{$srt}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$product_quantity}', '{$uploads_dirs}')");
confirm($send_draft_query);
$log = query("INSERT INTO log (pname, moderate_name) SELECT pname, moderate_name FROM draft WHERE draft_id = " . escape_string($_GET['id']) . "  ");
confirm($log);
$draft = query("DELETE FROM draft WHERE draft_id = " . escape_string($_GET['id']) . " ");
confirm($draft);
redirect("admin?draft");
set_message("New Product was Added <a href=".$srt." target=\"_blank\">View Product</a>");
        }
}


function get_drafts_in_admin(){
$query = query(" SELECT * FROM draft ");
confirm($query);
while($row = fetch_array($query)) {
$category = show_product_category_title($row['product_cat_id']);
$srt = strtoupper(str_replace("_"," ","{$row['pname']}"));
$product_image = $row['pimage'];
$product = <<<DELIMETER
        <tr>
            <td>{$row['draft_id']}</td>
            <td>{$srt}</a><br>
             <img width='100' src="$product_image" alt="">
            </td>
            <td>{$category}</td>
            <td>{$row['pprice']}</td>
            <td>{$row['pquant']}</td>
            <td>{$row['moderate_name']}</td>
             <td><a class="btn btn-warning" href="/admin?edit_draft&id={$row['draft_id']}"><span class="glyphicon glyphicon glyphicon-pencil"></span></a></td>
             <td><a class="btn btn-danger" href="html/php/includes/admin/delete_draft?id={$row['draft_id']}&i={$row['pimage']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
echo $product;
        }
}
/***************************Add Products in Moderate********************/
function add_product_moderate() {
global $user_data;    
if(isset($_POST['draft']) && empty($_POST['draft']) === false) {
$product_title          = escape_string($_POST['product_title']);
$srt                    = (str_replace(" ","_","{$product_title}"));
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = $_FILES['file']['tmp_name'];
$uploads_dir = 'images/uploads/';
$uploads_dirs = $uploads_dir. $product_image;
move_uploaded_file($image_temp_location  ,$uploads_dirs);
$query = query("INSERT INTO draft(pname, product_cat_id, pprice, pdesc, pquant, pimage, moderate_name) VALUES('{$srt}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$product_quantity}', '{$uploads_dirs}', '{$user_data['username']}')");
confirm($query);
set_message("New Product submited, It will be published once reviewed by Admin...!!!");

        }
}
/***************************updating product code Moderate***********************/
function update_product_moderate() {
if(isset($_POST['update']) && empty($_POST['update']) === false) {
$product_title          = escape_string($_POST['product_title']);
$srt                    = (str_replace(" ","_","{$product_title}"));
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = $_FILES['file']['tmp_name'];
if(empty($product_image)) {
$get_pic = query("SELECT pimage FROM products WHERE p_id =" .escape_string($_GET['id']). " ");
confirm($get_pic);
while($pic = fetch_array($get_pic)) {
$product_image = $pic['pimage'];
$uploads_dirs = $product_image;
    }
}else{
$uploads_dir = uploads_dir($product_category_id );
$uploads_dirs = $uploads_dir. $product_image;
move_uploaded_file($image_temp_location  ,$uploads_dirs);
}
$query = "UPDATE products SET ";
$query .= "pname                    = '{$srt}'                  , ";
$query .= "product_cat_id           = '{$product_category_id}'  , ";
$query .= "pprice                   = '{$product_price}'        , ";
$query .= "pdesc                    = '{$product_description}'  , ";
$query .= "pquant                   = '{$product_quantity}'     , ";
$query .= "pimage                   = '{$uploads_dirs}'           ";
$query .= "WHERE p_id=" . escape_string($_GET['id']);
$send_update_query = query($query);
confirm($query);
redirect("moderate?products");
set_message("Product has been updated <a href=".$srt." target=\"_blank\">View Product</a>");
        }
}
/*************************Reports in Moderate ********************/
function get_reports_moderate(){
$query = query(" SELECT * FROM reports");
confirm($query);
while($row = fetch_array($query)) {
$report = <<<DELIMETER
        <tr>
             <td>{$row['report_id']}</td>
            <td>{$row['p_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['pprice']}</td>
            <td>{$row['pname']}
            <td>{$row['pquant']}</td>
        </tr>
DELIMETER;
echo $report;
        }
}
/************************ Moderate Products Page ********************/
function get_products_in_moderate(){
$query = query(" SELECT * FROM products  ORDER BY p_id DESC");
confirm($query);
while($row = fetch_array($query)) {
$category = show_product_category_title($row['product_cat_id']);
$srt = strtoupper(str_replace("_"," ","{$row['pname']}"));
$product_image = $row['pimage'];
$product = <<<DELIMETER
        <tr>
            <td>{$row['p_id']}</td>
            <td><a href="{$row['pname']}" target="_blank">{$srt}</a><br>
             <img width='100' src="$product_image" alt="">
            </td>
            <td>{$category}</td>
            <td>{$row['pprice']}</td>
            <td>{$row['pquant']}</td>
             <td><a class="btn btn-warning" href="/moderate?edit_product&id={$row['p_id']}"><span class="glyphicon glyphicon glyphicon-pencil"></span></a></td>
        </tr>
DELIMETER;
echo $product;
        }
}
function show_product_category_title_moderate($product_category_id){
$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);
while($category_row = fetch_array($category_query)) {
return $category_row['cat_title'];
}
}
function show_product_category_main_id_moderate($product_category_id){
$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);
while($category_row = fetch_array($category_query)) {
return $category_row['cat_main_id'];
}
}

function display_orders_moderate(){
$query = query("SELECT * FROM orders");
confirm($query);
while($row = fetch_array($query)) {
$orders = <<<DELIMETER
<tr>
    <td>{$row['order_id']}</td>
    <td>{$row['order_amount']}</td>
    <td>{$row['order_transaction']}</td>
    <td>{$row['order_currency']}</td>
    <td>{$row['order_status']}</td>
</tr>
DELIMETER;
echo $orders;
    }
}
/*************************Reports in admin ********************/
function get_reports(){
$query = query(" SELECT * FROM reports");
confirm($query);
while($row = fetch_array($query)) {
$report = <<<DELIMETER
        <tr>
             <td>{$row['report_id']}</td>
            <td>{$row['p_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['pprice']}</td>
            <td>{$row['pname']}
            <td>{$row['pquant']}</td>
            <td><a class="btn btn-danger" href="html/php/includes/admin/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
echo $report;
        }
}
/*************************Categories in admin ********************/
function show_categories_in_admin() {
$category_query = query("SELECT * FROM categories");
confirm($category_query);
while($row = fetch_array($category_query)) {
$cat_id = $row['cat_id'];
$cat_title = $row['cat_title'];
$cat_main_id = $row['cat_main_id'];
$category = <<<DELIMETER
<tr>
    <td>{$cat_id}</td>
    <td>{$cat_title}</td>
    <td>{$cat_main_id}</td>
    <td><a class="btn btn-danger" href="html/php/includes/admin/delete_category?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
DELIMETER;
echo $category;
    }
}
function add_category() {
if(isset($_POST['add_category'])) {
$cat_title = escape_string($_POST['cat_title']);
$cat_main_id = escape_string((int)$_POST['cat_main_id']);
if(empty($cat_title) || $cat_title == " " && empty($cat_main_id) || $cat_main_id == " ") {
echo "<p class='bg-danger'>THIS CANNOT BE EMPTY</p>";
} else {
$insert_cat = query("INSERT INTO categories(cat_title,cat_main_id) VALUES('{$cat_title}','{$cat_main_id}') ");
confirm($insert_cat);
set_message("Category Created");
    }
    }
}
/***************************updating product code Admin***********************/
function update_product() {
if(isset($_POST['update']) && empty($_POST['update']) === false) {
$product_title          = escape_string($_POST['product_title']);
$srt                    = (str_replace(" ","_","{$product_title}"));
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = $_FILES['file']['tmp_name'];
if(empty($product_image)) {
$get_pic = query("SELECT pimage FROM products WHERE p_id =" .escape_string($_GET['id']). " ");
confirm($get_pic);
while($pic = fetch_array($get_pic)) {
$product_image = $pic['pimage'];
$uploads_dirs = $product_image;
    }
}else{
$uploads_dir = uploads_dir($product_category_id );
$uploads_dirs = $uploads_dir. $product_image;
move_uploaded_file($image_temp_location  ,$uploads_dirs);
}
$query = "UPDATE products SET ";
$query .= "pname                    = '{$srt}'                  , ";
$query .= "product_cat_id           = '{$product_category_id}'  , ";
$query .= "pprice                   = '{$product_price}'        , ";
$query .= "pdesc                    = '{$product_description}'  , ";
$query .= "pquant                   = '{$product_quantity}'     , ";
$query .= "pimage                   = '{$uploads_dirs}'           ";
$query .= "WHERE p_id=" . escape_string($_GET['id']);
$send_update_query = query($query);
confirm($query);
redirect("admin?products");
set_message("Product has been updated <a href=".$srt." target=\"_blank\">View Product</a>");
        }
}
/***************************Add Products in admin********************/
function add_product() {
if(isset($_POST['publish']) && empty($_POST['publish']) === false) {
$product_title          = escape_string($_POST['product_title']);
$srt                    = (str_replace(" ","_","{$product_title}"));
$product_category_id    = escape_string($_POST['product_category_id']);
$product_price          = escape_string($_POST['product_price']);
$product_description    = escape_string($_POST['product_description']);
$product_quantity       = escape_string($_POST['product_quantity']);
$product_image          = escape_string($_FILES['file']['name']);
$image_temp_location    = $_FILES['file']['tmp_name'];
$uploads_dir = uploads_dir($product_category_id );
$uploads_dirs = $uploads_dir. $product_image;
move_uploaded_file($image_temp_location  ,$uploads_dirs);
$query = query("INSERT INTO products(pname, product_cat_id, pprice, pdesc, pquant, pimage) VALUES('{$srt}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$product_quantity}', '{$uploads_dirs}')");
$last_id = last_id();
confirm($query);
set_message("New Product was Added <a href=".$product_title." target=\"_blank\">View Product</a>");

        }
}
function show_categories_add_product_page(){
$query = query("SELECT * FROM categories");
confirm($query);
while($row = fetch_array($query)) {
$categories_options = <<<DELIMETER
 <option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;
echo $categories_options;
     }
}
/************************ Admin Products Page ********************/
function get_products_in_admin(){
$query = query(" SELECT * FROM products  ORDER BY p_id DESC");
confirm($query);
while($row = fetch_array($query)) {
$category = show_product_category_title($row['product_cat_id']);
$srt = strtoupper(str_replace("_"," ","{$row['pname']}"));
$product_image = $row['pimage'];
$product = <<<DELIMETER
        <tr>
            <td>{$row['p_id']}</td>
            <td><a href="{$row['pname']}" target="_blank">{$srt}</a><br>
             <img width='100' src="$product_image" alt="">
            </td>
            <td>{$category}</td>
            <td>{$row['pprice']}</td>
            <td>{$row['pquant']}</td>
             <td><a class="btn btn-warning" href="/admin?edit_product&id={$row['p_id']}"><span class="glyphicon glyphicon glyphicon-pencil"></span></a></td>
             <td><a class="btn btn-danger" href="html/php/includes/admin/delete_product.php?id={$row['p_id']}&i={$row['pimage']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
echo $product;
        }
}
function show_product_category_title($product_category_id){
$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);
while($category_row = fetch_array($category_query)) {
return $category_row['cat_title'];
}
}
function show_product_category_main_id($product_category_id){
$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);
while($category_row = fetch_array($category_query)) {
return $category_row['cat_main_id'];
}
}

function display_orders(){
$query = query("SELECT * FROM orders");
confirm($query);
while($row = fetch_array($query)) {
$orders = <<<DELIMETER
<tr>
    <td>{$row['order_id']}</td>
    <td>{$row['order_amount']}</td>
    <td>{$row['order_transaction']}</td>
    <td>{$row['order_currency']}</td>
    <td>{$row['order_status']}</td>
    <td><a class="btn btn-danger" href="html/php/includes/admin/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
DELIMETER;
echo $orders;
    }
}
/*********************************************General Product Functions******************/
function last_id(){
global $connection;
return mysqli_insert_id($connection);
}
function order_count(){
    return mysql_result(mysql_query("SELECT COUNT(`order_id`) FROM `orders`"), 0);
}
function product_count(){
    return mysql_result(mysql_query("SELECT COUNT(`p_id`) FROM `products`"), 0);
}
function draft_count(){
    return mysql_result(mysql_query("SELECT COUNT(`draft_id`) FROM `draft`"), 0);
}
function report_count(){
    return mysql_result(mysql_query("SELECT COUNT(`report_id`) FROM `reports`"), 0);
}
function log_count($username){
    return mysql_result(mysql_query("SELECT COUNT(`log_id`) FROM `log` WHERE `moderate_name` = '$username'"), 0);
}
/*********************************************Category Functions******************/
function get_categories($cat_main_id){
$query = query("SELECT * FROM categories WHERE `cat_main_id` = $cat_main_id ");
confirm($query);
while($row = fetch_array($query)) {
$srt = strtoupper(str_replace(" ","_","{$row['cat_title']}"));
echo '<li class="cp_btn"><a  class="active"onclick="return false" href="subr?id='.$row['cat_id'].'&names='.$srt.'">'.$row['cat_title'].' </a></li>';
     }
}
function cat_count(){
    return mysql_result(mysql_query("SELECT COUNT(`cat_id`) FROM `categories`"), 0);
}
/*********************************************product Functions******************/
function add(){
    echo "hi";
}
function get_products() {
$query = query(" SELECT COUNT(p_id) FROM products;");
confirm($query);
$num = mysqli_fetch_row($query);
$nums = $num[0];
$page_rows = 9;
$last = ceil($nums/$page_rows);
if($last < 1){
    $last = 1;
}
$pagenum =1;
if(isset($_GET['pn'])){
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) {
    $pagenum = 1;
}elseif ($pagenum > $last) {
    $pagenum = $last;
}
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows . ',' .$page_rows;
$querys = query(" SELECT * FROM products ORDER BY rand() $limit ;");
confirm($querys);
$textline1 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
$paginationCtrls .= '<div class="text-center"><nav><ul class="pagination">';
if ($last != 1) {
    if ($pagenum > 1) {
        $previous = $pagenum - 1;
        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" ><i class="fa fa-chevron-left"></i></a></li>';
        for ($i=$pagenum-4; $i < $pagenum ; $i++) { 
            if ($i>0) {
                $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
            }
            
        }

    }
    $paginationCtrls .= '<li class="active"><a href="#">'.$pagenum.'</a></li>';
    for ($i=$pagenum+1; $i <= $last ; $i++) { 
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
            if ($i>$pagenum+4) {
                break;
            }
            
        }
        if ($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'"><i class="fa  fa-chevron-right"></i></a></li>';
        }
}
$paginationCtrls .= '</nav></ul></div>';
echo '<h2 class="text-center">'.$textline1.'</h2>';
while($row = fetch_array($querys)) {
$srt = strtoupper(str_replace("_"," ","{$row['pname']}"));
$product1 = <<<DELIMETER
<a class="link" href="{$row['pname']}">
<div class="col-sm-4 col-xs-6">
    <div class="thumbnail">
    <div class="product-image-wrapper">
        <div class="single-products">
                <div class="productinfo text-center">
                    <img src="{$row['pimage']}"  alt="" />
                    <h2>&#8377; {$row['pprice']}</h2>
                    <p>$srt</p>
                    <div class="row">
                        <div class="col-sm-6">
DELIMETER;
$product2 =<<<DELIMETER

                        <div class="col-sm-6">
                            <a href="{$row['pname']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-info-circle"></i>Details</a>
                        </div>
                    </div>    
                </div>
        </div>
        <div class="choose">
            <ul class="nav nav-pills nav-justified">
                <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
            </ul>
        </div>
    </div>
</div>
</div>
</a>
DELIMETER;
$s = $row['p_id'];
echo $product1;
if (isset($_SESSION['product_'.$s])) {
    $add = <<<DELIMETER
   <a href="cart?add={$row['p_id']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-cart-plus"></i>Added to cart</a>
                        </div>
DELIMETER;
echo $add;
}else{
   $add = <<<DELIMETER
   <a href="cart?add={$row['p_id']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                        </div>
DELIMETER;
echo $add;
}
echo $product2;
		}
    echo $paginationCtrls;    
}
function product_exits($productname){
    $productname = sanitize($productname);
    return (mysql_result(mysql_query("SELECT COUNT(`p_id`) FROM `products` WHERE `pname` = '$productname'"), 0) == 1 ) ? true : false;
}
function product_data($p_id){
    $data = array();
    $p_id = (int)$p_id;
    $func_num_args = func_num_args();
    $func_get_args = func_get_args();
    if ($func_get_args > 1) {
        unset($func_get_args[0]);
        $fields = '`'. implode('`, `', $func_get_args). '`';
        $data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `products` WHERE `p_id` = $p_id" ));
        return $data;
    }
}
function product_id_from_productname($productname){
    $productname = sanitize($productname);
    return mysql_result(mysql_query("SELECT `p_id` FROM `products` WHERE `pname`= '$productname' "), 0 , 'p_id');
}
/*********************************************category product Functions******************/
function get_sub($named,$id) {
$query = query(" SELECT COUNT(p_id) FROM products WHERE product_cat_id = '.$id.';");
confirm($query);
$num = mysqli_fetch_row($query);
$nums = $num[0];
$page_rows = 9;
$last = ceil($nums/$page_rows);
if($last < 1){
    $last = 1;
}
$pagenum =1;
if(isset($_GET['pn'])){
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) {
    $pagenum = 1;
}elseif ($pagenum > $last) {
    $pagenum = $last;
}
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows . ',' .$page_rows;
$querys = query(" SELECT * FROM products $limit ;");
confirm($querys);
$textline1 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
$paginationCtrls .= '<div class="text-center"><nav><ul class="pagination">';
if ($last != 1) {
    if ($pagenum > 1) {
        $previous = $pagenum - 1;
        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" ><i class="fa fa-chevron-left"></i></a></li>';
        for ($i=$pagenum-4; $i < $pagenum ; $i++) { 
            if ($i>0) {
                $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
            }
            
        }

    }
    $paginationCtrls .= '<li class="active"><a href="#">'.$pagenum.'</a></li>';
    for ($i=$pagenum+1; $i <= $last ; $i++) { 
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
            if ($i>$pagenum+4) {
                break;
            }
            
        }
        if ($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'"><i class="fa  fa-chevron-right"></i></a></li>';
        }
}
$paginationCtrls .= '</nav></ul></div>';
$sql = 'SELECT * FROM products WHERE product_cat_id = '.$id.'';
$querys = query($sql);
confirm($querys);
echo '<h2 class="title text-center">Featured Items IN '.$named.'</h2>';
echo '<h2 class="text-center">'.$textline1.'</h2>';
while($row = fetch_array($querys)) {
$srt = strtoupper(str_replace("_"," ","{$row['pname']}"));
$product1 = <<<DELIMETER
<a class="link" href="{$row['pname']}">
<div class="col-sm-4 col-xs-6">
    <div class="thumbnail">
    <div class="product-image-wrapper">
        <div class="single-products">
                <div class="productinfo text-center">
                    <img src="{$row['pimage']}"  alt="" />
                    <h2>&#8377; {$row['pprice']}</h2>
                    <p>$srt</p>
                    <div class="row">
                        <div class="col-sm-6">
DELIMETER;
$product2 =<<<DELIMETER

                        <div class="col-sm-6">
                            <a href="{$row['pname']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-info-circle"></i>Details</a>
                        </div>
                    </div>    
                </div>
        </div>
        <div class="choose">
            <ul class="nav nav-pills nav-justified">
                <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
            </ul>
        </div>
    </div>
</div>
</div>
</a>
DELIMETER;
$s = $row['p_id'];
echo $product1;
if (isset($_SESSION['product_'.$s])) {
    $add = <<<DELIMETER
   <a href="cart?add={$row['p_id']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-cart-plus"></i>Added to cart</a>
                        </div>
DELIMETER;
echo $add;
}else{
   $add = <<<DELIMETER
   <a href="cart?add={$row['p_id']}" class="btn btn-default btn-block add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                        </div>
DELIMETER;
echo $add;
}
echo $product2;
        }
        echo $paginationCtrls; 
}
?>

