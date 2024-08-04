# Symfony notifier
Symfony based service to send notifications to specific recipient

## Setup
Create .env.local by making a copy of .env and update all the necessary variables 
```
docker compose up -d
```

## Usage (in docker)

If you have php8.3 installed locally, just remove `docker compose exec php php` prefix. 

__Send Email__
```
 docker compose exec php php bin/console notification:email:send your@email.com "test mail notification v1" "hello notification v1" "tempuser"
```  
 __Send SMS__
```
 docker compose exec php php bin/console notification:sms:send +48123456789 "test sms notification v2" "tempuser"
``` 
 __Send Push Notification__
 ```
 docker compose exec php php bin/console notification:push:send <device_token> "test push notification v3" "tempuser"
 ```
 
 __Multiple channel notification__
```
 docker compose exec php php bin/console notification:multiple:send --to_address=your@email.com --to_phone=+48123456789 --subject="email server down warning" --body="your server is down" --userId="tempuser"
```

### Notes
Pushy, while implemented using docs, was not setup and not tested. May not work.

### TODO
- [ ] Logging - use better (for development purposes). Use https://symfony.com/doc/current/logging.html#monolog for now
- [ ] Queue/retry send - At the moment messages are send and not retried. Use https://symfony.com/doc/current/messenger.html to support retrying.
- [ ] Throttling - once we have queue, make the consumer use the leaky bucket to support.
Worth to note I didn't check the implemented services for their api limits, so throttling settings might have to be done per service.
- [ ] Usage tracking - no users implemented yet, so usage tracking left for later. Use doctrine table to store usage for now.