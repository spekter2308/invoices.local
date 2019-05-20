<?php

namespace App\Policies;

use App\InvoiceItemName;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create odel= invoice item names.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the odel= invoice item name.
     *
     * @param  \App\User $user
     * @param  \App\odel=InvoiceItemName  $odel=InvoiceItemName
     * @return mixed
     */
    public function update(User $user, InvoiceItemName $itemName)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the odel= invoice item name.
     *
     * @param  \App\User $user
     * @param  \App\odel=InvoiceItemName  $odel=InvoiceItemName
     * @return mixed
     */
    public function delete(User $user, InvoiceItemName $itemName)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        return false;
    }

}