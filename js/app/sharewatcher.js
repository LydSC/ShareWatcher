
/**
* ownCloud - ShareWatcher
*
* @author Lydéric SAINT CRIQ
* @copyright 2014 CNRS 
* @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
*/

var sharewatcher = angular.module('ShareWatcher', []);

sharewatcher.config(['$httpProvider', function($httpProvider) {
    // CSRF protection
    $httpProvider.defaults.headers.common.requesttoken = oc_requesttoken;
    
}]);


sharewatcher.controller('ShareController',
    function($scope, $http){  

    	/**
    	*	Unshare a file with a user
    	*/
        $scope.unshare = function(event){
			var target = angular.element(event.target); 
			target.parent().removeClass('delete').addClass('progress');
			target.addClass('progress-icon').removeClass('delete-icon');
			
			/**
			* Get the current <tr>  
			*/
			var infos = target.parent();
			
			/**
			* Forge data to send to the API
			*/
			var data = {"action" : 'unshare',
					    "itemSource" : infos.attr('data-item-source'), 
					    "itemType" : "file", 
					    "shareType": infos.attr('data-share-type'), 
					    "shareWith": infos.attr('data-share-with')
					};
			
			var url = OC.generateUrl('core/ajax/share.php');
			/**
			* API Ajax/share need data in URL params e.g. ajax/share.php?action=unshare .... 
			*/
			var responsePromise = $http({
			    method: 'POST',
			    url: url,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
			    data: data
			});			

            responsePromise.success(function(response, status, headers, config) {                
                // Hiding if success
                if(response.status == "success")
                {       
                	console.log($("li[data-item-source*='" + data.itemSource+ "'][data-share-with*='" + data.shareWith+ "']"));       	
                	$("li[data-item-source*='" + data.itemSource+ "'][data-share-with*='" + data.shareWith+ "']").hide();
                	// @todo if no more share, hide the tr line
                }
                // @todo better managing errors
               	else
               	{               		
               		alert('Error. Can\'t unshare');
               	}
            });
            // @todo better managing errors
            responsePromise.error(function(data, status, headers, config) {
                alert("AJAX failed!");
            });
        };



        $scope.getNotification = function(event){
			var target = angular.element(event.target); 
			target.hide();

			target.parent().children(".action").addClass('progress-icon');

			/**
			* Get the current data 
			*/
			var infos = target.parent();

			// The checkbox is checked, user need to be notified if the share_with download the share
			if(target.is(':checked'))
			{
				notification_needed = '1';
			}
			else
			{
				notification_needed = '0';	
			}

			/**
			* Forge data to send to the API
			*/
			var data = {"action" : 'setNotification',
					    "id_share" : infos.attr('data-id'), 
					    "itemType" : "file", 
					    "shareType": infos.attr('data-share-type'), 
					    "shareWith": infos.attr('data-share-with'),
					    "notification_needed": notification_needed
					};

			
			var url = OC.generateUrl('/apps/sharewatcher/notification');
			var responsePromise = $http({
			    method: 'POST',
			    url: url,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
			    data: data
			});


			responsePromise.success(function(response, status, headers, config) {     
				target.parent().children(".action").removeClass('progress-icon');
                target.show();           
                // Hiding if success
                if(response.status == "success")
                {                	
                	// @todo if no more share, hide the tr line
                }
                // @todo better managing errors
               	else
               	{
               		//alert(response);
               		//alert('error : ' + response.status);
               	}
            });
            // @todo better managing errors
            responsePromise.error(function(data, status, headers, config) {
                alert("AJAX failed!");
            });
			
		};

		/**
		* Show users in group using api#controller ShareWatcher
		*/
		$scope.getUsersInGroup = function(event){
			var target = angular.element(event.target); 
			/**
			* Get the current data of the group to sho
			*/
			var infos = target.parent();					

			/**
			* Forge the url with the name_group in parameter
			*/
			var url = OC.generateUrl('/apps/sharewatcher/getUsersInGroup?group=' + infos.attr('data-share-with'));	
			var responsePromise = $http({
			    method: 'GET',
			    url: url,				    
			});

			responsePromise.success(function(response, status, headers, config) {     
				
				/**
				* Bad way to fix a bug 
				* A "NULL" always end the response. I delete it
				*/ 
				var response = response.replace('"}null', '"}');

				/**
				* Get the JSON from the response
				*/
				var json_result = JSON.parse(response);
				
				if(json_result.status == 'success')
				{							
					/**
					* Get the id of the shared ressource to be able to find where to display the users list
					*/
                	var item_source = infos.attr('data-item-source');        	
                	var div_result = $('li[data-item-source=' + item_source +'] > div.users_in_group[data-share-with=' + infos.attr('data-share-with') +']');

                	/**
                	* Create the "list" to display
                	*/
                	var display = "";
                	json_result.result.forEach(function(element, index){
                		display += "<strong>|- " + element + "</strong><br/>";
                	});
                }

                /**
                * Put the display in the right div
                */
                div_result.html(display); 
            });

            // @todo better managing errors
            responsePromise.error(function(data, status, headers, config) {
                alert("AJAX failed!");
            });

		};
    }
);




