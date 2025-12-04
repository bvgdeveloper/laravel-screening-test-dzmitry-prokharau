<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('rating', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('auditoria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_multiplier', 5, 2)->default(1.00);
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auditorium_id')->constrained('auditoria')->cascadeOnDelete();
            $table->foreignId('seat_type_id')->constrained('seat_types')->restrictOnDelete();
            $table->string('row_label');
            $table->unsignedInteger('seat_number');
            $table->string('label');
            $table->timestamps();

            $table->unique(['auditorium_id', 'row_label', 'seat_number']);
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->restrictOnDelete();
            $table->foreignId('auditorium_id')->constrained('auditoria')->restrictOnDelete();
            $table->dateTime('start_time')->index();
            $table->dateTime('end_time')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->cascadeOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('show_id')->constrained('shows')->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained('seats')->restrictOnDelete();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->unique(['show_id', 'seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seat_types');
        Schema::dropIfExists('auditoria');
        Schema::dropIfExists('movies');
    }
};
