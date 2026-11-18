<?php
declare(strict_types=1);

namespace FypPilot;

interface Greeter
{
    public function greet(): string;
}

class FriendlyGreeter implements Greeter
{
    public function greet(): string
    {
        return 'Hello';
    }
}

class GreetingController
{
    public function __construct(
        private Greeter $greeter
    ) {}

    public function show(): string
    {
        return $this->greeter->greet();
    }
}
