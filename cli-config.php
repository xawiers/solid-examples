<?php

require_once __DIR__.'bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
    [
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
    ]
);

return $helperSet;
