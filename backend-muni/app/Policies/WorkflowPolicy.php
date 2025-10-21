<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workflow;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkflowPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Super admin and admin can view all
        if ($user->hasRole(['superadministrador', 'administrador'])) {
            return true;
        }
        
        return $user->can('gestionar_workflows') || $user->can('ver_workflows');
    }

    public function view(User $user, Workflow $workflow): bool
    {
        // Super admin and admin can view all
        if ($user->hasRole(['superadministrador', 'administrador'])) {
            return true;
        }
        
        // Can view if has general permission or is from same gerencia
        return $user->can('ver_workflows') || 
               ($workflow->gerencia_id && $user->gerencia_id === $workflow->gerencia_id);
    }

    public function create(User $user): bool
    {
        // Super admin and admin can create
        if ($user->hasRole(['superadministrador', 'administrador'])) {
            return true;
        }
        
        return $user->can('crear_workflows');
    }

    public function update(User $user, Workflow $workflow): bool
    {
        // Super admin and admin can update all
        if ($user->hasRole(['superadministrador', 'administrador'])) {
            return true;
        }
        
        // Can update if has permission and (is creator or from same gerencia)
        return $user->can('editar_workflows') && 
               ($workflow->created_by === $user->id || 
                ($workflow->gerencia_id && $user->gerencia_id === $workflow->gerencia_id));
    }

    public function delete(User $user, Workflow $workflow): bool
    {
        // Super admin and admin can delete all
        if ($user->hasRole(['superadministrador', 'administrador'])) {
            return true;
        }
        
        // Can delete if has permission and is creator or has admin role
        return $user->can('eliminar_workflows') && 
               ($workflow->created_by === $user->id || $user->hasRole(['gerente']));
    }

    public function duplicate(User $user, Workflow $workflow): bool
    {
        // Can duplicate if can create and view the original
        return $this->create($user) && $this->view($user, $workflow);
    }

    public function manage(User $user): bool
    {
        return $user->can('gestionar_workflows') || $user->hasRole(['super-admin', 'gerente']);
    }
}