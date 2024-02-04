<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Agency;
use App\Models\City;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;


    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(Admin $admin, $ability)
    {

        if ($admin->role == 'god') {
            return true;
        }

    }

    public function create(Admin $admin, $item, $abort = true, $option = null)
    {

        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }
        if (empty($message) && $item)
            switch ($item) {
                case     Agency::class :
                    $res = $admin->hasAccess('create_agency');
                    break;
                case    Repository::class:
                    $res = $admin->hasAccess('create_repository');
                    break;
            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;
        return false;
    }

    public function edit(Admin $admin, $item, $abort = true, $option = null)
    {

        if (!$item) {
            $message = __("item_not_found");

        }
        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }

        if ($item->status == 'block') {
            $message = __("item_is_blocked");
        }

        if (empty($message) && $item)
            switch ($item) {
                case  $item instanceof Agency  :
                    $res = $admin->hasAccess('edit_agency');
                    if ($res) {
                        $myAgency = Agency::find($admin->agency_id);
                        if (!$myAgency)
                            $res = false;
                        elseif ($myAgency->level == '1')
                            $res = $myAgency->id == $item->id || in_array($myAgency->province_id, $item->access ?? []);
                        elseif ($myAgency->level == '2')
                            $res = $myAgency->id == $item->id || in_array($myAgency->id, $item->access ?? []);
                        elseif ($myAgency->level == '3')
                            $res = $myAgency->id == $item->id;

                    }

                    break;
                case  $item instanceof Repository  :
                    $res = $admin->hasAccess('edit_repository');


                    break;

            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;
        return false;
    }
}
