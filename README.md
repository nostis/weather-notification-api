# weather-notification-api

API which allows users to subscribe to weather 'newsletter'

Current, functionalities:
- Account registration, account confirmation, password reset
- Easy to extend notification channel (channel on which user will get notifications) - currently only email
- Mails queue - ability to store history and delivering asynchronously

Based on: PHP 8.1, Symfony 5.4, API Platform, Messenger queue (currently based on Doctrine)

Installation:
- composer install
- php bin/console lexik:jwt:generate-keypair
- php bin/console d:s:c
- php bin/console d:s:u -f
- php bin/console app:load-cities

Docker currently is not supported; dockerfiles are only defaults from package installation

Fill .env file:
- DATABASE_URL
- MAILER_DSN
- MESSENGER_TRANSPORT_DSN (can be default)
- APP_MAIL_FROM

Cron:
- */5 * * * * php bin/console app:retry-mail-delivery - command for mails resend

Sources:
- Cities downloaded from: https://simplemaps.com/data/pl-cities
