<?php

use Vreasy\Models\TaskAction;

class Vreasy_TaskActionController extends Vreasy_Rest_Controller
{
    protected $tasksaction, $tasksactions;

    public function preDispatch()
    {
        parent::preDispatch();
        $req = $this->getRequest();
        $action = $req->getActionName();
        $contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['tasksaction' => Zend_Json::decode($rawBody)]);
            }
        }
        if($req->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    $params = [];
                    if($req->getParam('task_id')) 
                        {
                            $params = ["task_id" => $req->getParam('task_id')];
                        }
                    $this->tasksactions = TaskAction::where($params);
                    break;
                case 'new':
                    $this->tasksaction = new TaskAction();
                    break;
                case 'create':
                    $this->tasksaction = TaskAction::instanceWith($req->getParam('tasksaction'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $this->tasksaction = TaskAction::findOrInit($req->getParam('id'));
                    break;
            }
        }

        if( !in_array($action, [
                'index',
                'new',
                'create',
                'update',
                'destroy'
            ]) && !$this->tasksactions && !$this->tasksaction->id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }

    }

    public function indexAction()
    {
        $this->view->tasksactions = $this->tasksactions;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->tasksactions]);
    }

    public function newAction()
    {
        $this->view->tasksaction = $this->tasksaction;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->tasksaction]);
    }

    public function createAction()
    {
        if ($this->tasksaction->isValid() && $this->tasksaction->save()) {
            $this->view->tasksaction = $this->tasksaction;
        } else {
            $this->view->errors = $this->tasksaction->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function showAction()
    {
        $this->view->tasksaction = $this->tasksaction;
        $this->_helper->conditionalGet()->sendFreshWhen(
            ['etag' => [$this->task]]
        );
    }

    public function updateAction()
    {
        Task::hydrate($this->tasksaction, $this->_getParam('task'));
        if ($this->tasksaction->isValid() && $this->tasksaction->save()) {
            $this->view->tasksaction = $this->tasksaction;
        } else {
            $this->view->errors = $this->tasksaction->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function destroyAction()
    {
        if($this->task->destroy()) {
            $this->view->taskaction = $this->taskaction;
        } else {
            $this->view->errors = ['delete' => 'Unable to delete resource'];
        }
    }
}
