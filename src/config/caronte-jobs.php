<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Conexión de Cola (Queue Connection)
    |--------------------------------------------------------------------------
    |
    | Define la conexión de cola que debe usar este paquete. Por defecto,
    | usa la conexión predeterminada de la aplicación, pero puede ser
    | sobreescrita para usar un Redis/Beanstalk/Database específico.
    |
    */
    'queue_connection' => env('CARONTE_QUEUE_CONNECTION', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Nombre de la Cola (Queue Name)
    |--------------------------------------------------------------------------
    |
    | Puedes especificar un nombre de cola dedicado para tus jobs.
    |
    */
    'queue_name' => env('CARONTE_QUEUE_NAME', 'caronte-jobs'),
];
