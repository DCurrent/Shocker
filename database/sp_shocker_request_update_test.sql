USE [EHSINFO]
GO

DECLARE	@return_value int

EXEC	@return_value = [dbo].[shocker_request_update]
		@param_id_list = N'<root><row id="1"/></root>',
		@param_update_by = N'dvcask2',
		@param_update_host = N'Laptop',
		@param_account = N'dvcask2',
		@param_department = N'3he00',
		@param_details = N'Detail test.',
		@param_name_f = N'Damon',
		@param_name_l = N'V.',
		@param_name_m = N'Caskey',
		@param_building_code = N'0314',
		@param_room_code = N'028518',
		@param_location = N'On the wall.',
		@param_reason = N'Need it bad.',
		@param_comments = N'Hope this works. Updated.'

SELECT	'Return Value' = @return_value

GO
