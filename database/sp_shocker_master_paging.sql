USE [ehsinfo]
GO
/****** Object:  StoredProcedure [dbo].[shocker_master_paging]    Script Date: 2017-09-06 20:07:43 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[shocker_master_paging]
	
	-- Parameters. 
		@param_page_current	int			= 1,	-- Current page of records to display.
		@param_page_rows	smallint	= 25	-- (optional) max number of records to display in a page.
			
AS
BEGIN
	
	-- If non paged layout is requested (current = -1), then just
	-- get all records and exit the procedure immediately.
		IF @param_page_current = -1
			BEGIN
				SELECT *
					FROM #cache_primary
					RETURN
			END 

	-- Verify arguments from control code. If something
	-- goes out of bounds we'll use stand in values. This
	-- also lets the paging "jumpstart" itself without
	-- needing input from the control code.
				
		-- Current page default.
		IF	@param_page_current IS NULL OR @param_page_current < 1
			SET @param_page_current = 1
			
		-- Rows per page default.
		IF	@param_page_rows IS NULL OR @param_page_rows < 1 
			SET @param_page_rows = 10
							
	-- Declare the working variables we'll need.			
				
		DECLARE 
			@row_count_total	int,	-- Total row count of primary table.
			@page_last			float,	-- Number of the last page of records.
			@row_first			int,	-- Row ID of first record.
			@row_last			int		-- Row ID of last record.
			
	-- Set up table var so we can reuse results.		
		CREATE TABLE #cache_paging
		(
			id_row				int,
			id_paging			int
		)

		-- Populate paging cache. This is to add an
		-- ordered row number column we can use to 
		-- do paging math.
		INSERT INTO #cache_paging (id_row, 
									id_paging)
		(SELECT ROW_NUMBER() OVER(ORDER BY @@rowcount)
			AS id_row,
			id_key
		FROM #cache_primary _main)	

	-- Debug. Remove for production
		--SELECT * FROM #cache_paging

	-- Get total count of records.				
		SET @row_count_total = (SELECT COUNT(id_row) FROM #cache_paging);

	-- Get paging first and last row limits. Example: If current page
	-- is 2 and 10 records are allowed per page, the first row should 
	-- be 11 and the last row 20.
				
		SET @row_first	= (@param_page_current - 1) * @param_page_rows
		SET @row_last	= (@param_page_current * @param_page_rows + 1);			
	
	-- Get last page number.
				
		SET @page_last = (SELECT CEILING(CAST(@row_count_total AS FLOAT) / CAST(@param_page_rows AS FLOAT)))
		IF @page_last = 0 SET @page_last = 1								

	-- Extract paged rows from page table var, join to the
	-- main data table where IDs match and output as a recordset. 
	-- This gives us a paged set of records from the main
	-- data table.
		SELECT TOP (@row_last-1) *
			FROM #cache_paging _paging
				JOIN #cache_primary _primary ON _paging.id_paging = _primary.id_key 	 
			WHERE id_row > @row_first 
				AND id_row < @row_last					
			ORDER BY id_row	
				
	-- Output the paging data as a recordset for use by control code.
				
		SELECT	@row_count_total	AS row_count_total,
				@param_page_rows		AS page_rows,
				@page_last			AS page_last,
				@row_first			AS row_first,
				@row_last			AS row_last
			
		
END