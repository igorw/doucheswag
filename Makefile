test:
	bin/phpunit
	bin/behat
	bin/behat -p end-to-end

init:
	mkdir -p storage
	touch storage/data.db
	php src/DoucheWeb/console.php init
	php src/DoucheWeb/console.php sql "INSERT INTO auctions (id, name, ending_at, currency) VALUES (null, 'YOLO glasses', '2013-06-02', 'GBP')"

web: .PHONY
	php -S localhost:8080 -t web web/dev.php

web-prod:
	php -S localhost:8080 -t web web/prod.php

.PHONY:
