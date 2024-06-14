<?php
$alias = 'App';
$proxy = \Raizdev\Framework\Proxy\App::class;
$manager = new Statical\Manager();
$manager->addProxyInstance($alias, $proxy, $app);
