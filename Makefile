.PHONY: build test

build:
	docker build --tag request-bundle-ci .

test: build
	docker-compose -f ./tests/E2e/docker-compose.yml run --rm test
