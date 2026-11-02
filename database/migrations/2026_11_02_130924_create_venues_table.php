<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['community_hall', 'sports_venue', 'public_venue', 'conference_center', 'outdoor_space']);
            $table->text('description');
            $table->string('location'); // e.g. Lugogo, Kamwokya, Kabalagala
            $table->string('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('capacity');
            $table->decimal('booking_fee', 10, 2)->comment('Base fee in UGX');
            $table->decimal('hourly_rate', 10, 2)->nullable()->comment('Per-hour rate in UGX');
            $table->decimal('full_day_rate', 10, 2)->nullable()->comment('Full-day rate in UGX');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable(); // additional images
            $table->json('event_types')->nullable(); // wedding, sports, workshop, party, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->time('open_time')->default('07:00:00');
            $table->time('close_time')->default('22:00:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
