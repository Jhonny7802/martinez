-- Fix users table by adding status column
USE martinez;

-- Check if status column exists
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'martinez' 
  AND TABLE_NAME = 'users' 
  AND COLUMN_NAME = 'status';

-- Add status column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active' 
AFTER email_verified_at;

-- Update existing users to have active status
UPDATE users SET status = 'active' WHERE status IS NULL;

-- Verify the column was added
DESCRIBE users;
