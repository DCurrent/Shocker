<?php 
	
	require(__DIR__.'/source/main.php');
	
	const LOCAL_STORED_PROC_NAME 	= 'shocker_request_read'; 	// Used to call stored procedures for the main record set of this script.	
	$primary_data_class = '\data\Request';
	
	// Page caching.
	$page_obj = new \dc\cache\PageCache();
			
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();
	$obj_navigation_main->generate_markup_nav();
	$obj_navigation_main->generate_markup_footer();	
							
	// Record navigation.
	$obj_navigation_rec = new \dc\recordnav\RecordNav();	
	
	// Prepare redirect url with variables.
	$url_query	= new \dc\url\URLFix;
	$url_query->set_data('action', $obj_navigation_rec->get_action());
	$url_query->set_data('id', $obj_navigation_rec->get_id());
	$url_query->set_data('id_key', $obj_navigation_rec->get_id_key());
		
	// User access.
	$access_obj = new \dc\stoeckl\status();
	$access_obj->get_config()->set_authenticate_url(APPLICATION_SETTINGS::DIRECTORY_PRIME);
	$access_obj->get_config()->set_database($yukon_database);
	$access_obj->set_redirect($url_query->return_url());
	
	$access_obj->verify();	
	$access_obj->action();

	// Start page cache.
	$page_obj = new \dc\cache\PageCache();
	
	// Initialize our data objects. This is just in case there is no table
	// data for any of the navigation queries to find, we are making new
	// records, or copies of records. It also has the side effect of enabling 
	// IDE type hinting.
	$_main_data = new \data\Request();	
		
	// Ensure the main data ID member is same as navigation object ID.
	$_main_data->set_id($obj_navigation_rec->get_id());
	$_main_data->set_id_key($obj_navigation_rec->get_id_key());
	
	switch($obj_navigation_rec->get_action())
	{		
		default:		
		case \dc\recordnav\COMMANDS::NEW_BLANK:
			break;
			
		case \dc\recordnav\COMMANDS::LISTING:
			break;
			
		case \dc\recordnav\COMMANDS::DELETE:	
			break;				
					
		case \dc\recordnav\COMMANDS::SAVE:
			break;			
	}

	// Last thing to do before moving on to main html is to get data to populate objects that
	// will then be used to generate forms and subforms. This may have already been done, 
	// such as when making copies of a record, but normally only a only blank object 
	// will exist at this point. We run a basic select query from our current ID and 
	// if a row is found overwrite whatever is in the main data object. If needed, we
	// repeat the process for any sub queries and forms.
	//
	// If there is no row at all found, nothing will be done - this is intended behavior because
	// there could be several reasons why no record is found here and we don't want to have 
	// overly complex or repetitive logic, but that does mean we have to make sure there
	// has been an object established at some point above.
	
	$yukon_database->set_sql('{call '.LOCAL_STORED_PROC_NAME.'(@param_id 		= ?,														 
										@param_id_key 	= ?)}');	
	
	$params = array(array($_main_data->get_id(), 			SQLSRV_PARAM_IN), 
					array($_main_data->get_id_key(), 				SQLSRV_PARAM_IN));

	// Debugging tools
	//var_dump($params);
	//exit;

	$yukon_database->set_param_array($params);			
	$yukon_database->query_run();
	
	// Get navigation record set and populate navigation object.		
	$yukon_database->get_line_config()->set_class_name('\dc\recordnav\RecordNav');	
	if($yukon_database->get_row_exists() === TRUE) $obj_navigation_rec = $yukon_database->get_line_object();	
	
	// Get primary data record set.	
	$yukon_database->get_next_result();
	
	$yukon_database->get_line_config()->set_class_name($primary_data_class);
	if($yukon_database->get_row_exists() === TRUE) $_main_data = $yukon_database->get_line_object();
	
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; ?>, Request Form</title>        
        
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
        <div id="container" class="container">            
            <?php echo $obj_navigation_main->get_markup_nav(); ?>
                                                                                           
            <div class="page-header">           
                <h1>Request Readout</h1>
				<p class="lead"><a href="request_list.php">Return to Request List.</a></p>
                <?php
					// If this isn't the active version, better alert user.
					//if(!$_main_data->get_active() && $_main_data->get_id_key())
					//{
					?>
                    	<!--div class="alert alert-warning">
                        	<strong>Notice:</strong> You are currently viewing an inactive revision of this record. Saving will make this the active revision. To return to the currently active revision without saving, click <a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$_main_data->get_id(); ?>">here</a>.
						</div-->
                    <?php
					//}
				?>
            </div>
                        	
            	<table class="table table-striped table-hover">
            		<caption>Requestor</caption>
            		<tr>
            			<th>Account</th>
            			<td><?php echo $_main_data->get_account(); ?></td>
            		</tr>
            		<tr>
            			<th>Name</th><td><?php echo $_main_data->get_name_l().', '.$_main_data->get_name_f().' '.$_main_data->get_name_m(); ?></td>
            		</tr>
            		<tr>
            			<th>Department</th><td><?php echo $_main_data->get_department(); ?></td>
            		</tr>
            	</table>
            	
               	<table class="table table-striped table-hover">
            		<caption>Location</caption>
            		<tr>
            			<th>Building</th>
            			<td><?php echo $_main_data->get_building_name(); ?></td>
            		</tr>
            		<tr>
            			<th>Area</th><td><?php echo $_main_data->get_room_id(); ?></td>
            		</tr>
            		<tr>
            			<th>Location Detail</th><td><?php echo $_main_data->get_location(); ?></td>
            		</tr>
            	</table>
                
                <table class="table table-striped table-hover">
            		<caption>Commentary</caption>
            		<tr>
            			<th>Reason</th>
            			<td><?php echo $_main_data->get_reason(); ?></td>
            		</tr>
            		<tr>
            			<th>Comments</th><td><?php echo $_main_data->get_comments(); ?></td>
            		</tr>
            	</table>
            	
            <?php echo $obj_navigation_main->get_markup_footer(); ?>
        </div><!--container--> 
    <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-40196994-1', 'uky.edu');
		  ga('send', 'pageview');

		  $(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>

	<!-- Latest compiled JavaScript -->
</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>