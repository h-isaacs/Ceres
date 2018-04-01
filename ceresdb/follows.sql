-- SQL data definition statements for the follows table of the Ceres database.
-- Created: 19/03/18
-- Author: Gregg Schofield

-- comment this command out if you are attempting to create this table for the
-- first time.
DROP TABLE follows;

CREATE TABLE follows (
	userIDa character varying (10) NOT NULL REFERENCES users (userID),
	userIDb character varying (10) NOT NULL REFERENCES users (userID),
	dateOfFollow timestamp with time zone NOT NULL,
	weighting integer NOT NULL
);