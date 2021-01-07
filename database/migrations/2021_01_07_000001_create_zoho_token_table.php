<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZohoTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zoho_tokens')) {
            Schema::create('zoho_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('access_token');
                $table->string('refresh_token');
                $table->string('api_domain');
                $table->string('token_type');
                $table->string('expires_in');
                $table->datetime('expires_at');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('zoho_tokens')) {
            Schema::dropIfExists('zoho_tokens');
        }
    }
}
