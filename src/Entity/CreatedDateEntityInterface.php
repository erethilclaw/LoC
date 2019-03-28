<?php

namespace App\Entity;

interface CreatedDateEntityInterface {
	public function setCreated(\DateTimeInterface $created): CreatedDateEntityInterface;
}