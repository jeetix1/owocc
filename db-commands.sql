-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `cookie_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- These queries will add the created_at and updated_at columns to existing users table.
ALTER TABLE `users`
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `users`
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


CREATE TABLE cookie_titles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  min_count INT NOT NULL,
  max_count INT NOT NULL,
  custom_style VARCHAR(255)
);

INSERT INTO cookie_titles (title, min_count, max_count) VALUES
('Dying!', 0, 9),
('Starving', 10, 199),
('Malnourished', 200, 349),
('Struggling', 350, 620);
-- There are almost 100 more that will be released publicly later!

