# Create database
CREATE DATABASE IF NOT EXISTS s2890444_Website;
USE s2890444_Website;

# Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    date_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# Search table
CREATE TABLE IF NOT EXISTS searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    protein_family VARCHAR(100),
    taxonomy VARCHAR(100),
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE 
);
# delete if the user is deleted

# sequences table
CREATE TABLE sequences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    search_id INT,
    sequence_id VARCHAR(50),
    name TEXT,
    sequence TEXT,
    length INT,
    FOREIGN KEY (search_id) REFERENCES searches(id)
        ON DELETE CASCADE
);

# results table
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    search_id INT,
    motif JSON,
    conservation JSON,
    other JSON,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (search_id) REFERENCES searches(id)
        ON DELETE CASCADE
);
# delete if the search is deleted

ALTER TABLE results
ADD UNIQUE KEY (search_id);
