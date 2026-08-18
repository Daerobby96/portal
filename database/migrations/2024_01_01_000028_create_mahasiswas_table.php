<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('nim', 30)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->integer('angkatan')->nullable();
            $table->integer('semester')->default(1);
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'drop_out', 'non_aktif'])->default('aktif');
            $table->decimal('ipk', 4, 2)->nullable();
            $table->integer('sks_total')->default(0);
            $table->integer('masa_studi_bulan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
