<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_department()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $department = Department::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $department));

        $response->assertSessionHas('status', 'Department deleted.');
        $this->assertModelMissing($department);
    }
}
