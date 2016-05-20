/* trello js */
$(document).ready(function(){

	$('.boardActivity').each(function(){
		var t = $(this);
		drawboard(t);
	});
});


function drawboard(t) {	
	// var t=$(this);
	var boardid = t.data('boardid');
	var limit = t.data('limit');
	var filter = t.data('filter');
	if(!filter) filter = false;
	
	$('.loggedIn',t).hide();

	var getBoards = function (){
		updateLoggedIn();
	
		$("#boardList",t).empty();

		Trello.members.get("me", function(member){
			$(".fullName",t).text(member.fullName);

			var $boardActivity = $('<div>')
				.text("Loading Activity...")
				.appendTo('#'+t.attr('id'));

			// Output Board Activity 

			var output = '';
			
			if(filter!='') {
				var params = { limit:limit, filter:filter };
			} else {
				var params = { limit:limit };
			}
			
			Trello.get("boards/"+boardid+"/actions", params, function(actions) {
				
					/*
					for (key in actions) {
						console.log(actions[key]);
					}				
					*/
				
					// output += '<div class="elgg-item">';
					// output += '<div class="elgg-image-block clearfix">';
					output += '<div class="elgg-body">';
					output += '<h3><a href="https://trello.com/b/'+actions[0].data.board.shortLink+'" target="_blank">'+actions[0].data.board.name+'</a></h3>';
					output += '</div>';
					// output += '</div>';
					// output += '</div>';
				
					var lng = {
						"commentCard":"comment card",
						"createCard":"create card",
						"updateCard":"update card"
					};



					$.each(actions, function(ix, ac) {
					
						var acdateiso = new Date(ac.date);
						var acdateutc = acdateiso.toUTCString()
				
						output += '<div class="card '+ac.type+'">';
						output += '<span class="elgg-subtext mbn">'+ac.memberCreator.fullName+' ';
						if(lng[ac.type]) {
							output += lng[ac.type];
						} else {
							output += '('+ac.type+')';
						}
						output += '</span><br />';

						if(typeof ac.data.card != "undefined"){
							output += '<p><strong><a href="https://trello.com/c/'+ac.data.card.shortLink+'" target="_blank">'+ac.data.card.name+'</a></strong><br />';
						}
					
						output += '<span class="elgg-subtext mbn">'+acdateutc+'</span><br />';
						output += '</p>';
						if(typeof ac.data.text != "undefined"){
							output += '<p><i>'+ac.data.text+'</i></p>';
						}
					
						output += '</div>';

					}); 

					output += '<p>';

					output += '</div>';

					$boardActivity.empty();
					$boardActivity.html(output);

				}, function(error) {
					$boardActivity.empty();
					$boardActivity.html('<p>Error: '+error.responseText+'</p>');
				});

		
		});
	
	}

	var updateLoggedIn = function() {
		var isLoggedIn = Trello.authorized();
		if (isLoggedIn){
			$(".loggedIn").show();     
			$(".loggedOut").hide();   
		} else {
			$(".loggedIn").hide();
			$(".loggedOut").show();
		}   
	};

	var getDateStamp = function(){
		var d = new Date();
		var year = d.getFullYear();
		var month = d.getMonth() + 1;
		var day = d.getDate();
		return year+'-'+month+'-'+day;
	};

	var logout = function() {
		Trello.deauthorize();
		updateLoggedIn();
	};
  
	Trello.authorize({
		interactive:false,
		success: getBoards
	});

	$("#connectLink")
	.click(function(){
		Trello.authorize({
			type: "popup",
			success: getBoards,
			name: 'Trellista'
		})
	});

	$("#showLink").click(function(){
		Trello.authorize({
			interactive:false,
			success: getBoards
		});	
	});
	$("#disconnect").click(logout);

}