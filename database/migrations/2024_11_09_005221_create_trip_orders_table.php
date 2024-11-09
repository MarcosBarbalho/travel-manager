<?php

use App\Enums\TripOrder\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_orders', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('destination');
            $table->string('requester_name');
            $table->foreignId('user_id');
            $table->timestamp('departure_at');
            $table->timestamp('return_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_orders');
    }
};
