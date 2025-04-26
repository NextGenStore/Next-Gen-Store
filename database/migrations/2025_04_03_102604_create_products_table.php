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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->decimal('price', 10, 2);
//            $table->integer('stock')->default(0);
//            $table->foreignId('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
//            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
//            $table->enum('status', ['active', 'out_of_stock', 'disabled'])->default('active');
            $table->timestamps();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
