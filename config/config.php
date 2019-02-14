<?php

/*
 |-------------------------------------------------------------------------
 | "Roles" config for scaffolding.
 |-------------------------------------------------------------------------
 |
 | You can replace this conf file with config/amranidev/config.php
 | to let scaffold-interface interact with "Roles".
 |
 */
return [

		'env' => [
        	'local',
    	],

		'package' => 'Roles',

		'model' => base_path() . '/Walksoft/Roles/src',

        'views' => base_path() . '/Walksoft/Roles/resources/views',

        'controller' => base_path() . '/Walksoft/Roles/src/Http/Controllers',

        'migration' => base_path() . '/Walksoft/Roles/database/migrations',

		'database' => '/Walksoft/Roles/database/migrations',

	   	'routes' => base_path() . '/Walksoft/Roles/routes/web.php',

	   	'controllerNameSpace' => 'Walksoft\Roles\\Http\\Controllers',

	   	'modelNameSpace' => 'Walksoft\Roles',

		'loadViews' => 'Roles',

	   ];
