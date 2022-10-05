<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalLineItemsPriceAndTotalAdditionalChargesPriceFieldOnSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('total_line_items_price')->default(0);
            $table->unsignedBigInteger('total_additional_charges_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn([
                'total_line_items_price',
                'total_additional_charges_price',
            ]);
        });
    }
}
