<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\WorkflowRule;

class WorkflowRuleBuilderTest extends TestCase
{
    /** @test */
    public function where_activo_is_rewritten_to_activa()
    {
        // Build a query using the legacy column name 'activo'
        $query = WorkflowRule::where('activo', true);

        // Convert to SQL and assert the column name used is 'activa'
        $sql = $query->toSql();

        $this->assertStringContainsString('`activa`', $sql);
        // Ensure 'activo' does not appear in the compiled SQL
        $this->assertStringNotContainsString('`activo`', $sql);
    }
}
