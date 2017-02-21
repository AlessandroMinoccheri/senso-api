# configuration

## config/config.php

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

## config/responses.php

```php
<?php return [
    '/v1/some-api/foo' => [
        'ciao' => 'mondo'
    ],
];
```
