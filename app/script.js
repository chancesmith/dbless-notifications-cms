// create the module and name it notificationsApp
var dbless = angular.module('dbless', ['ngRoute']);

// configure our routes
dbless.config(function($routeProvider) {
	$routeProvider
		// route for the home page
		.when('/', {
			templateUrl : 'pages/intro.html',
			controller  : 'mainController'
		});
});

//create UUID
function generateUUID(){
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function"){
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
}

dbless.service('mainController', function ($http) {
    var notifications = '';
    $http.get('notifications.json').success(function(data) {
        notifications = data;
    });
    
    //save method create a new notification if not already exists
    //else update the existing object
    this.save = function (notification) {
    	if (notification.id == null) {
            //if this is new notification, add it in notifications array
            notification.id = generateUUID();
            notifications.push(notification);
            console.log('New notifications'+notifications.length);
        } else {
            //for existing notification, find this notification using id
            //and update it.
            console.log('Existing notification');
            for (i in notifications) {
            	if (notifications[i].id == notification.id) {
            		notifications[i] = notification;
            	}
            }
        }
        // var jsonData = JSON.stringify(notifications);
        $http({
          method: 'POST',
          url: 'update-json.php',
          data: notifications
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            console.log(response);
          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.log(response);
          });
    }

    //simply search notifications list for given id
    //and returns the notification object if found
    this.get = function (id) {
    	for (i in notifications) {
    		if (notifications[i].id == id) {
    			return notifications[i];
    		}
    	}

    }
    
    //iterate through notifications list and delete 
    //notification if found
    this.delete = function (id) {
    	for (i in notifications) {
    		if (notifications[i].id == id) {
    			notifications.splice(i, 1);
    		}
    	}
        $http({
          method: 'POST',
          url: 'update-json.php',
          data: notifications
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            console.log(response);
          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.log(response);
          });
    }

    //iterate through notifications list and duplicate 
    //notification if found
    this.duplicate = function (id) {
        var cloneNotification = '';
        for (i in notifications) {
            if (notifications[i].id == id) {
                cloneNotification = notifications[i];
                console.log(cloneNotification);
                cloneNotification.id = generateUUID();
                notifications.push(cloneNotification);
                console.log(cloneNotification);
                console.log('Notification Duplicated');
            }
        }
        $http({
          method: 'POST',
          url: 'update-json.php',
          data: notifications
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            console.log(response);
          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.log(response);
          });
    }

    //simply returns the notifications list
    this.list = function () {
        return notifications;
    }
});

dbless.controller('mainController', function ($scope, mainController) {

	$scope.notifications = mainController.list();

	$scope.saveNotification = function () {
		mainController.save($scope.newnotification);
		$scope.newnotification = {};
	}

	$scope.delete = function (id) {
		mainController.delete(id);
		if ($scope.newnotification.id == id) $scope.newnotification = {};
	}

	$scope.edit = function (id) {
		$scope.newnotification = angular.copy(mainController.get(id));
	}

    $scope.duplicate = function (id) {
        $scope.newnotification = angular.copy(mainController.duplicate(id));
    }
})