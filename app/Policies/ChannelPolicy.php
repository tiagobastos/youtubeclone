<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Channel  $channel
     * @return mixed
     */
    public function view(User $user, Channel $channel)
    {
        //
    }

    /**
     * Determine whether the user can create channels.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Channel  $channel
     * @return mixed
     */
    public function update(User $user, Channel $channel)
    {
        return $user->id === $channel->user_id;
    }

    /**
     * Determine whether the user can edit the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Channel  $channel
     * @return mixed
     */
    public function edit(User $user, Channel $channel)
    {
        return $user->id === $channel->user_id;
    }

    /**
     * Determine whether the user can delete the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Channel  $channel
     * @return mixed
     */
    public function delete(User $user, Channel $channel)
    {
        //
    }
}
