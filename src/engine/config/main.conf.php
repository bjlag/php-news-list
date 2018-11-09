<?php

$config[ 'PATH_ROOT' ] = dirname( dirname( dirname( __FILE__ ) ) );
//$config[ 'PATH_LOGS' ] = $config[ 'PATH_ROOT' ] . '/logs';
$config[ 'PATH_PUBLIC' ] = $config[ 'PATH_ROOT' ] . '/public_html';
$config[ 'PATH_TEMPLATES' ] = $config[ 'PATH_ROOT' ] . '/templates';
$config[ 'PATH_CACHE_TEMPLATES' ] = $config[ 'PATH_PUBLIC' ] . '/cache/twig';

$config[ 'DEBUG' ] = false;
$config[ 'TIMEZONE' ] = 'Europe/Moscow';
$config[ 'DATE_FORMAT' ] = 'd.m.Y';