# Senso-api

This project is a simulator of api.
You can configure routes with its headers method and parameters, and responses.

## configuration

You need to create a virtual host and add inside your hosts file for example api.simulator.com

Now you can call api.simulator.com to simulate api  

### config/config.php

```php
<?php return [
    'container' => [
        'resources' => [
            '/v1/some-api/foo' => [
                'options' => [
                    'GET'
                ],
                'constraints' => [
                    'mandatory' => [
                    ]
                ]
            ]
        ]
    ]
];
```

### config/responses.php

```php
<?php return [
    '/v1/some-api/foo' => [
        'ciao' => 'mondo'
    ],
];
```
