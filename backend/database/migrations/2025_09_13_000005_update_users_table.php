<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover a coluna type enum pois vamos usar o sistema de roles
            $table->dropColumn('type');
            
            // Adicionar campos adicionais para perfil do usuário
            $table->string('phone')->nullable()->after('email');
            $table->string('document')->nullable()->after('phone'); // CPF/CNPJ
            $table->enum('document_type', ['cpf', 'cnpj'])->nullable()->after('document');
            $table->text('address')->nullable()->after('document_type');
            $table->string('city')->nullable()->after('address');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('zipcode', 10)->nullable()->after('state');
            $table->json('settings')->nullable()->after('zipcode'); // Configurações personalizadas
            $table->timestamp('last_login_at')->nullable()->after('settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'staff', 'customer'])->default('customer')->after('password');
            
            $table->dropColumn([
                'phone',
                'document',
                'document_type',
                'address',
                'city',
                'state',
                'zipcode',
                'settings',
                'last_login_at'
            ]);
        });
    }
};