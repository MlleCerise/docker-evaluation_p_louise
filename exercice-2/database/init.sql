CREATE DATABASE docker_doc;
CREATE DATABASE docker_doc_dev;

USE docker_doc_dev;

CREATE TABLE article (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(32),
    body TEXT
);

INSERT INTO article (title, body) VALUES
('Docker overview', 'Docker is an open platform for developing, shipping, and running applications.'),
('What is a container?', 'Imagine you’re developing a web app with multiple components...');
