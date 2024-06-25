<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sales</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />  
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">  
    
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Sales</h1>
 <div class="err_msg"></div>

	<div id="body">
    <form action="" id="user_sale" class="" enctype="multipart/form-data" method="post">
        
        
        <label>User</label>
        <select class="user" name="user">
			<option value="">Select</option>
			<?php foreach($user as $usr){ ?>
				<option value="<?= $usr['id'] ?>"><?= $usr['username'] ?></option>
				<?php }?>
		</select>
        </br>
        </br>
		<label>Amount</label>
        <input type="text" class="amount" name="amount">
		</br>
        </br>
        <button type="button" class="submit_btn">Submit</button>
    </form>
  </div>

	<p class="footer">Affiliation Heirarchy</p>
</div>

</body>
</html> 
   
   <!-- Script -->  
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
   <script type='text/javascript'>  
   var base_url = "<?php echo base_url();?>";
   $(document).ready(function(){  
   
     $('.submit_btn').on('click',function(e){  
       e.preventDefault();   
       var amount   = $('.amount').val();
       var user   = $('.user').val();
        var post_data = {amount:amount,user:user};
       $.ajax({  
         url: base_url+'user/save_sales',  
         type: 'post',  
         data: post_data,
         dataType: 'json',  
         success: function(response){  
            $('.err_msg').html(response.error_msg);
         }  
       });
     });  
   });  
    </script>  
  </body>  
</html>