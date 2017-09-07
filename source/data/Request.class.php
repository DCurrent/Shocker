<?php
	
	namespace data;

	interface iRequest
	{
		// Accessors.
		function get_location();
		function get_building_code();
		function get_building_name();
		function get_room_code();	
		function get_room_id();
		function get_reason();		
		function get_comments();		
		
		// Mutators.
		function set_location($value);
		function set_building_code($value);
		function set_room_code($value);	
		function set_reason($value);		
		function set_comments($value);			
	}
	
	class Request extends Account implements iRequest
	{
		protected			
			$location		= NULL,	
			$building_code	= NULL,
			$building_name	= NULL,
			$room_code		= NULL,
			$room_id		= NULL,
			$reason			= NULL,
			$comments		= NULL;	
			
		// Accessors					
		public function get_location()
		{
			return $this->location;
		}
		
		public function get_building_code()
		{
			return $this->building_code;
		}
		
		public function get_building_name()
		{
			return $this->building_name;
		}
		
		public function get_room_code()
		{
			return $this->room_code;
		}		
		
		public function get_room_id()
		{
			return $this->room_id;
		}
		
		public function get_reason()
		{
			return $this->reason;
		}
		
		public function get_comments()
		{
			return $this->comments;
		}
		
		// Mutators
		public function set_location($value)
		{
			$this->location = $value;
		}
		
		public function set_building_code($value)
		{
			$this->building_code = $value;
		}
		
		public function set_room_code($value)
		{
			$this->room_code = $value; 
		}
				
		public function set_reason($value)
		{
			$this->reason = $value;
		}
		
		public function set_comments($value)
		{
			$this->comments = $value;
		}		
	}	
	
?>