<?php
	require_once(__DIR__.'/../../source/main.php');

	// Output options markup for select.
	function options_markup(SplDoublyLinkedList $_list, $select_target = NULL)
	{
		$result		= NULL;
		$current 	= NULL;

		if(is_object($_list) === TRUE)
		{        
			// Generate table row for each item in list.
			for($_list->rewind(); $_list->valid(); $_list->next())
			{	                                                               
				$current = $_list->current();

				$value 		= $current->get_building_code();																
				$label		= $current->get_building_code().' - '.$current->get_building_name();
				$selected 	= NULL;

				if($value != NULL && $value == $select_target)
				{
					$selected = ' selected ';
				}									

				$result .= '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';                 
			}
		}
		
		return $result;
	}

	class ConfigLocal extends data\Common
	{
		private $id_guid;
		private $select_target;
		
		// Accessors
		public function get_id_guid()
		{
			return $this->id_guid;
		}
		
		public function get_select_target()
		{
			return $this->select_target;
		}
		
		// Mutators
		public function set_id_guid($value)
		{
			$this->id_guid = $value;
		}
		
		public function set_select_target($value)
		{
			$this->select_target = $value;
		}
	}

	// Start page cache.
	$page_obj = new \dc\cache\PageCache();

	// Get request variables.
	$config = new ConfigLocal();
	$config->populate_from_request();

	$guid 			= $config->get_id_guid();
	$option_list	= NULL;   	

	// --Buildings
	$yukon_database->set_sql('{call '.DATABASE::SP_PREFIX.'area_building_list()}');
	$yukon_database->query_run();

	$yukon_database->get_line_config()->set_class_name('\data\Area');

	$_obj_field_source_list = new SplDoublyLinkedList();
	if($yukon_database->get_row_exists() === TRUE) $_obj_field_source_list = $yukon_database->get_line_object_list();

	//
	$option_list = options_markup($_obj_field_source_list, $config->get_select_target());
?>



		<?php
			$temp = 0;
		
			if($temp == 1)
			{
		?>
		<?php		
			}
			else
			{
				echo $option_list;				
			}
		?>
	</div>

<?php
	// So the loading bar doesn't look like a glitch when loading is fast.
	//sleep(1);

	// Collect and output page markup.
	echo $page_obj->markup_and_flush();
?>