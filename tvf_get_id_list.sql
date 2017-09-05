USE [ehsinfo]
GO
/****** Object:  UserDefinedFunction [dbo].[tvf_get_id_list]    Script Date: 2017-09-05 13:09:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- tvf_get_id_list
-- Caskey, Damon V.
-- 2017-01-25
-- Returns recordset of IDs from xml list
-- 
-- <root>
--		<row id="INT" />
--		... 
-- </root>
			

CREATE FUNCTION [dbo].[tvf_get_id_list] (@param_id_list xml)
RETURNS TABLE AS
RETURN (SELECT ROW_NUMBER() OVER(ORDER BY y) AS id_row, 
			x.y.value('.','int') AS id	
		FROM @param_id_list.nodes('root/row/@id') AS x(y))

