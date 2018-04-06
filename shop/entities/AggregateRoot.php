<?php

namespace shop\entities;

interface AggregateRoot
{
    public function releaseEvents(): array;
}