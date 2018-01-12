<?php
namespace Theme\Database;

use Garung\Database\Adapter\DBManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateTestTable extends DBManager
{
    public $table = 'test';

    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (!Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->create($table_name, function($table){
                $table->increments('id');
                $table->integer('post_id')->unique();
                $table->text('tags');
                $table->timestamps();
            });
        }       
    }

    public function down() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->drop($table_name);
        }
    }
}
