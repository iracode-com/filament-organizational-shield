<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('fullname')->nullable();
            $table->string('mobile')->nullable();
            $table->string('tel')->nullable();
            $table->string('internal_tel')->nullable();
            $table->integer('personnel_code')->nullable();
            $table->string('address')->nullable();
            $table->boolean('receive_email')->default(false);
            $table->boolean('receive_sms')->default(false);
            $table->boolean('receive_messenger')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
