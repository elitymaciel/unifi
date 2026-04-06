<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Corrigir tabela 'routers'
        if (!Schema::hasTable('routers')) {
            if (Schema::hasTable('mikrotiks')) {
                Schema::rename('mikrotiks', 'routers');
            } else {
                Schema::create('routers', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('host');
                    $table->string('username');
                    $table->string('password');
                    $table->integer('port')->default(8728);
                    $table->timestamps();
                });
            }
        }

        // 2. Corrigir tabela 'router_permissions'
        if (!Schema::hasTable('router_permissions')) {
            if (Schema::hasTable('mikrotik_permissions')) {
                Schema::rename('mikrotik_permissions', 'router_permissions');
            } elseif (Schema::hasTable('router_permissions_table')) { // Possível nome incorreto criado em tentativas anteriores
                Schema::rename('router_permissions_table', 'router_permissions');
            } else {
                Schema::create('router_permissions', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->unsignedBigInteger('router_id');
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não é seguro fazer rollback de um reparo de emergência em produção
    }
};
