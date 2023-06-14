<?php

namespace App\Utils;

use Shopify\Auth\Session;
use Shopify\Auth\SessionStorage;

class ShopifySessionStorage implements SessionStorage
{
    public function storeSession( Session $session ): bool
    {
        return true;
    }

    public function loadSession( string $sessionId ): ?Session
    {
        return null;
    }

    public function deleteSession( string $sessionId ): bool
    {
        return true;
    }
}
