<?php

use App\Classes\Commun\ExtendBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $schema = DB::getSchemaBuilder();
        $schema->blueprintResolver(function ($table, $callback) {
            return new ExtendBlueprint($table, $callback);
        });

        $schema->create('reservations', function (ExtendBlueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
        });

        Bouncer::allow('admin')->to('reservation-create');
        Bouncer::allow('admin')->to('reservation-delete');
        Bouncer::allow('admin')->to('reservation-retrieve');
        Bouncer::allow('user')->to('reservation-create');
        Bouncer::allow('user')->to('reservation-delete');
        Bouncer::Refresh();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('reservations');
        Schema::enableForeignKeyConstraints();
    }
};
