.PHONY: build test down

build:
	docker build --tag request-bundle-ci .

test: down
	docker-compose -f ./tests/E2e/docker-compose.yml run --rm test

down:
	docker-compose -f ./tests/E2e/docker-compose.yml down
