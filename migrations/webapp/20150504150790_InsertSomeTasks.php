<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['Vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\Task;

class InsertSomeTasks extends Ruckusing_Migration_Base
{
    
    
    private function addTask($delay, $assigned_name, $assigned_phone, $description, $state, $complete_claimed, $complete_confirmed)
    {
        $deadline_str = (new \DateTime("+$delay days"))->format(DATE_FORMAT);
        $t = Task::instanceWith([
            'deadline' => (new \DateTime("+$delay days"))->format(DATE_FORMAT),
            'assigned_name' => $assigned_name,
            'assigned_phone' => $assigned_phone,
            'description' => $description.$deadline_str.' ?',
            'state' => $state,
            'complete_claimed' => $complete_claimed,
            'complete_confirmed' => $complete_confirmed
        ]);
        $t->save();
    }

    public function up()
    {
        

        $this->addTask(1, 'Johen Merits', '+33756894512', 'Pick Up MR.plus at airport at ', 1, 1, 0);
        $this->addTask(2, 'Trisha Eliot', '+33256568945', 'Bring Match ticket Before ', 2, 0, 0);
        $this->addTask(3, 'Astaben Xelt', '+33985451236', 'Fix Heating at Eixaple ', 1, 0, 0);
        $this->addTask(4, 'Alexandre Damiron', '+33626333841', 'Cleanup Gracia Appt before ', 1, 0, 0);

        
    }//up()

    public function down()
    {
    }//down()
}
