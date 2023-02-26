# Bitcoin tracker

A simple laravel based application for tracking the changes in bitcoin price.

## Running locally

Running locally requires set up docker.

To run the application run the bash command: `docker-compose up -d`.

## Check list
- [ ] Manual testing
- [ ] Tests & coverage
- [ ] Logging
- [ ] Error handling

## TODOs:
- [ ] General diagram
- [ ] Initial/Basic
- - [x] Laravel setup
- - [x] Dockerize
- - [ ] Clean default files
- [ ] Bitcoin price getter
- - [x] Client
- - [x] DTO
- - [x] Adapter
- - [x] Error handling
- - [x] Caching
- - [ ] Test coverage

## Design decision notes

- I use an abstraction to get Bitcoin price to make able adding another source besides Bitfinex
- I use `new class implements ...` to create single use class instances instead of creating dedicated class
