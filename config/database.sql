-- Database: gallery

-- Table: user_table
CREATE TABLE IF NOT EXISTS user_table (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: images_table
CREATE TABLE IF NOT EXISTS images_table (
    id SERIAL PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    image_ext VARCHAR(10) NOT NULL,
    path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_email) REFERENCES user_table(email) ON DELETE CASCADE
);

-- Table: deleted_images
CREATE TABLE IF NOT EXISTS deleted_images (
    id SERIAL PRIMARY KEY,
    image_id INTEGER NOT NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (image_id) REFERENCES images_table(id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_images_user_email ON images_table(user_email);
CREATE INDEX IF NOT EXISTS idx_deleted_images_image_id ON deleted_images(image_id);