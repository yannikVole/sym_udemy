<?php
/**
 * Created by PhpStorm.
 * User: yanni
 * Date: 7/31/2018
 * Time: 5:24 PM
 */

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{
    private $logger;
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet(string $name){
        $this->logger->info("Greeted $name");
        return "{$this->message} $name";
    }
}