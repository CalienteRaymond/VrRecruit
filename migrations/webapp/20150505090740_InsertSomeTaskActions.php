<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['Vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\TaskAction;

class InsertSomeTaskActions extends Ruckusing_Migration_Base
{
    
    private function addTaskAction($task_id, $description, $message)
    {
        $t = TaskAction::instanceWith([
            'task_id' => $task_id,
            'description' => $description,
            'message' => $message,
        ]);
        $t->save();
    }

    public function up()
    {
        
        $this->addTaskAction(1, 'Assignee has answered the task', 'Not Sure');
        $this->addTaskAction(1, 'Assignee has accepted the task', 'Ok for the job');
        $this->addTaskAction(2, 'Assignee has refused the task', 'No, not available');
        $this->addTaskAction(3, 'Assignee has accepted the task', 'Of course');
        $this->addTaskAction(4, 'Assignee has accepted the task', 'Ok for the job');
        $this->addTaskAction(1, 'Assignee has completed the task', 'Job done');
        
        
    }//up()

    public function down()
    {
    }//down()
}
