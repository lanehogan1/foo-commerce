<?php
$title = 'Orders';
require_once('../theme/header.php');
require_once("../lib/db_util.php");


if(!isset($_SESSION['user-id'])) header('Location: index.php');

$orderNum = DBHelper::query('SELECT `order_id` FROM `users-orders` WHERE `user_id` = ? ORDER BY `order_id` DESC LIMIT 1;
', [$_SESSION['user-id']]);
$orderNum = $orderNum->fetch();
echo $orderNum['order_id'];

$isComplete = DBHelper::query('SELECT `is_completed` FROM `orders` WHERE `order_id` = ?;
', [$orderNum['order_id']]);
$isComplete = $isComplete->fetch();
echo $isComplete['is_completed'];

if ($isComplete == '0') {
    DBHelper::insert('INSERT INTO orders-products(order_id, product_id) VALUES(?, ?)',[$orderNum['order_id'], $_GET['product_id']]); 
} ?>
<?php 
if ($isComplete == '1'){
    DBHelper::insert('INSERT INTO orders(order_id, status) VALUES("","");');
    $orderNum = DBHelper::query('SELECT `order_id` FROM `orders` ORDER BY `order_id` DESC LIMIT 1;');
    $orderNum = $orderNum->fetch();
    DBHelper::insert('INSERT INTO users-orders(order_id, user_id) VALUES(?, ?)',[$orderNum['order_id'],$_SESSION['user-id']]); 
    DBHelper::insert('INSERT INTO orders-products(order_id, product_id) VALUES(?, ?)',[$orderNum['order_id'],$_GET['product_id']]); 
}
print_r($orderNum);
$result = DBHelper::query('SELECT * FROM `orders-products` WHERE `order_id` = ?', [$orderNum['order_id']]);
$order = $result->fetchAll();
?>
    <h2><?= "Order #: " . $orderNum['order_id']; ?></h2><?php
    //Loops through each orders-products associations in the user's current order.
    foreach ($order as $orderProduct) :
        print_r($orderProduct);
        $product = DBHelper::query('SELECT * FROM `products` WHERE `product_id` = ?', [$orderProduct['product_ID']]);
        $product = $product->fetchAll();
        ?>
    <?php endforeach; ?>

<!--Bootstrap-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>