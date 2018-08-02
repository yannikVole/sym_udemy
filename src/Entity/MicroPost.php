<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 *
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=280)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function getId()
    {
        return $this->id;
    }



}
