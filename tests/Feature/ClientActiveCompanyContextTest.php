<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Consortium;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientActiveCompanyContextTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_admin_can_list_clients_using_last_active_company_context(): void
    {
        $user = User::factory()->create();
        $role = Role::query()->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $consortium = Consortium::query()->create([
            'name' => 'Consorcio Test',
            'is_active' => true,
        ]);

        $activeCompany = Company::query()->create([
            'consortium_id' => $consortium->id,
            'manager_id' => null,
            'name' => 'Empresa Activa',
            'is_active' => true,
        ]);

        $otherCompany = Company::query()->create([
            'consortium_id' => $consortium->id,
            'manager_id' => null,
            'name' => 'Empresa Secundaria',
            'is_active' => true,
        ]);

        $user->update(['last_active_company_id' => $activeCompany->id]);

        DB::table('company_user')->insert([
            'user_id' => $user->id,
            'company_id' => null,
            'role_id' => $role->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'id' => (string) Str::uuid(),
            'commercial_name' => 'Cliente Activo',
            'business_name' => 'Cliente Activo SA',
            'tax_id' => '20123456789',
            'technical_contact' => 'Contacto Activo',
            'email' => 'activo@example.com',
            'company_id' => $activeCompany->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'id' => (string) Str::uuid(),
            'commercial_name' => 'Cliente Ajeno',
            'business_name' => 'Cliente Ajeno SA',
            'tax_id' => '20987654321',
            'technical_contact' => 'Contacto Ajeno',
            'email' => 'ajeno@example.com',
            'company_id' => $otherCompany->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/clients');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.company_id', $activeCompany->id)
            ->assertJsonPath('data.0.commercial_name', 'Cliente Activo');
    }
}
