-- Create the additional databases
CREATE DATABASE IF NOT EXISTS expenses_db;
CREATE DATABASE IF NOT EXISTS mileage_db;
CREATE DATABASE IF NOT EXISTS other_db;

USE expenses_db;
CREATE TABLE transactions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Description VARCHAR(255) NOT NULL,
    Date DATE NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    Notes TEXT
);

USE mileage_db;
CREATE TABLE IF NOT EXISTS vehicle_logs (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Date DATE NOT NULL,
    MPG DECIMAL(10, 2) NOT NULL,                -- Calculated fuel efficiency
    Trip DECIMAL(10, 2) NOT NULL, -- The 'Trip' field from your form
    Total DECIMAL(10, 2) NOT NULL,        -- Total cost of the fill-up
    PricePerGallon DECIMAL(10, 2) NOT NULL,
    Notes TEXT
);


USE other_db;
CREATE TABLE contacts (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(150),
    Phone VARCHAR(20)
);

CREATE TABLE users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Passhash VARCHAR(255) NOT NULL
);

insert into users (Username, Passhash) values ('kota', '$2y$10$BW94fPShe8djmec3EFiDZeF5MnSYIozefusCdEokijaoKvNGzk6TW');

-- Grant permissions for your 'user' to access them
GRANT ALL PRIVILEGES ON expenses_db.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON mileage_db.* TO 'user'@'%';
GRANT ALL PRIVILEGES ON other_db.* TO 'user'@'%';

FLUSH PRIVILEGES;