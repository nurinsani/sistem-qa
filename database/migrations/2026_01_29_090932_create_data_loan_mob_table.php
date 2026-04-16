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
        Schema::create('data_loan_mob', function (Blueprint $table) {
            $table->dateTime('buss_date');
            $table->string('code_kel', 16);
            $table->string('no_anggota', 16);
            $table->string('cif', 10);
            $table->string('Cust_Short_name', 40);
            $table->string('Deal_type', 3);
            $table->string('suffix', 2);
            $table->double('bagi_hasil');
            $table->integer('Tenor');
            $table->bigInteger('Plafond');
            $table->bigInteger('os');
            $table->bigInteger('saldo_margin');
            $table->bigInteger('angsuran');
            $table->bigInteger('pokok');
            $table->bigInteger('ijaroh');
            $table->bigInteger('bulat');
            $table->string('run_tenor', 3);
            $table->string('ke', 2);
            $table->string('usaha', 3);
            $table->string('nama_usaha', 30);
            $table->string('unit', 4);
            $table->dateTime('tgl_wakalah');
            $table->date('tgl_akad');
            $table->date('tgl_murab');
            $table->date('next_schedule')->nullable();
            $table->date('maturity_date');
            $table->date('last_payment')->nullable();
            $table->string('hari', 10);
            $table->string('cao', 10);
            $table->string('USERID', 8)->nullable();
            $table->string('status', 20);
            $table->string('status_usia', 20);
            $table->string('status_app', 30);
            $table->string('gol', 10)->nullable();
            $table->integer('deal_produk')->nullable();

            $table->primary(['cif', 'unit', 'code_kel']);

            $table->index('no_anggota');
            $table->index('code_kel');
            $table->index('hari');
            $table->index('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_loan_mob');
    }
};
