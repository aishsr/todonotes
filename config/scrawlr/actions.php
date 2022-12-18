<?php declare(strict_types = 1);

/**
 * Actions
 *
 * Action and action handling configuration.
 */

return [

    'actions_send_user_emails' => env('ACTIONS_SEND_EMAILS', false),

    'actions_are_queued' => env('ACTIONS_ARE_QUEUED', true),
];
