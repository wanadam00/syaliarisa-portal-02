<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

/**
 * Application TrustProxies middleware.
 *
 * This class trusts all proxies ("*") which is appropriate for local
 * development with tools like ngrok. If you need to restrict proxies,
 * replace '*' with the specific proxy IPs or CIDR ranges.
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * We do not set this explicitly here to avoid compatibility issues
     * across Laravel/Symfony versions where the constant name/availability
     * may differ. The parent implementation will use reasonable defaults.
     *
     * @var int|null
     */
    // protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
