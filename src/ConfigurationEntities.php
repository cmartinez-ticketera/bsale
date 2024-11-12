<?php

namespace ticketeradigital\bsale;

use Illuminate\Support\Str;

enum ConfigurationEntities
{
    case Discounts;
    case Offices;
    case Users;
    case Coins;
    case DocumentTypes;
    case ShippingTypes;
    case SaleConditions;

    public function getEndpoint(): string
    {
        $name = Str::of($this->name)->snake();

        return "/v1/$name.json";
    }
}
