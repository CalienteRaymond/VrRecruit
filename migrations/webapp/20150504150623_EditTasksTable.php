<?php

class EditTasksTable extends Ruckusing_Migration_Base
{
    public function up()
    {
    	$this->add_column('tasks','description','text');
    	
        $this->add_column(                                                 // 0-pending | 1-Accepted | 2-Refused
                    'tasks',
                    'state',
                    'tinyinteger', 
                    [
                        'null' => false,
                        'default' => 0
                    ]);					                                    
    	
        $this->add_column(                                                 // 0-no | 1-yes
                    'tasks',
                    'complete_claimed',
                    'tinyinteger',
                    [
                        'null' => false,
                        'default' => 0
                    ]); 		
    	
        $this->add_column(                                                 // 0-no | 1-yes
                    'tasks',
                    'complete_confirmed',
                    'tinyinteger',
                    [
                        'null' => false,
                        'default' => 0
                    ]);

    }//up()

    public function down()
    {
    }//down()
}
