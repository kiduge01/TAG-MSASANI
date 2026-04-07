-- Add member details fields from form
-- Tarehe: 2026-04-07

ALTER TABLE members ADD COLUMN city_village VARCHAR(100) NULL COMMENT 'Mji/Kijiji';
ALTER TABLE members ADD COLUMN country VARCHAR(100) NOT NULL DEFAULT 'Tanzania' COMMENT 'Nchi';
ALTER TABLE members ADD COLUMN education_level VARCHAR(100) NULL COMMENT 'Kiwango cha Elimu';
ALTER TABLE members ADD COLUMN job_title VARCHAR(150) NULL COMMENT 'Cheo Kazi/Majukumu Kazini';
ALTER TABLE members ADD COLUMN church_services TEXT NULL COMMENT 'Huduma Kanisani (JSON or comma-separated)';
ALTER TABLE members ADD COLUMN is_doing_service_fully TINYINT(1) NULL COMMENT 'Je Unafanya Huduma Yako kikamilifu';
ALTER TABLE members ADD COLUMN service_level VARCHAR(100) NULL COMMENT 'Daraja la Huduma';
ALTER TABLE members ADD COLUMN pays_tithes_faithfully TINYINT(1) NULL COMMENT 'Je Unatoa zaka kwa Uaminifu';
ALTER TABLE members ADD COLUMN account_number VARCHAR(50) NULL COMMENT 'Nambari Ya Bahasha';
ALTER TABLE members ADD COLUMN tithe_amount_monthly DECIMAL(10, 2) NULL COMMENT 'Kiasi cha Zaka kila Mwezi';
ALTER TABLE members ADD COLUMN emergency_contact_relationship VARCHAR(100) NULL COMMENT 'Uhusiano (Relationship to emergency contact)';
ALTER TABLE members ADD COLUMN emergency_contact_email VARCHAR(150) NULL COMMENT 'Baruapepe ya Dharura';
ALTER TABLE members ADD COLUMN alt_phone_2 VARCHAR(30) NULL COMMENT 'Alternative phone number 2';
ALTER TABLE members ADD COLUMN physical_address_detailed VARCHAR(255) NULL COMMENT 'Anuani (Detailed address)';

-- Create index for common queries
ALTER TABLE members ADD INDEX idx_members_country (country);
ALTER TABLE members ADD INDEX idx_members_education (education_level);
ALTER TABLE members ADD INDEX idx_members_service_level (service_level);
