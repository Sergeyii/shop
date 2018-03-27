<?php

namespace api\formatters;

interface ApiFormatterInterface
{
    public function format(): array;
}