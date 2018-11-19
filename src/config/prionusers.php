<?php

return [

    'use_cache' => true,

    'middleware' => [
        'register' => true,
    ],

    'base_path' => [
        'users' => 'user',
        'accounts' => 'account',
    ],

    /*
    |--------------------------------------------------------------------------
    | PrionUsers Tables
    |--------------------------------------------------------------------------
    |
    | Tables are used to store user and account data for Prion Development
    | Users package.
    |
    */

    'tables' => [
        /**
         * All Account Tables
         *
         */
        'accounts' => 'accounts',

        /**
         * Create Account Group of Permissions
         *
         */
        'account_group' => 'account_groups',

        /**
         * Associate Account Permissions with Account Group
         *
         */
        'account_group_permission' => 'account_group_permissions',

        /**
         * Permissions for Users in Accounts
         *
         */
        'account_permissions' => 'account_permissions',

        /**
         * Account User Tables
         *
         */
        'account_users' => 'account_users',

        /**
         * Associate account user permissions with users
         *
         */
        'account_user_permissions' => 'account_user_permissions',


        /**
         * Addresses for Users or Accounts
         *
         */
        'addresses' => 'addresses',


        /**
         * Phone Numbers for Users or Accounts
         *
         */
        'phones' => 'phones',

        /**
         * All Users Tables
         *
         */
        'users' => 'users',


        /**
         * Associate Emails with Uses
         *
         */
        'user_emails' => 'user_emails',


        /**
         * User Verification Codes
         *
         */
        'user_verification_codes' => 'user_verification_codes',
    ],
];