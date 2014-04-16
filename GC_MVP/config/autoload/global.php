<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'epals' => array(
        'sis_server' => 'http://dev01.neuedu.dev.ec2.epals.net:8080/sis/',
        'pm_server' => 'http://dev02.neuedu.dev.ec2.epals.net:8080/BasicESB',
        'elasticSearch_server' => 'http://apidev.dev.epals.com:9200',
        'tenant' => 'epals.com',
    ),
);
