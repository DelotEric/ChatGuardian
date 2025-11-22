<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Abort if the authenticated user does not match one of the allowed roles.
     */
    protected function authorizeRoles(array|string $roles): void
    {
        $allowedRoles = is_array($roles) ? $roles : func_get_args();

        abort_unless(
            auth()->check() && in_array(auth()->user()->role, $allowedRoles, true),
            403,
            'Accès non autorisé pour ce rôle.'
        );
    }

    protected function organization(): Organization
    {
        return Organization::query()->first() ?? Organization::create(Organization::defaults());
    }
}
