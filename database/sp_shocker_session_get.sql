USE [EHSINFO]
GO
/****** Object:  StoredProcedure [dbo].[shocker_session_get]    Script Date: 2017-09-06 15:58:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Create date: 2015-05-22
-- Description:	Return session data.
-- =============================================
CREATE PROCEDURE [dbo].[shocker_session_get]
	
	-- Parameters
	@id				varchar(40) = NULL	-- Primary key.

AS	
BEGIN
	
	SET NOCOUNT ON;	 
	
		SELECT session_data 
			FROM dbo.tbl_shocker_session
			WHERE 
				session_id = @id
					
END

