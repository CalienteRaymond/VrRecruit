<?php

class AddTasksactionsTable extends Ruckusing_Migration_Base
{
    public function up()
    {
    	$tasksactions = $this->create_table('tasksactions', ['id' => false, 'options' => 'Engine=InnoDB']);
        $tasksactions->column(
            'id',
            'integer',
            [
                'primary_key' => true,
                'auto_increment' => true,
                'null' => false
            ]
        );
        $tasksactions->column('task_id','integer');
        $tasksactions->column('created_at','datetime');
        $tasksactions->column('description','text');
        $tasksactions->column('message','text');
        $tasksactions->finish();
    }//up()

    public function down()
    {
    	$this->drop_table("tasksactions");
    }//down()
}
