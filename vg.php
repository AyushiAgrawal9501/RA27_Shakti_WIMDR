<?php
require_once "register.php";
session_start();
$CONTACT="";
$CONTACT_err="";
$a="";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$CONTACT=$_SESSION["phone"];

	if(isset($_POST['sendopt'])) {
		require('textlocal.class.php');

		$textlocal = new Textlocal(false, false,'lfotIw1FFnQ-B0jVy4xsgZpcWbWmYiJ47Ah9S1C2py');

		// You can access MOBILE from credential.php
		// $numbers = array(MOBILE);
		// Access enter mobile number in input box
		$numbers = array($_SESSION["phone"]);
		$sender = 'TXTLCL';
		$otp = mt_rand(10000, 99999);
		$message = "Hello User, this is your OTP: " . $otp;
		try {
			$result = $textlocal->sendSms($numbers, $message, $sender);
			setcookie('otp', $otp);
			$a="OTP successfully send..";
		} catch (Exception $e) {
			die('Error: ' . $e->getMessage());
		}
	}
    if(isset($_POST['verifyotp'])) { 
		$otp = $_POST['otp'];
		if($_COOKIE['otp'] == $otp) {
			setcookie('msg', "Successfully Signed In");                           
            header('location:login.php');  
		} else {
			echo "Please enter correct otp.";
		}
	}

	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Emandi/login</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Grocery Store Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet" type="text/css" media="all" /> 
<!-- //font-awesome icons -->
<!-- js -->
<script src="js/jquery-1.11.1.min.js"></script>
<!-- //js -->
<link href='//fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
</head>
	
<body>
<!-- header -->
	<div class="agileits_header">
		<div class="w3l_offers">
			<a href="index.php">Today's special Offers !</a>
		</div>
		<div class="w3l_search">
			<form action="#" method="post">
				<input type="text" name="Product" value="Search a product..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search a product...';}" required="">
				<input type="submit" value=" " name="search">
				<?php

      if(isset($_POST['Product']) && isset($_POST['search']))
      {

		  include('register.php');   //establish connection
		  include('sample.php');
          echo $_POST['Product']."<br>";
          $query= $_POST['Product'];
          //sepaerating words and appending the metaphone of
          //each words with a space
          $search=explode(" ",$query);
          $search_string="";
          foreach($search as $word)
          {
               $search_string.=metaphone($word)." ";
          }
          echo $search_string."<br>";
          $sql="SELECT * FROM product WHERE indexing like '%$search_string%'";
          $res=mysqli_query($link,$sql);
          if(!$res)
          {
              echo mysqli_error($link);
          }

          if(mysqli_num_rows($res)>0)
          {
              while($row=mysqli_fetch_assoc($res))
              {
                   ?>
				   
                   <?php
				   setcookie("prod",$row['Name']);
				   setcookie("img",$row['Image']);
				   setcookie("qty",$row['Quantity']);
				   setcookie("qlt",$row['Quality']);
				   setcookie("prc",$row['price']);
				   setcookie("hrv",$row['harvested']);
				   header('location:single.php');
              }
          }



          if(mysqli_num_rows($res)==0)
          {
              $count=0;
              $words=explode(" ",$query);
              foreach ($words as $word)
              {
                  $mword=metaphone($word);
                  $sql="SELECT * FROM product WHERE indexing like '%$mword%'";
                  $res=mysqli_query($link,$sql);
                  if(!$res)
                  {
                      echo mysqli_error($link);
                  }
                  if(mysqli_num_rows($res)>0)
                  {
                    while($row=mysqli_fetch_assoc($res))
                    {
                         $count++;
                         ?>
                         <?php
						 setcookie("prod",$row['Name']);
						 setcookie("img",$row['Image']);
						 setcookie("qty",$row['Quantity']);
						 setcookie("qlt",$row['Quality']);
						 setcookie("prc",$row['price']);
						 setcookie("hrv",$row['harvested']);
						 header('location:single.php');

                    }
                  }
              }
              if($count==0)
              {
                   echo "no search results found :(";
              }
          }


      }

    ?>
			</form>
		</div>
		<div class="product_list_header">  
			<form action="#" method="post" class="last">
                <fieldset>
                    <input type="hidden" name="cmd" value="_cart" />
                    <input type="hidden" name="display" value="1" />
                    <input type="submit" name="submit" value="View your cart" class="button" />
                </fieldset>
            </form>
		</div>
		<div class="w3l_header_right">
			<ul>
				<li class="dropdown profile_details_drop">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user" aria-hidden="true"></i><span class="caret"></span></a>
					<?php

 // Check if the user is logged in, if not then redirect to login
 if(!empty($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  					echo "<div class='mega-dropdown-menu'>
						<div class='w3ls_vegetables'>
							<ul class='dropdown-menu drp-mnu'>
								<li><a href='logout.php'>Logout</a></li> 
							</ul>

						</div>                  
					</div>";
}
else
{
					echo "<div class='mega-dropdown-menu'>
						<div class='w3ls_vegetables'>
							<ul class='dropdown-menu drp-mnu'>
								<li><a href='login.php'>Login</a></li> 
								<li><a href='signup.php'>Sign Up</a></li>
							</ul>

						</div>                  
					</div>";
}
?>
				</li>
			</ul>
		</div>
		<div class="w3l_header_right1">
			<h2><a href="mail.php">Contact Us</a></h2>
		</div>
		<div class="clearfix"> </div>
	</div>
<!-- script-for sticky-nav -->
	<script>
	$(document).ready(function() {
		 var navoffeset=$(".agileits_header").offset().top;
		 $(window).scroll(function(){
			var scrollpos=$(window).scrollTop(); 
			if(scrollpos >=navoffeset){
				$(".agileits_header").addClass("fixed");
			}else{
				$(".agileits_header").removeClass("fixed");
			}
		 });
		 
	});
	</script>
<!-- //script-for sticky-nav -->
	<div class="logo_products">
		<div class="container">
		<div class="w3ls_logo_products_left">
				<h1><a href="index.php"><span>Apni Mandi</span> Store</a></h1>
			</div>
			<div class="w3ls_logo_products_left1">
				<ul class="special_items">
					<li><a href="events.php">Events</a><i>/</i></li>
					<li><a href="about.php">About Us</a><i>/</i></li>
					<li><a href="index.php">Best Deals</a><i>/</i></li>
					
				</ul>
			</div>
			<div class="w3ls_logo_products_left1">
				<ul class="phone_email">
				<li><i class="fa fa-phone" aria-hidden="true"></i>(1800) 200 200</li>
					<li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:teamapnimandi@gmail.com">apni@mandi.com</a></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //header -->
<!-- products-breadcrumb -->
	<div class="products-breadcrumb">
		<div class="container">
			<ul>
				<li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Home</a><span>|</span></li>
				<li>Sign In & Sign Up</li>
			</ul>
		</div>
	</div>
<!-- //products-breadcrumb -->
<!-- banner -->
	<div class="banner">
		<div class="w3l_banner_nav_left">
		<nav class="navbar nav_bottom">
			 <!-- Brand and toggle get grouped for better mobile display -->
			  <div class="navbar-header nav_2">
				  <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
			   </div> 
			   <!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
					<br> &nbsp &nbsp <span> BUY FROM CATEGORIES </span> <br><br>
					<ul class="nav navbar-nav nav_1"><br>
										<li><a href="vegetables.php">Vegetables</a></li>
										<li><a href="fruits.php">Fruits</a></li>
										<li><a href="crop.php">Grains</a></li>
					</ul>
					<br> &nbsp &nbsp <span> FARMER </span> <br><br>
					<ul class="nav navbar-nav nav_1">
										<li><a href="sell.php">Sell Now</a></li>
										<li><a href="organic.php">Organic Farming</a></li>
										<li><a href="tips.php">Tips and Suggestions</a></li>
										<li><a href="policies.php">Government Policies</a></li>
					</ul>
				</div><!-- /.navbar-collapse -->	

			</nav>
		</div>
		<div class="w3l_banner_nav_right">
<!-- login -->

		<div class="w3_login">
			<h3>OTP Verification</h3>
			<div class="w3_login_module">
				<div class="module form-module">
				  <div class="toggle"><i class="fa fa-times fa-pencil"></i>

				  </div>
				   <div class="form">
					<h2>Verify</h2>
					<form method="post">
					  <?php
					   echo $a;?>
                   			 <input type="submit" name="sendopt" value="Send otp">
					</form>
					<form method="post">
                    <input type="text" name="otp" placeholder="Enter OTP" maxlength="5" required="">
			                    <input type="submit" name="verifyotp" value="Submit">

					</form>
				  </div>
			</div>
			<script>
				$('.toggle').click(function(){
				  // Switches the Icon
				  $(this).children('i').toggleClass('fa-pencil');
				  // Switches the forms  
				  $('.form').animate({
					height: "toggle",
					'padding-top': 'toggle',
					'padding-bottom': 'toggle',
					opacity: "toggle"
				  }, "slow");
				});
			</script>
		</div>
<!-- //login -->
		</div>
		<div class="clearfix"></div>
	</div>
<!-- //banner -->
<!-- newsletter-top-serv-btm -->
	<div class="newsletter-top-serv-btm">
		<div class="container">
			
		</div>
	</div>
<!-- //newsletter-top-serv-btm -->
<!-- newsletter -->
	<div class="newsletter">
		<div class="container">
			<div class="w3agile_newsletter_left">
				<h3>sign up for our newsletter</h3>
			</div>
			<div class="w3agile_newsletter_right">
				<form action="#" method="post">
					<input type="email" name="Email" value="Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
					<input type="submit" value="subscribe now">
				</form>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //newsletter -->
<!-- footer -->
	<div class="footer">
		<div class="container">
			<div class="col-md-3 w3_footer_grid">
				<h3>information</h3>
				<ul class="w3_footer_grid_list">
					<li><a href="events.php">Events</a></li>
					<li><a href="about.php">About Us</a></li>
					<li><a href="index.php">Best Deals</a></li>
					
				</ul>
			</div>
			<div class="col-md-3 w3_footer_grid">
				<h3>policy info</h3>
				<ul class="w3_footer_grid_list">
					<li><a href="faqs.php">FAQ</a></li>
					<li><a href="privacy.php">privacy policy</a></li>
					<li><a href="privacy.php">terms of use</a></li>
				</ul>
			</div>
			
			<div class="col-md-3 w3_footer_grid">
				<h3>Users Review</h3>
				<ul class="w3_footer_grid_list1">
				<li><label class="fa fa-twitter" aria-hidden="true"></label><i>01 day ago</i><span>Great place! These guys know what they are doing and go the extra mile.</span></li>
					<li><label class="fa fa-twitter" aria-hidden="true"></label><i>02 day ago</i><span>The support I got was thorough and timely.Awesome!!</span></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
			<div class="agile_footer_grids">
				<div class="col-md-3 w3_footer_grid agile_footer_grids_w3_footer">
					<div class="w3_footer_grid_bottom">
						<h4>100% secure payments</h4>
						<img src="images/card.png" alt=" " class="img-responsive" />
					</div>
				</div>
				<div class="col-md-3 w3_footer_grid agile_footer_grids_w3_footer">
					<div class="w3_footer_grid_bottom">
						<h5>connect with us</h5>
						<ul class="agileits_social_icons">
							<li><a href="#" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a href="#" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
							<li><a href="#" class="google"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
							<li><a href="#" class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a href="#" class="dribbble"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="wthree_footer_copy">
			<p>© 2020 Apni Mandi Store|All rights reserved.</p>
			</div>
		</div>
	</div>
<!-- //footer -->
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
        }
    );
});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {				
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
<script src="js/minicart.js"></script>
<script>
		paypal.minicart.render();

		paypal.minicart.cart.on('checkout', function (evt) {
			var items = this.items(),
				len = items.length,
				total = 0,
				i;

			// Count the number of each item in the cart
			var myStringArray = [];
// Count the number of each item in the cart
		for (i = 0; i < len; i++) {  myStringArray.push(items[i].get('item_name'));
myStringArray.push(items[i].get('quantity'));
myStringArray.push(items[i].get('amount'));
				total += items[i].get('quantity')*items[i].get('amount');
		}
localStorage.setItem("vOneLocalStorage", myStringArray);
			if (total < 3) {
				alert('The minimum order quantity is 3. Please add more to your shopping cart before checking out');
				evt.preventDefault();
			}
		});

	</script>
</body>
</html>