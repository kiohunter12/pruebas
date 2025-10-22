# Copilot Instructions for Municipal Document Management System

## Project Overview
- Laravel 11 backend for municipal document management system with customizable workflows
- Architecture focuses on scalable document processing and role-based access control
- Key components: expedientes (documents), workflows, gerencias (departments), permissions

## Essential Project Structure
```
app/
├── Actions/         # Core business logic actions (CreateWorkflowAction, ApproveProcedureAction)
├── Models/          # Eloquent models with relationships
├── Services/        # Business logic services
└── Repositories/    # Data access layer
```

## Critical Workflows

### Document Management Flow
1. Documents enter through `mesa-partes` (front desk)
2. Auto-assigned to departments via `workflow_rules` table
3. Progress tracked in `expediente_workflow_progress`
4. Documents attached through `documentos_expediente`

### Permission Structure
- Uses Spatie Permission with custom middleware
- 7 predefined roles (superadmin → citizen)
- 65+ granular permissions organized by module
- See `config/permission.php` for configuration

## Key Development Patterns

### Creating New Steps in Workflows
```php
// Example in app/Actions/CreateStepAction.php
$step = WorkflowStep::create([
    'workflow_id' => $workflowId,
    'nombre' => $nombre,
    'orden' => $nextOrder,
    'gerencia_responsable_id' => $gerenciaId
]);
```

### Department Hierarchy
- Uses self-referential relationship in `gerencias` table
- Parent-child relationships with unlimited nesting
- Always validate parent_id to prevent circular references

## Important Commands
```bash
# Development setup
composer install
php artisan migrate:fresh --seed

# Generate test data
php artisan db:seed --class=ExpedientesSeeder

# Clear workflow cache
php artisan cache:clear
php artisan permission:cache-reset
```

## Testing Conventions
- Unit tests in `tests/Unit/`
- Feature tests in `tests/Feature/`
- Use `RefreshDatabase` trait for DB tests
- Factories available for all main models

## Architecture Guidelines
1. Use Actions for complex business logic
2. Repository pattern for database queries
3. Service layer for reusable business rules
4. Policy-based authorization for permissions

## Common Gotchas
- Workflow transitions require valid from/to steps
- Department hierarchy needs careful parent validation
- Permission checks cascade through role hierarchy
- Document upload requires proper mime validation

## Performance Considerations
- Use eager loading for workflow/document relationships
- Cache permission checks where possible
- Implement cursor pagination for large result sets
- Use queues for document processing tasks