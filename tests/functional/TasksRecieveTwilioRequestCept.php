<?php
$I = new TestGuy($scenario);
$I->wantTo('Recieve POST Twilio Request');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twiliorequest/?format=json", ["To" => "33626333841"]);
$I->seeResponseCodeIs(200);
$I->wantTo('Find the TwilioRequest Inserted');
$I->seeInDatabase('twiliorequests', array('To' => '33626333841'));
