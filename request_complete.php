<?php 
	
	require(__DIR__.'/source/main.php');
	
	// Page caching.
	$page_obj = new \dc\cache\PageCache();
			
	// Main navigaiton.
	$obj_navigation_main = new class_navigation();
	$obj_navigation_main->generate_markup_nav();
	$obj_navigation_main->generate_markup_footer();	
				
	// Set up database.
	$query = new \dc\yukon\Database($yukon_connection);		
			
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
			
			// Stop errors in case someone tries a direct command link.
			if($obj_navigation_rec->get_command() != \dc\recordnav\COMMANDS::SAVE) break;
									
			// Save the record. Saving main record is straight forward. We’ll run the populate method on our 
			// main data object which will gather up post values. Then we can run a query to merge the values into 
			// database table. We’ll then get the id from saved record (since we are using a surrogate key, the ID
			// should remain static unless this is a brand new record). 
			
			// If necessary we will then save any sub records (see each for details).
			
			// Finally, we redirect to the current page using the freshly acquired id. That will ensure we have 
			// always an up to date ID for our forms and navigation system.			
		
			// Populate the object from post values.			
			$_main_data->populate_from_request();
		
			// Call update stored procedure.
			$query->set_sql('{call shocker_request_update(@id			= ?,
													@log_update_by	= ?, 
													@log_update_ip 	= ?,										 
													@account 		= ?,
													@department 	= ?,
													@details		= ?,
													@name_f			= ?,
													@name_l			= ?,
													@name_m			= ?,
													@building_code	= ?,
													@room_code		= ?,
													@location		= ?,
													@reason			= ?,
													@comments		= ?)}');
													
			$params = array(array('<root><row id="'.$_main_data->get_id().'"/></root>', 		SQLSRV_PARAM_IN),
						array($access_obj->get_account(), 		SQLSRV_PARAM_IN),
						array($access_obj->get_ip(), 			SQLSRV_PARAM_IN),
						array($_main_data->get_account(), 		SQLSRV_PARAM_IN),						
						array($_main_data->get_department(),	SQLSRV_PARAM_IN),						
						array($_main_data->get_details(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_name_f(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_name_l(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_name_m(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_building_code(), SQLSRV_PARAM_IN),
						array($_main_data->get_room_code(),		SQLSRV_PARAM_IN),
						array($_main_data->get_location(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_reason(), 		SQLSRV_PARAM_IN),
						array($_main_data->get_comments(), 		SQLSRV_PARAM_IN));
			
			//var_dump($params);
			//exit;
			
			$query->set_param_array($params);			
			$query->query_run();
			
			// Repopulate main data object with results from merge query.
			$query->get_line_config()->set_class_name('\data\Account');
			$_main_data = $query->get_line_object();
			
			// Now that save operation has completed, reload page using ID from
			// database. This ensures the ID is always up to date, even with a new
			// or copied record.
			// Initialize redirect url object and 
			// populate variables.			
			$url_query->set_data('id', $_main_data->get_id());
			$url_query->set_url_base($_SERVER['PHP_SELF']);
			
			header('Location: '.$url_query->return_url());
			
			break;			
	}

	// Option lists.
	// --Buildings
	$yukon_database->set_sql('{call '.DATABASE::SP_PREFIX.'area_building_list()}');
	$yukon_database->query_run();

	$yukon_database->get_line_config()->set_class_name('\data\Area');

	$_obj_field_source_building_list = new SplDoublyLinkedList();
	if($yukon_database->get_row_exists() === TRUE) $_obj_field_source_building_list = $yukon_database->get_line_object_list();
	
?>

<!DOCtype html>
<html lang="en">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php echo APPLICATION_SETTINGS::NAME; ?>, Request Complete</title>        
        
         <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="source/css/style.css" />
        <link rel="stylesheet" href="source/css/print.css" media="print" />
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        
        <!-- Place inside the <head> of your HTML -->
		<script type="text/javascript" src="http://ehs.uky.edu/libraries/vendor/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"});
        </script>
    </head>
    
    <body>    
        <div id="container" class="container">            
            <?php echo $obj_navigation_main->get_markup_nav(); ?>
                                                                                           
            <div class="page-header">           
                <h1>Request</h1>
                <p class="lead text-success">AED Request Completed!</p>
                          
               	<p>Thank you. We will process your AED request soon as possible. You may <a href="request.php">click here</a> to make another AED request or return to <a href="http://ehs.uky.edu">Environmental Health & Safety</a>.</p>
                           
            <?php echo $obj_navigation_main->get_markup_footer(); ?>
        </div><!--container--> 
        
    <script src="source/javascript/verify_save.js"></script>
	<script src="../../libraries/javascript/options_update.js"></script>       
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
		
		
	// Building & area entry
		$(document).ready(function(event) {		

					// Populate building seelct list.
					options_update(event, null, '#building_code');

					// If the room and building fields are 
					// populated, we need to populate the 
					// room select list too so current room 
					// selection is visible.
					<?php
					if($_main_data->get_building_code() && $_main_data->get_room_code())
					{
					?>
						 options_update(event, null, '#room_code');
					<?php
					}
					?>

					$('#room_code').attr("data-current", null);

				});

		// Room search and add.
		$('.room_search').change(function(event)
		{				
			options_update(event, null, '#room_code');	
		});
	</script>

	<!-- Latest compiled JavaScript -->
</body>
</html>

<?php
	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>