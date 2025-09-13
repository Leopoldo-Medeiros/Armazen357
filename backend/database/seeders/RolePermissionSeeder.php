<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Permissões para o Sistema do Armazém 357
        $permissions = [
            // Dashboard & Analytics
            ['name' => 'view_dashboard', 'display_name' => 'Ver Dashboard', 'group' => 'dashboard'],
            ['name' => 'view_analytics', 'display_name' => 'Ver Analytics', 'group' => 'dashboard'],

            // Gestão de Usuários
            ['name' => 'view_users', 'display_name' => 'Visualizar Usuários', 'group' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Criar Usuários', 'group' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Editar Usuários', 'group' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Deletar Usuários', 'group' => 'users'],
            ['name' => 'manage_user_roles', 'display_name' => 'Gerenciar Roles de Usuários', 'group' => 'users'],

            // Gestão de Produtos (Grãos de Café)
            ['name' => 'view_products', 'display_name' => 'Visualizar Produtos', 'group' => 'products'],
            ['name' => 'create_products', 'display_name' => 'Criar Produtos', 'group' => 'products'],
            ['name' => 'edit_products', 'display_name' => 'Editar Produtos', 'group' => 'products'],
            ['name' => 'delete_products', 'display_name' => 'Deletar Produtos', 'group' => 'products'],
            ['name' => 'manage_product_categories', 'display_name' => 'Gerenciar Categorias', 'group' => 'products'],

            // Gestão de Estoque
            ['name' => 'view_inventory', 'display_name' => 'Visualizar Estoque', 'group' => 'inventory'],
            ['name' => 'manage_inventory', 'display_name' => 'Gerenciar Estoque', 'group' => 'inventory'],
            ['name' => 'view_inventory_reports', 'display_name' => 'Relatórios de Estoque', 'group' => 'inventory'],

            // Gestão de Pedidos
            ['name' => 'view_orders', 'display_name' => 'Visualizar Pedidos', 'group' => 'orders'],
            ['name' => 'create_orders', 'display_name' => 'Criar Pedidos', 'group' => 'orders'],
            ['name' => 'edit_orders', 'display_name' => 'Editar Pedidos', 'group' => 'orders'],
            ['name' => 'cancel_orders', 'display_name' => 'Cancelar Pedidos', 'group' => 'orders'],
            ['name' => 'process_orders', 'display_name' => 'Processar Pedidos', 'group' => 'orders'],

            // Gestão de Clientes
            ['name' => 'view_customers', 'display_name' => 'Visualizar Clientes', 'group' => 'customers'],
            ['name' => 'create_customers', 'display_name' => 'Criar Clientes', 'group' => 'customers'],
            ['name' => 'edit_customers', 'display_name' => 'Editar Clientes', 'group' => 'customers'],
            ['name' => 'delete_customers', 'display_name' => 'Deletar Clientes', 'group' => 'customers'],

            // Gestão Financeira
            ['name' => 'view_financial', 'display_name' => 'Visualizar Financeiro', 'group' => 'financial'],
            ['name' => 'manage_pricing', 'display_name' => 'Gerenciar Preços', 'group' => 'financial'],
            ['name' => 'view_reports', 'display_name' => 'Visualizar Relatórios', 'group' => 'financial'],

            // Configurações do Sistema
            ['name' => 'view_settings', 'display_name' => 'Visualizar Configurações', 'group' => 'settings'],
            ['name' => 'edit_settings', 'display_name' => 'Editar Configurações', 'group' => 'settings'],
            ['name' => 'manage_system', 'display_name' => 'Gerenciar Sistema', 'group' => 'settings'],

            // Loja Virtual
            ['name' => 'browse_store', 'display_name' => 'Navegar na Loja', 'group' => 'store'],
            ['name' => 'add_to_cart', 'display_name' => 'Adicionar ao Carrinho', 'group' => 'store'],
            ['name' => 'checkout', 'display_name' => 'Finalizar Compra', 'group' => 'store'],
            ['name' => 'view_order_history', 'display_name' => 'Ver Histórico de Pedidos', 'group' => 'store'],

            // Atacado (B2B)
            ['name' => 'view_wholesale_prices', 'display_name' => 'Ver Preços Atacado', 'group' => 'wholesale'],
            ['name' => 'bulk_orders', 'display_name' => 'Pedidos em Volume', 'group' => 'wholesale'],
            ['name' => 'custom_pricing', 'display_name' => 'Preços Personalizados', 'group' => 'wholesale'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Criar Roles do Sistema
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrador',
                'description' => 'Acesso total ao sistema - Dono da empresa',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Gerente/Admin da empresa - Acesso a gestão operacional',
                'permissions' => [
                    'view_dashboard', 'view_analytics',
                    'view_users', 'create_users', 'edit_users',
                    'view_products', 'create_products', 'edit_products', 'manage_product_categories',
                    'view_inventory', 'manage_inventory', 'view_inventory_reports',
                    'view_orders', 'create_orders', 'edit_orders', 'cancel_orders', 'process_orders',
                    'view_customers', 'create_customers', 'edit_customers',
                    'view_financial', 'manage_pricing', 'view_reports',
                    'view_settings', 'edit_settings'
                ]
            ],
            [
                'name' => 'staff',
                'display_name' => 'Funcionário',
                'description' => 'Funcionário da empresa - Acesso operacional limitado',
                'permissions' => [
                    'view_dashboard',
                    'view_products', 'edit_products',
                    'view_inventory', 'manage_inventory',
                    'view_orders', 'process_orders',
                    'view_customers', 'edit_customers'
                ]
            ],
            [
                'name' => 'customer_b2c',
                'display_name' => 'Cliente Varejo',
                'description' => 'Cliente final - Compra no varejo',
                'permissions' => [
                    'browse_store', 'add_to_cart', 'checkout', 'view_order_history'
                ]
            ],
            [
                'name' => 'customer_b2b',
                'display_name' => 'Cliente Atacado',
                'description' => 'Cliente corporativo - Compra no atacado',
                'permissions' => [
                    'browse_store', 'add_to_cart', 'checkout', 'view_order_history',
                    'view_wholesale_prices', 'bulk_orders', 'custom_pricing'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Sincronizar permissões
            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        // Criar usuário Super Admin padrão
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@armazem357.com.br'],
            [
                'name' => 'Administrador Armazém 357',
                'password' => Hash::make('123456'), // Mudar em produção
                'is_active' => true,
            ]
        );

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        $this->command->info('Roles e permissões criadas com sucesso!');
        $this->command->info('Super Admin criado: admin@armazem357.com.br / 123456');
    }
}