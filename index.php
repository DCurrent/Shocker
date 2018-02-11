<?php 

	// Central config and libraries.
	require(__DIR__.'/source/main.php');
	
	$page_obj = new \dc\cache\PageCache();
	
	$access_obj_process = new \dc\stoeckl\process();
	$access_obj_process->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);	
	$access_obj_process->get_config()->set_database($yukon_database);
	$access_obj_process->process_control();
	
	//var_dump($_POST);
	//echo '<br />';
	//var_dump($_SESSION);
	
	//Get and verify log in status.
	$access_obj = new \dc\stoeckl\status();
	$access_obj->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);	
	$access_obj->get_config()->set_database($yukon_database);
	$access_obj->verify();
	
	// Set up navigaiton.
	$navigation_obj = new class_navigation();
	$navigation_obj->generate_markup_nav();
	$navigation_obj->generate_markup_footer();
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; ?></title>        
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>
    
    <body>          
        <!-- Modal -->
        <div id="help_link_blue" class="modal fade" role="dialog">
          <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Link Blue</h4>
              </div>
              <div class="modal-body">
                <p>Link Blue is the University of Kentucky's campus wide Active Directory login. It is the same account name and password you use to log into a workstation. <a href="//www.uky.edu/UKHome/subpages/linkblue.html" target="_blank">Click here</a> for more information.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>        
          </div>
        </div>
    
        <div id="container" class="container">            
            <?php echo $navigation_obj->get_markup_nav(); ?>                                                                                
            <div class="page-header">
                <h1><?php echo APPLICATION_SETTINGS::NAME; ?></h1>
                <p class="lead">
				<?php
				
					echo '<!--account:'.$access_obj->get_account().'-->';
					echo '<!--id:'.$access_obj->get_id().'-->';
					// Logged in?
					if($access_obj->get_account())
					{
						// Get current hour in the 24 hour clock format.
						$time = date('H');
						
						// Give user a greeting based on hour of the day.
						if ($time < '12') 
						{
							echo 'Good morning ';
						} 
						else if ($time >= '12' && $time < '17') 
						{
							echo 'Good afternoon ';
						} 
						else if ($time >= "17") 
						{
							echo 'Good evening ';
						}
						echo $access_obj->get_name_f();
				?>! Thank you for using <?php echo APPLICATION_SETTINGS::NAME; ?>. <a href="request.php">Click here</a> to fill out an AED request.</p>
                <?php
					}
					else
					{
				?>
                		<p class="lead">Welcome to <?php echo APPLICATION_SETTINGS::NAME; ?>, the University of Kentucky's online Automated External Defibrillator (AED) request application. In order to use <?php echo APPLICATION_SETTINGS::NAME; ?>, please log in using your <a href="#" data-toggle="modal" data-target="#help_link_blue">Link Blue</a> account and password.</p>
            		
                    	<p><?php echo $access_obj->dialog(); ?></p>
                    	
                        <!--Note: PHP self is nessesary to override any link vars.-->
                        <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" class="form-control" name="account" id="account" placeholder="Link Blue Account" required>
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" class="form-control" name="credential" id="credential" placeholder="Password" required>
                            </div>
                            
                            <br>
                            
                            <button type="submit" name="access_action" value="<?php echo \dc\stoeckl\ACTION::LOGIN; ?>" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                        </form>
            
                <?php
					}					
				?>
            </div> 
                    
            <?php echo $navigation_obj->get_markup_footer(); ?>
        </div><!--container-->        
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40196994-1', 'uky.edu');
  ga('send', 'pageview');
</script>
</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>