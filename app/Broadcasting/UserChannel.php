<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Utils\LogUtil;

class UserChannel
{
    use LogUtil;

    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, $id)
    {
        $this->log('INFO UserChannel::join : ' . json_encode($user));
        return (int) $user->id === (int) $id;
    }
}
