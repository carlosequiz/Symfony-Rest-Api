myApp.controller('UserController', ['$scope', '$http', function($scope, $http){
	$scope.newFirstName = null;
	$scope.newLastName = null;
	$scope.newEmail = null;
	$scope.searchLastNameTerm = null;
	$scope.searchEmailTerm = null;
	$scope.successMessage = null;
	$scope.errorMessage = null;

	var apiHeaders = {'api-key':'e98bf0f5b73e51b6b85e5d649d42d5bd8144394b'};

	var users = $scope.users = [];

	angular.element(document).ready(function(){
		$scope.init();
	});

	$scope.init = function(){
		$scope.getAllUsers();
	};

	$scope.toggleEditingUser = function(){
		$scope.editingUser = !$scope.editingUser;
	};

	$scope.getAllUsers = function(){
		var url = Routing.generate('get_all_users');

		$http({method: 'GET', url: url, headers: apiHeaders}).
			  success(function(data, status, headers, config){
			  	if(undefined != data.Users){
				  	foundUsers = angular.fromJson(data.Users);
					users = $scope.users = [];

					angular.forEach(foundUsers, function(foundUser){
						users.push(foundUser);
					});
		  		}
			  	
			  }).
			  error(function(data, status, headers, config){
			  	$scope.successMessage = null;
			  	$scope.errorMessage = "Error getting users.";
			  });
	};

	$scope.addUser = function(){
		var url = Routing.generate('create_user');

		var newUserData = {};
		newUserData.firstName = $scope.newFirstName;
		newUserData.lastName = $scope.newLastName;
		newUserData.email = $scope.newEmail;

		 $http({method: 'PUT', url: url, headers: apiHeaders, data: JSON.stringify(newUserData)}).
		  success(function(data, status, headers, config){
		  	if(undefined != data.newUser){
			  	newUser = angular.fromJson(data.newUser);
				users.push(newUser);
				$scope.newFirstName = null;
				$scope.newLastName = null;
				$scope.newEmail = null;

				$scope.errorMessage = null;
			  	$scope.successMessage = "User " + newUser.firstName + " " + newUser.lastName + " added successfully.";
		  	}
		  }).
		  error(function(data, status, headers, config){
		  	$scope.successMessage = null;
		  	$scope.errorMessage = "Error adding " + newUserData.firstName + " " + newUserData.lastName + ".";
		  });
	};

	$scope.updateUser = function(user){
		var url = Routing.generate('update_user', {id: user.id});

		 $http({method: 'PUT', url: url, headers: apiHeaders, data: JSON.stringify(user)}).
		  success(function(data, status, headers, config){
		  	$scope.errorMessage = null;
			$scope.successMessage = "User updated successfully.";
		  }).
		  error(function(data, status, headers, config){
		  	$scope.successMessage = null;
		  	$scope.errorMessage = "Error updating user.";
		  });
	};

	$scope.removeUser = function(user){
		var url = Routing.generate('delete_user', { id: user.id});

		$http({method: 'DELETE', url: url, headers: apiHeaders}).
		success(function(data, status, headers, config){
		  $scope.errorMessage = null;
		  $scope.successMessage = "User " + user.firstName + " " + user.lastName + " removed successfully.";
		  users.splice(users.indexOf(user), 1);
		}).
		error(function(data, status, headers, config){
		  $scope.successMessage = null;
		  $scope.errorMessage = "Error removing " + user.firstName + " " + user.lastName + ".";
		});
	};

	$scope.searchUser = function(){
		var url = Routing.generate('search_users');
		var searchObject = {};
		searchObject.id = $scope.searchIdTerm;
		searchObject.lastName = $scope.searchLastNameTerm;
		searchObject.email = $scope.searchEmailTerm;

		$http({method: 'POST', url: url, headers: apiHeaders, data: JSON.stringify(searchObject)}).
			  success(function(data, status, headers, config){
			  	if(undefined != data.Users){
				  	foundUsers = angular.fromJson(data.Users);
					users = $scope.users = [];

					angular.forEach(foundUsers, function(foundUser){
						users.push(foundUser);
					});
		  		}
			  	
			  	$scope.errorMessage = null;
			  }).
			  error(function(data, status, headers, config){
		  	    $scope.successMessage = null;
			  	$scope.errorMessage = "Error getting users.";
			  });
	};
}]);