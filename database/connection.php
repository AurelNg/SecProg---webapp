<?php
    $config = [
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'witter'
    ];

    $db = new mysqli(
        $config['server'], 
        $config['username'], 
        $config['password'], 
        $config['database']
    );
?>