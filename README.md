# Bitcoin tracker

A simple laravel based application for tracking the changes in bitcoin price.

## Warning

Currently tested only under Windows 10 with sqlite because of technical issues related to Docker Desktop for Windows, so it requires php locally(described in composer.json).

## Setup

### Running locally

Running locally requires set up docker.

Run the migrations: `php artisan migrate --seed`.

To run the application run the bash command: `php artisan serve`.

### Hooks

`cp pre-commit .git/hooks`

### Testing

`composer run test`

## Check list
- [ ] Manual testing
- [ ] Tests & coverage
- [ ] Logging
- [ ] Error handling

## TODOs:
- [ ] General diagram
- [ ] Initial/Basic
- - [x] Laravel setup
- - [ ] Dockerize
- - [x] Clean default files
- [x] Bitcoin price getter
- - [x] Client
- - [x] DTO
- - [x] Adapter
- - [x] Error handling
- - [x] Caching
- - [x] Test coverage
- [x] Price action/notification
- - [x] Model + migration
- - [x] Request
- - [x] DTO
- - [x] Persist endpoint
- - [x] Test coverage
- [ ] Cron container

## Design decision notes

- I use an abstraction to get Bitcoin price to make able adding another source besides Bitfinex
- I use `new class implements ...` to create single use class instances instead of creating dedicated class
- Using single table with email and price, instead of dedicated table for emails to prevent the possible performance issues caused by the JOIN statements
