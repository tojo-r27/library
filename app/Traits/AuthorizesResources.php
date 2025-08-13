<?php

namespace App\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

trait AuthorizesResources
{
    use AuthorizesRequests;

    /**
     * Automatically apply policy abilities to controller resource methods.
     */
    protected function authorizeResource(string $model, ?string $parameter = null, array $options = []): void
    {
        $parameter = $parameter ?: Str::snake(class_basename($model));

        foreach ($this->resourceAbilityMap() as $method => $ability) {
            $this->middleware("can:{$ability},{$parameter}")->only($method);
        }
    }

    /**
     * Map resource methods to ability names.
     */
    protected function resourceAbilityMap(): array
    {
        return [
            'index'   => 'viewAny',
            'show'    => 'view',
            'create'  => 'create',
            'store'   => 'create',
            'edit'    => 'update',
            'update'  => 'update',
            'destroy' => 'delete',
        ];
    }
}
