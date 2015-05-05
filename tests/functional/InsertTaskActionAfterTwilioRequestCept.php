<?php
$I = new TestGuy($scenario);
$I->wantTo('Insert Relevant TaskAction after Recieving TwilioRequest');

// Let's Create a Task action - Acceptance - following Twilio Request
$task = $I->haveTask(['assigned_phone' => '+33626333841', 'state' => 0]);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twiliorequest/?format=json", ["To" => "+33626333841", "Body" => "Yes"]);
$I->seeResponseCodeIs(200);

$I->seeInDatabase('tasksactions', array('task_id' => $task->id));
$I->seeInDatabase('tasks', array('id' => $task->id, "state" => 1));

// Let's Create a Task action - Refuse - following Twilio Request
$task = $I->haveTask(['assigned_phone' => '+33626333842', 'state' => 0]);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twiliorequest/?format=json", ["To" => "+33626333842", "Body" => "No"]);
$I->seeResponseCodeIs(200);

$I->seeInDatabase('tasksactions', array('task_id' => $task->id));
$I->seeInDatabase('tasks', array('id' => $task->id, "state" => 2));

// Let's Create a Task action - Complete - following Twilio Request
$task = $I->haveTask(['assigned_phone' => '+33626333843', 'state' => 0]);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twiliorequest/?format=json", ["To" => "+33626333843", "Body" => "Done"]);
$I->seeResponseCodeIs(200);

$I->seeInDatabase('tasksactions', array('task_id' => $task->id));
$I->seeInDatabase('tasks', array('id' => $task->id, "complete_claimed" => 1));
