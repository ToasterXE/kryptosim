-- Create a new database called 'DatabaseName'
-- Connect to the 'master' databatest1is snippet
USE master
GO
-- Create the new database if it does not exist already
IF NOT EXISTS (
    SELECT name
        FROM sys.databases
        WHERE name = N'test1'
)
CREATE DATABASE test1
GO


-- Create a new table called 'testt' in schema 'dbo'
-- Drop the table if it already exists
IF OBJECT_ID('dbo.testt', 'U') IS NOT NULL
DROP TABLE dbo.testt
GO
-- Create the table in the specified schema
CREATE TABLE dbo.testt
(
    testtId INT NOT NULL PRIMARY KEY, -- primary key column
    Name [NVARCHAR](50) NOT NULL,
    Location [NVARCHAR](50) NOT NULL
    -- specify more columns here
);
GO

-- Insert rows into table 'testt'
INSERT INTO testt
( -- columns to insert data into
 [testtId], [Name], [Location]
)
VALUES
   ( 1, N'Jared', N'Australia'),
   ( 2, N'Nikita', N'India'),
   ( 3, N'Tom', N'Germany'),
   ( 4, N'Jake', N'United States')
-- add more rows here
GO

SELECT COUNT(*) as testc FROM dbo.testt;
SELECT e.testtId, e.Name, e.Location FROM dbo.testt as e
GO