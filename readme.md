# SPTestProject #

### About ###

Simplified implementation of notification module. 
Project shows how it can be done using strategies.
By default all strategies will be used, but it's possible to narrow number of strategies that would be used (see below). 


### Adding to queue ###
```
$queue = new NotificationProducer();


//For use all available strategies
$queue->push(
    [
        'user_id' => 3,
        'message' => 'Lorem ipsum',
    ]
)

//For use only selected strategies
$queue->push(
    [
        'user_id' => 3,
        'message' => 'Lorem ipsum',
        'strategies' => [
            NotificationEmailStrategy::class,
        ],
    ]
)
```

### Consuming from queue ###

`NotificationConsumer.php` - simplified consumer (Implemented only part that receives message)

