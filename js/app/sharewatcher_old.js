
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
        $scope.showClicked = function(event){
			var target = angular.element(event.target); 
			target.parent().removeClass('delete').addClass('progress');
			target.addClass('progress-icon').removeClass('delete-icon');
			
			/**
			* Get the current <tr>  
			*/
			var tr = target.parent().parent();
			
			/**
			* Forge data to send to the API
			*/
			var data = {"action" : 'unshare', "itemSource" : tr.attr('data-id'), "itemType" : "file", "shareType": tr.attr('data-share-type'), "shareWith": tr.attr('data-share-with')};
			
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



            responsePromise.success(function(data, status, headers, config) {                
                // Hiding tr in the table if success
                if(data.status == "success")
                {
                	tr.hide();
                }
                // @todo better managing errors
               	else
               	{
               		alert('error');
               	}
            });
            // @todo better managing errors
            responsePromise.error(function(data, status, headers, config) {
                alert("AJAX failed!");
            });
        };
    }
);

