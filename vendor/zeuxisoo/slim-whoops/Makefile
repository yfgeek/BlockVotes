all:
	@echo "Command              : Description"
	@echo "-------------------- : ---------------------"
	@echo "make install         : Install the development vendors and assets"
	@echo "make server          : Start web server to run examples"
	@echo "make test            : Run the phpunit"
	@echo "make coverage-report : Generate coverage report by phpunit"

install:
	@curl -sS https://getcomposer.org/installer | php
	@php composer.phar install

server:
	@php -S localhost:8080 -t examples

test:
	@php vendor/bin/phpunit

coverage-report:
	@php vendor/bin/phpunit --coverage-html ./report
