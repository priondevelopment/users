<?php

namespace PrionUsers\Middleware\Access;

/**
 * Admin Credentials Required to Access Route
 *
 * @license MIT
 * @package Users
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class Admin
{
    /**
     * A valid API Connection is required, credentials must be marked
     * for internal use.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = $request->

        // Is not Interal Credential
        if (!$token OR !Token::token()->credentials->internal)
            throw new Exceptions\ReturnException(515);

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }

}