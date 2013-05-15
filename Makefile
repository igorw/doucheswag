test:
	bin/phpunit
	bin/behat

init:
	mkdir -p storage
	touch storage/data.db
	php src/DoucheWeb/init.php

web: .PHONY
	php -S localhost:8080 -t web web/dev.php

web-prod:
	php -S localhost:8080 -t web web/prod.php

.PHONY:
