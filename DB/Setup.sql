-- Create and select the database
DROP DATABASE IF EXISTS myLifeGrade;
CREATE DATABASE myLifeGrade;
USE myLifeGrade;

-- Create the users table
CREATE TABLE Users (
	UserID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	UserName VARCHAR(200) NOT NULL,
	PasswordHash VARCHAR(200),
	PasswordSalt INT NOT NULL DEFAULT 0,
	Email VARCHAR(200),
    CreatedOn TIMESTAMP NOT NULL,
	LastActivityDate TIMESTAMP NOT NULL,
	UserType INT NOT NULL DEFAULT 0,
	CurrentPoints INT NOT NULL DEFAULT 0,
	ApiKey VARCHAR(36) NOT NULL
);

-- Create the category table
CREATE TABLE Categories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    UserID INT NOT NULL, -- TODO: Make this an FK
    CategoryName VARCHAR(200),
    CategoryDescription VARCHAR(2000),
    CategoryWeight INT NOT NULL,
    CreatedOn TIMESTAMP NOT NULL,
    IsActive BOOL NOT NULL DEFAULT 1
);

-- Create the key indicator table
CREATE TABLE KeyIndicators (
    KeyIndicatorID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    CategoryID INT NOT NULL,  -- TODO: Make this an FK
    KeyIndicatorName VARCHAR(200),
    KeyIndicatorDescription VARCHAR(2000),
    KeyIndicatorWeight INT NOT NULL,
    CreatedOn TIMESTAMP NOT NULL,
    IsActive BOOL NOT NULL DEFAULT 1
);

-- Create the daily report table
CREATE TABLE DailyKeyIndicatorReports (
    ReportID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    KeyIndicatorID INT NOT NULL, -- TODO: Make this an FK
    CreatedOn TIMESTAMP NOT NULL,
    AchievedPercentage DECIMAL NOT NULL DEFAULT 0,
    Notes VARCHAR(2000)
);

-- Create an external user
INSERT INTO Users 
(UserName, PasswordHash, Email, CreatedOn, LastActivityDate, UserType, ApiKey)
VALUES 
('admin', '', 'mylifegrade@gmail.com', NOW(), NOW(), 1, UUID());