angular.module('taskConfirmationApp',  ['ui.router', 'ngResource', 'ui.bootstrap'])
.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    // Use hashtags in URL
    $locationProvider.html5Mode(false);

    $urlRouterProvider.otherwise("/");
    $stateProvider
    .state('index', {
      url: "/",
      templateUrl: "/taskConfirmationApp/templates/index.html",
      controller: 'TaskCtrl'
    });
})
.factory('Task', function($resource) {
    return $resource('/task/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.factory('TaskAction', function($resource) {
    return $resource('/taskaction/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET', isArray: true},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.controller('TaskCtrl', function($scope, $modal, Task, TaskAction) {
    
    Task.query().$promise.then(function(result) {
        
        $scope.tasks = result;

        angular.forEach($scope.tasks, function(task, key) {
        
            task.state_i = parseInt(task.state);
            task.state_description = task.state_i === 0 ? "Pending" : task.state_i == 1 ? "Accepted" : "Refused";
        
            TaskAction.get({'task_id':task.id}).$promise.then(function(result_task_actions) {
                task.taskactions = result_task_actions;
            });

            $scope.open = function (task) {

                var modalInstance = $modal.open({
                  animation: true,
                  templateUrl: '/taskConfirmationApp/templates/taskActions.html',
                  controller: 'ModalInstanceCtrl',
                  resolve: {
                    item: function () {
                      return task;
                    }
                  }
                });

                modalInstance.result.then(function (selectedItem) {
                  
                }, function () {
                  
                });

            }   


        });

    })

    
})
.controller('ModalInstanceCtrl', function ($scope, $modalInstance, item) {

  $scope.item = item;
  $scope.taskactions = item.taskactions;
  
  $scope.currentDate = function(date) {
    var rdate = new Date(date);
    var options = {weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "numeric", minute: "numeric"}
    return rdate.toLocaleDateString("en-US", options);
    }

  $scope.ok = function () {
    $modalInstance.close();
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
});
