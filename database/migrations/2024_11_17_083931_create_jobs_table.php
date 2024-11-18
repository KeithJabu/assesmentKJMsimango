<?php

use App\AssessmentIncludes\Classes\AssessmentInterface;
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
        Schema::create('bg_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->string('method')->nullable();
            $table->text('parameters')->nullable();
            $table->enum('status', AssessmentInterface::ASSESSMENT_STATUS);
            $table->integer('retry_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bg_jobs');
    }
};
