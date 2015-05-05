<?php

use Vreasy\Models\TwilioRequest;
use Vreasy\Models\Task;
use Vreasy\Models\TaskAction;


class Vreasy_TwilioRequestController extends Vreasy_Rest_Controller
{
    protected $twilioRequest, $twilioRequests;

    public function preDispatch()
    {
        parent::preDispatch();
        $req = $this->getRequest();
        $action = $req->getActionName();
        $contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['twilioRequest' => Zend_Json::decode($rawBody)]);
            }
        }
        if($req->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    $this->twilioRequests = TwilioRequest::where([]);
                    break;
                case 'new':
                    $this->twilioRequest = new TwilioRequest();
                    break;
                case 'create':
                    $this->twilioRequest = TwilioRequest::instanceWith($req->getParam('twilioRequest'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $this->twilioRequest = TwilioRequest::findOrInit($req->getParam('id'));
                    break;
            }
        }

        if( !in_array($action, [
                'index',
                'new',
                'create',
                'update',
                'destroy'
            ]) && !$this->twilioRequests && !$this->twilioRequest->id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }

    }

    /**
     * Will use the Tel number assigned by twilio to find a relevant task, then analyse the body to determine details
     * Rule 1: A single Yes or Equiv create a TaskAction for the last Updated Tak for that phone number
     * Rule 2: An already accepted or refused task cannot be updated again. Task need to be re-sent and return to pending state
     *
     * TODO: engine to normalize phone numbers
     * 
     */
    private function setTaskActions()
    {
        $relevant_tasks = Task::where(["assigned_phone" => $this->twilioRequest->To, "state" => 0]);
        if(count($relevant_tasks) === 0) return;
        $last_task = end($relevant_tasks);
        //\Codeception\Util\Debug::debug($last_task);
        if(preg_match("/\byes\b|\bsi\b|\bok\b|\bsure\b|\bof course\b/i", $this->twilioRequest->Body, $matches))
        {
            $taskaction = TaskAction::instanceWith([
                'task_id' => $last_task->id,
                'description' => 'Accepted',
                'message' => $this->twilioRequest->Body
            ]);
            if($taskaction->save()) 
            {
                $last_task->state = 1; $last_task->save();
            }
            
        }
        if(preg_match("/\bno\b|\bnope\b|\bnot possible\b|\bcannot\b|\bsorry\b/i", $this->twilioRequest->Body, $matches))
        {
            $taskaction = TaskAction::instanceWith([
                'task_id' => $last_task->id,
                'description' => 'Refused',
                'message' => $this->twilioRequest->Body
            ]);
            if($taskaction->save())
            {
                $last_task->state = 2; $last_task->save();
            }

            
        }

        if(preg_match("/\bDone\b|\bcompleted\b/i", $this->twilioRequest->Body, $matches))
        {
            \Codeception\Util\Debug::debug("FOUND Complete");
            $taskaction = TaskAction::instanceWith([
                'task_id' => $last_task->id,
                'description' => 'Task Complete',
                'message' => $this->twilioRequest->Body
            ]);
            if($taskaction->save())
            {
                $last_task->complete_claimed = 1; $last_task->save();
            }

            
        }

        
    }

    public function indexAction()
    {
        $this->view->twilioRequests = $this->twilioRequests;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->twilioRequests]);
    }

    public function newAction()
    {
        $this->view->twilioRequest = $this->twilioRequest;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->twilioRequest]);
    }

    public function createAction()
    {
        if ($this->twilioRequest->isValid() && $this->twilioRequest->save()) {
            $this->setTaskActions();        // purpose of twilio request treatment is to generate TaskActions
            $this->view->twilioRequest = $this->twilioRequest;
        } else {
            $this->view->errors = $this->twilioRequest->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function showAction()
    {
        $this->view->twilioRequest = $this->twilioRequest;
        $this->_helper->conditionalGet()->sendFreshWhen(
            ['etag' => [$this->twilioRequest]]
        );
    }

    public function updateAction()
    {
        TwilioRequest::hydrate($this->twilioRequest, $this->_getParam('TwilioRequest'));
        if ($this->twilioRequest->isValid() && $this->twilioRequest->save()) {
            $this->view->twilioRequest = $this->twilioRequest;
        } else {
            $this->view->errors = $this->twilioRequest->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function destroyAction()
    {
        if($this->twilioRequest->destroy()) {
            $this->view->twilioRequest = $this->twilioRequest;
        } else {
            $this->view->errors = ['delete' => 'Unable to delete resource'];
        }
    }
}
