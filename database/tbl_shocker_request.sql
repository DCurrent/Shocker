USE [ehsinfo]
GO

/****** Object:  Table [dbo].[tbl_shocker_request]    Script Date: 2017-09-05 12:32:55 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_shocker_request](
	[id_key]		[int]			NOT NULL,
	[label]			[varchar](50)	NULL,
	[details]		[varchar](max)	NULL,
	[account]		[varchar](10)	NULL,
	[name_f]		[varchar](25)	NULL,
	[name_m]		[varchar](25)	NULL,
	[name_l]		[varchar](25)	NULL,
	[department]	[char](5)		NULL,
	[building_code]	[char](4)		NULL,
	[room_code]		[char](6)		NULL,
	[location]		[varchar](max)	NULL,
	[reason]		[varchar](max)	NULL,
	[comments]		[varchar](max)	NULL
 CONSTRAINT [PK_tbl_shocker_request_new] PRIMARY KEY CLUSTERED 
(
	[id_key] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO

ALTER TABLE [dbo].[tbl_shocker_request]  WITH CHECK ADD  CONSTRAINT [tbl_shocker_master_tbl_shocker_request] FOREIGN KEY([id_key])
REFERENCES [dbo].[tbl_shocker_master] ([id_key])
ON UPDATE CASCADE
ON DELETE CASCADE
GO

ALTER TABLE [dbo].[tbl_shocker_request] CHECK CONSTRAINT [tbl_shocker_master_tbl_shocker_request]
GO


