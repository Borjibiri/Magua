<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{

    #[OneToMany(targetEntity: "OrderProduct", mappedBy: "order")]
    private $OrderProducts;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $status = "Pending";

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $total_amount = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_created = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?User $user_id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?String
    {
        return $this->status;
    }

    public function setStatus(?String $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?string $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTime $dateCreated): static
    {
        $this->date_created = $dateCreated;

        return $this;
    }
}
