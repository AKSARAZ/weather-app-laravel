<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('installation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang meminta
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_phone');
            $table->string('city'); // Kota untuk prediksi cuaca
            $table->integer('daily_energy_wh'); // Kebutuhan energi harian
            $table->string('status')->default('Pending'); // Misal: Pending, Processed, Completed
            $table->integer('recommended_panel_wp')->nullable(); // Hasil prediksi admin
            $table->integer('recommended_battery_wh')->nullable(); // Hasil prediksi admin
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installation_requests');
    }
};
