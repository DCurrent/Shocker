<?php

	abstract class APPLICATION_SETTINGS
	{
		const
			VERSION 		= '0.1.1',
			NAME			= 'AED Online',
			DIRECTORY_PRIME	= '/apps/shocker',
			TIME_FORMAT		= 'Y-m-d H:i:s',
			PAGE_ROW_MAX	= 25;
	}

	abstract class DATABASE
	{
		const 
			HOST 		= 'gensql.ad.uky.edu\general',	// Database host (server name or address)
			NAME 		= 'ehsinfo',					// Database logical name.
			USER 		= 'EHSInfo_User',				// User name to access database.
			PASSWORD	= 'ehsinfo',					// Password to access database.
			CHARSET		= 'UTF-8',						// Character set.
			SP_PREFIX	= 'shocker_';					// Added to begining of SP names.
	}

	abstract class MAILING
	{
		const
			TO		= 'melissa.claar@uky.edu, lpoor2@uky.edu, dvcask2@uky.edu',
			CC		= '',
			BCC		= '',
			SUBJECT = 'AED REQUEST',
			FROM 	= 'ehs_noreply@uky.edu';
	}
	
	abstract class SESSION_ID
	{
		const
			LAST_BUILDING	= 'id_last_building';	// Last building choosen by user.
	}

?>