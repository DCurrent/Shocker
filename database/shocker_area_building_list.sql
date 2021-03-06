USE [ehsinfo]
GO
/****** Object:  StoredProcedure [dbo].[shocker_area_building_list]    Script Date: 2017-09-06 20:06:28 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Create date: 2015-07-27
-- Description:	Get list of items, ordered and paged.
-- =============================================

CREATE PROCEDURE [dbo].[shocker_area_building_list]
	
	-- paging
	@param_page_current		int				= -1,
	@param_page_rows		int				= 10
	
	-- Sorting
	
	-- Filters
	
AS	
	SET NOCOUNT ON;
		
	-- Create and Populate main table var. This is the primary query. Order
	-- and query details go here.
		SELECT ROW_NUMBER() OVER(ORDER BY LTRIM(_main.BuildingName))
			AS	_row_id,					
				_main.BuildingCode	AS building_code,
				_main.BuildingName	AS building_name			
		INTO #cache_primary
		FROM UKSpace.dbo.MasterBuildings AS _main

	-- Output results.
		SELECT * FROM #cache_primary