<?php

namespace App\Service;

class NameGenerator
{
    public function getName() {
        $quotes = ['paco', 'pepe', 'josefina'];
        return $quotes[array_rand($quotes)];
    }
}