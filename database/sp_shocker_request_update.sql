USE [ehsinfo]
GO
/****** Object:  StoredProcedure [dbo].shocker_request_update    Script Date: 2017-09-05 12:27:39 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Create date: 2015-07-27
-- Description:	Insert or update items.
-- =============================================
CREATE PROCEDURE [dbo].[shocker_request_update]
	
	-- Parameters
	@param_id_list			xml				= NULL, 
	@param_update_by		varchar(10)		= NULL,
	@param_update_host		varchar(50)		= NULL,
	@param_account			varchar(10)		= NULL,
	@param_department		char(5)			= NULL,
	@param_details			varchar(max)	= NULL,
	@param_name_f			varchar(25)		= NULL,
	@param_name_l			varchar(25)		= NULL,
	@param_name_m			varchar(25)		= NULL,	
	@param_building_code	char(4)			= NULL,
	@param_room_code		char(10)		= NULL,
	@param_location			varchar(max)	= NULL,
	@param_reason			varchar(max)	= NULL,
	@param_comments			varchar(max)	= NULL
			

AS
BEGIN
	
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;	

	-- Local cache of master result.
	CREATE TABLE #cache_master_update
	(
		id_row	int,
		id_key	int,
		id		int
	)

	-- Update master table. This creates a new
	-- version entry and primary key we need.
		INSERT INTO #cache_master_update
			EXEC shocker_master_update
				@param_id_list,
				@param_update_by,
				@param_update_host

	-- Update data table using the
	-- new primary key from master table.
		INSERT INTO tbl_shocker_request
				(id_key,
				account, 
				department,
				name_f,
				name_l,
				name_m,
				details,
				building_code,
				room_code,
				location,
				reason,
				comments)	

		SELECT _master.id_key,
				@param_account, 
				@param_department,
				@param_name_f,
				@param_name_l,
				@param_name_m,
				@param_details,
				@param_building_code,
				@param_room_code,
				@param_location,
				@param_reason,
				@param_comments

		FROM 
			#cache_master_update AS _master
		
		-- Sub records

		-- Output ID of the newly inserted record.
		SELECT TOP 1
			_master.id
			FROM #cache_master_update AS _main
			JOIN tbl_shocker_master AS _master ON _main.id_key = _master.id_key
			
					
END
