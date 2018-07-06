<?php

namespace PrionUsers\Traits;

use Exceptions\UserException;

/**
 * This file is part of PrionUsers.
 *
 * It checks if users are active, their permissions and
 * if
 *
 * Class PrionUsersTraits
 * @package PrionUsers\Traits
 */
class PrionUsersTraits
{

    /**
     * Check if User is Active
     *
     */
    public function active ()
    {
        if (!$this->active)
            throw new UserException("User is not active");

        if (!$this->deleted)
            throw new UserException("User is deleted");

        return true;
    }


    /**
     * Check if User has Account with Access
     *
     */
    public function hasAccountAccess ($account_id)
    {
        $account_ids = $this->accountIdsCached();
        if (in_array($account_id, $account_ids))
            return true;

        reutrn false;
    }


    /**
     * Find All User Account IDs
     *
     * @return mixed
     */
    public function accounts ()
    {
        $table = config('prionusers.account_users');
        return $this
            ->belongsToMany( "PrionUsers\Models\Account", $table, 'user_id', 'account_id' )
            ->where($table . '.verified', '1');
    }


    /**
     * Find all User Account Ids
     *
     */
    public function accountIds ()
    {
        return $this
            ->belongsTo( "PrionUsers\Models\AccountUser", "user_id" )
            ->where('verified', 1);
    }


    /**
     * Pull Cached User Account Ids
     *
     * @return mixed
     */
    public function accountIdsCached ()
    {
        $cacheKey = 'prionusers:user_account_ids_' . $this->getKey();

        if (! config('prionusers.use_cache')) {
            return $this->accountIds()->get()->toArray();
        }

        return cache()->remember($cacheKey, config('cache.ttl', 60), function () {
            return $this->accountIds()->get()->toArray();
        });
    }


    /**
     * Pull Cached User Accounts
     *
     * @return mixed
     */
    public function accountsCached ()
    {
        $cacheKey = 'prionusers:user_accounts_' . $this->getKey();

        if (! config('prionusers.use_cache')) {
            return $this->accounts()->get();
        }

        return cache()->remember($cacheKey, config('cache.ttl', 60), function () {
            return $this->accounts()->get()->toArray();
        });
    }
}