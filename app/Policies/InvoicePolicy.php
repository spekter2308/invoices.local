<?php

namespace App\Policies;

use App\User;
use App\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function view(User $user, Invoice $invoice)
    {
        return true;
    }

    /**
     * Determine whether the user can create invoices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role() == 'admin' OR $user->role() == 'user') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function update(User $user, Invoice $invoice)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        elseif ($user->role() == 'user') {
            if ($user->id == $invoice->user_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function delete(User $user, Invoice $invoice)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        if ($user->role() == 'user') {
            if ($user->id == $invoice->user_id) {
                return true;
            }
        }
        return false;
    }

    public function sendMail(User $user, Invoice $invoice)
    {
        if ($user->role() == 'admin') {
            return true;
        }
        if ($user->role() == 'user') {
            if ($user->id == $invoice->user_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function restore(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        //
    }
}
