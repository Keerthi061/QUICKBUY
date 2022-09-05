<?php
session_start();
include('db.php');
if (!empty($_SESSION['email'])) {  
  # code...
  $email = $_SESSION['email'];
  $sql = $con->query("SELECT lname, fname FROM users WHERE email = '$email' limit 1");
  if ($sql->num_rows != 0) {
    # code...
    while ($rows = $sql->fetch_assoc()) {
      # code...
      $lname = $rows['lname'];
      $fname = $rows['fname'];
    }
  }
}

$status="";
if (isset($_POST['action']) && $_POST['action']=="remove"){
if(!empty($_SESSION["shopping_cart"])) {
  foreach($_SESSION["shopping_cart"] as $key => $value) {
    if($_POST["code"] == $key){
    unset($_SESSION["shopping_cart"][$key]);
    $status = "<div class='alert alert-danger text-center alert-dismissible'>
   <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Product has been removed from Cart!</strong></div>";
    }
    if(empty($_SESSION["shopping_cart"]))
    unset($_SESSION["shopping_cart"]);
      }   
    }
}

if (isset($_POST['action']) && $_POST['action']=="change"){
  foreach($_SESSION["shopping_cart"] as &$value){
    if($value['code'] === $_POST["code"]){
        $value['quantity'] = $_POST["quantity"];
         $status = "<div class='alert alert-success text-center alert-dismissible'>
   <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Product has been Updated!</strong></div>";
        break; // Stop the loop after we've found the product
    }
}
    
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Shoppy an Ecommerce Online Shopping Website</title>
<!--/tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- //tags -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<style>
	.cart_div {
	float:right;
	font-weight:bold;
	position:relative;
	}
	
.cart_div span {
	font-size: 17px;
    line-height: 14px;
    background: #ff8080;
    padding: 2px;
    border: 2px solid #fff;
    border-radius: 50%;
    position: absolute;
    top: -1px;
    left: 13px;
    color: #fff;
    width: 23px;
    height: 22px;
    text-align: center;
	}

</style>
<body>
   <!-- header -->
<div class="header" id="home">
  <div class="container">
    <ul>
      <?php 
   if (empty($_SESSION['email'])) {
     # code...
    echo "
    <li> <a href='login.php'><i class='fa fa-unlock-alt' aria-hidden='true'></i> Sign In </a></li>
    ";
   }else{
    echo "
      <li> <a href='logout.php'><i class='fa fa-unlock-alt' aria-hidden='true'></i> Sign Out </a></li>
    ";
   }

      ?>

        <li><i class="fa fa-envelope-o" aria-hidden="true"></i> 

        <?php 
        if (!empty($_SESSION['email'])) {
          # code...
          echo "Welcome!"." ". $lname." ".$fname; 
        }
        else{
          echo "You are not Signed in";
        }

        ?>

       </li>
    </ul>
  </div>
</div>
<div class="header" id="home">
  <div class="container">
 <div class="">
  <img src="product-images/jumia.gif" class="img-responsive">
</div>
  </div>
</div>
<!-- //header -->
                <br>
      <h3 class="wthree_text_info">Edit <span>Cart</span></h3>    
      	<?php
if(isset($_SESSION["shopping_cart"])){
    $total_price = 0;
?> 
<div class="container">
  <div class="row" style="margin: 10px">
    <div class="col-lg-12">

       <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">PHOTO</th>
            <th scope="col">ITEM</th>
      <th scope="col">QUANTITY</th>
      <th scope="col">UNIT PRICE</th>
      <th scope="col">SUBTOTAL</th>
    </tr>
  </thead>

  <?php		
foreach ($_SESSION["shopping_cart"] as $product){

	?>
  <tbody>
    <tr>
      <th scope="row"><img src='<?php echo $product["image"]; ?>' width="50" height="40" /></th>
      <td><?php echo $product["name"]; ?><br>
<form method='post' action=''>
<input type='hidden' name='code' value="<?php echo $product["code"]; ?>" />
<input type='hidden' name='action' value="remove" />
<button type="submit" class="btn btn-secondary btn-xs" title="REMOVE" style="background: #ff8080; color: #fff;">
	<strong>X</strong></button>
</form>
      </td>
      <td>
  <form method='post' action=''>
<input type='hidden' name='code' value="<?php echo $product["code"]; ?>" />
<input type='hidden' name='action' value="change" />
<input type='text' name='quantity' maxlength="2" size="2" value="<?php echo $product["quantity"]; ?>" />
<span><button type="submit" class="btn btn-default btn-xs" style="background: #ff8080; color: #fff;" onChange="this.form.submit()">Update</button></span>
</form>
      </td>
      <td>
 <?php echo "$".$product["price"]; ?>
      </td>
      <td><?php echo "$".$product["price"]*$product["quantity"]; ?></td>
    </tr>
<?php $total_price += ($product["price"]*$product["quantity"]);
 } 
 ?>
 
    <tr>
      <?php echo $status; ?>

<td colspan="5" align="right">
<strong>TOTAL: <?php echo "$".$total_price; ?></strong>
</td>
</tr>

<tr>
  <td colspan="5" align="right">
 <a href="index.php"><button type="button" class="btn btn-success" style="float: left;" data-dismiss="modal">Continue Shopping</button></a>
      <a href="checkout.php"><button type="button" class="btn btn-primary">Proceed To Checkout</button></a>
  </td>
</tr>

  </tbody>
</table>
 
        <?php
}else{
	echo "<h4 style='color:red; text-align:center;'>Your cart is empty!</h4><br><br> 
  <a href='index.php'><button type='button' style='margin-left: 45%; margin-right: 45%;' class='btn btn-warning' data-dismiss='modal'>Start Shopping</button></a>"
  ;
	}
?>              
    </div>
  </div>
</div>
        
   

<br><br><br><br>

 <script>
$(".alert").delay(4000).slideUp(200, function() {
    $(this).alert('close');
});
  </script>
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>
