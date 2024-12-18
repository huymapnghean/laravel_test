<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTypeLabelColumnTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->dropColumn('label_id');
            $table->dropColumn('category_id');
        });

        // Thêm cột roles mới kiểu JSON
        Schema::table('ticket', function (Blueprint $table) {
            $table->json('label_id')->nullable();
            $table->json('category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->dropColumn('label_id');
            $table->dropColumn('category_id');
        });

        // Thêm cột roles mới kiểu JSON
        Schema::table('ticket', function (Blueprint $table) {
            $table->bigInteger('label_id')->nullable();
            $table->bigInteger('category_id')->nullable();
        });
    }
}
