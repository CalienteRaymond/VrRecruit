<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['Vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\TwilioRequest;

class InsertSomeTwilioRequests extends Ruckusing_Migration_Base
{
    
    private function addTwilioRequest($to, $body)
    {
        $t = TwilioRequest::instanceWith([
            'To' => $to,
            'Body' => $body,
        ]);
        $t->save();
    }

    public function up()
    {
        
        $this->addTwilioRequest('+55 555-555-555', 'Not Sure');
        $this->addTwilioRequest('+55 555-555-555', 'Ok for the job');
        $this->addTwilioRequest('+55 555-555-555', 'No, not available');
        $this->addTwilioRequest('+55 555-555-555', 'Of course');
        $this->addTwilioRequest('+33626333841', 'Ok for the job');
        $this->addTwilioRequest('+55 555-555-555', 'Job done');
        
    }//up()

    public function down()
    {
    }//down()
}
