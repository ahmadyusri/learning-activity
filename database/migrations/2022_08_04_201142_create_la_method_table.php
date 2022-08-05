<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection   = '';
    protected $table_name   = '';

    protected $table_name_user  = '';

    public function __construct()
    {
        $object_MainModel = new \App\Models\LA\LAMethod();
        $this->connection   = $object_MainModel->getConnectionName();
        $this->table_name   = $object_MainModel->getTable();

        $object_UserModel = new \App\Models\User();
        $this->table_name_user  = $object_UserModel->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::connection($this->connection)->create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Method Name');
            $table->tinyInteger('order')->unsigned()->comment('Method Order');

            $table->bigInteger('created_by')->unsigned()->nullable()->comment('User who created');
            $table->bigInteger('updated_by')->unsigned()->nullable()->comment('User who updated');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')
                ->references('id')
                ->on($this->table_name_user)
                ->onDelete('set null');
            $table->foreign('updated_by')
                ->references('id')
                ->on($this->table_name_user)
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists($this->table_name);
    }
};
