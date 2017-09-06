USE [EHSINFO]
GO

/****** Object:  Table [dbo].[tbl_shocker_session]    Script Date: 2017-09-06 15:52:33 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[tbl_shocker_session](
	[session_id] [varchar](40) NOT NULL,
	[session_data] [varchar](max) NULL,
	[last_update] [datetime2](7) NULL,
	[source] [varchar](max) NULL,
	[host] [varchar](15) NULL,
 CONSTRAINT [PK_[tbl_shocker_session] PRIMARY KEY CLUSTERED 
(
	[session_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO


