<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatisticRepository::class)
 */
class Statistic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $browser;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $url_from;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $url_to;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $os;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(?string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getUrlFrom(): ?string
    {
        return $this->url_from;
    }

    public function setUrlFrom(?string $url_from): self
    {
        $this->url_from = $url_from;

        return $this;
    }

    public function getUrlTo(): ?string
    {
        return $this->url_to;
    }

    public function setUrlTo(?string $url_to): self
    {
        $this->url_to = $url_to;

        return $this;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
