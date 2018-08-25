Weather service
======================
A micro-service aggregating weather information from weather providers.
It serves as a gateway between the weather information providers (which normally limits the number of request) and the application that depends on weather information.

## Installation
```
composer install
```
## Usage

#### API
```
http://localhost/api/v1/current/[location]
http://localhost/api/v1/current/[location]?provider=openweathermap
```
#### CLI
```
php bin/console weather:current [location]
php bin/console weather:current [location] --provider=heweather
```

## Features
The following features are provided:
 - [x] Simple Restful API for apps to call, with JSON return format.
 - [x] Collects weather info from weather providers.
 - [x] Cache weather info per day per location. This is useful for apps that have massive user-base. e.g. digital signage, tourist apps.
 - [x] Support multiple weather providers and convert weather info into the same format.

Supported weather providers:
 - [x] OpenWeatherMap
 - [x] HeWeather

Supported weather information type:
 - [x] Current weather
 - [] Forcast
