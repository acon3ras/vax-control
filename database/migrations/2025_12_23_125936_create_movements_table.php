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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', [
                'RECEIPT', 'DISPATCH', 'ADMINISTRATION', 'TRANSFER', 
                'QUARANTINE_MOVE', 'QUARANTINE_RELEASE', 
                'WASTAGE', 'BREAKAGE', 'LOSS', 'EXPIRY', 'INVENTORY_ADJUSTMENT'
            ]);
            $table->foreignId('source_location_id')->nullable()->constrained('locations');
            $table->foreignId('destination_location_id')->nullable()->constrained('locations');
            $table->string('reference_number')->nullable()->comment('Invoice or internal document ID');
            $table->string('notes')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING_APPROVAL', 'POSTED', 'REJECTED', 'CANCELLED'])->default('DRAFT');
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
