DROP TABLE IF EXISTS wcf1_pricelist;
CREATE TABLE wcf1_pricelist (
	priceID					INT(10) AUTO_INCREMENT PRIMARY KEY,
	
	isDisabled				TINYINT(1) NOT NULL DEFAULT 0,
	hasLabels				TINYINT(1) NOT NULL DEFAULT 0,
	itemID					VARCHAR(50) NOT NULL,
	categoryID				INT(10),
	
	title					VARCHAR(80) NOT NULL,
	description				TEXT,
	currency				VARCHAR(3) NOT NULL DEFAULT 'CHF',
	price					DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	
	time INT(10)			NOT NULL DEFAULT 0,
	userID					INT(10),
	
	KEY (time),
	UNIQUE KEY itemID (itemID)
);

ALTER TABLE wcf1_pricelist ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_pricelist ADD FOREIGN KEY (categoryID) REFERENCES wcf1_category (categoryID) ON DELETE SET NULL;
