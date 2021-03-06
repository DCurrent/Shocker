USE [EHSINFO]
GO
/****** Object:  StoredProcedure [dbo].[shocker_session_destroy]    Script Date: 2017-09-06 15:56:49 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Create date: 2015-05-23
-- Description:	Destroy session data.
-- =============================================
CREATE PROCEDURE [dbo].[shocker_session_destroy]
	
	-- Parameters
	@id				varchar(40) = NULL	-- Primary key.

AS	
BEGIN
	
	SET NOCOUNT ON;	 
	
		DELETE FROM dbo.tbl_shocker_session WHERE session_id = @id
					
END

