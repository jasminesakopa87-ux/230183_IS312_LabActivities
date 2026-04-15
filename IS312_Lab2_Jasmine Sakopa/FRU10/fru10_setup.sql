-- ============================================================
-- Author: [Jasmine SAKOPA]
-- Date:   18th March 2026
-- Unit:   IS312 Web Application Development
-- File:   fru10_setup.sql
--         Creates the FRU10 database, Program and Student
--         tables, and inserts the sample data shown in the
--         assignment brief.
-- ============================================================

-- Create the database and select it
CREATE DATABASE IF NOT EXISTS FRU10;
USE FRU10;

-- -------------------------------------------------------
-- Table: Program
-- Stores academic program details.
-- ProgramCode is the primary key (e.g. 'IS', 'BS').
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS Program (
    ProgramCode VARCHAR(10)  NOT NULL,
    ProgramName VARCHAR(100) NOT NULL,
    Duration    INT          NOT NULL,
    Faculty     VARCHAR(100) NOT NULL,
    PRIMARY KEY (ProgramCode)
);

-- -------------------------------------------------------
-- Table: Student
-- Stores enrolled student details.
-- ProgramCode is a foreign key referencing Program.
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS Student (
    StudentNo   VARCHAR(10)  NOT NULL,
    Firstname   VARCHAR(50)  NOT NULL,
    Lastname    VARCHAR(50)  NOT NULL,
    Gender      VARCHAR(10)  NOT NULL,
    ContactNo   VARCHAR(20)  NOT NULL,
    ProgramCode VARCHAR(10)  NOT NULL,
    PRIMARY KEY (StudentNo),
    FOREIGN KEY (ProgramCode) REFERENCES Program(ProgramCode)
);

-- -------------------------------------------------------
-- Sample data: Programs
-- -------------------------------------------------------
INSERT INTO Program (ProgramCode, ProgramName, Duration, Faculty) VALUES
('BS', 'Bachelor of Science',              3, 'Department of Science'),
('IS', 'Bachelor of Information Systems',  3, 'Department of Information Systems');

-- -------------------------------------------------------
-- Sample data: Students (as shown in assignment Table 1)
-- -------------------------------------------------------
INSERT INTO Student (StudentNo, Firstname, Lastname, Gender, ContactNo, ProgramCode) VALUES
('11111', 'James',   'Peter', 'Male',   '71717171', 'BS'),
('22222', 'Peter',   'Mark',  'Male',   '71727172', 'IS'),
('33333', 'Mary',    'John',  'Female', '71737173', 'BS'),
('44444', 'Belinda', 'Cain',  'Female', '71717271', 'IS');
