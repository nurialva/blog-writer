const graphurl = 'http://localhost:8000/';
const nullmessage = 'Maaf, halaman yang Anda cari tidak dapat kami sajikan.';
function disableSubmitKeyPress () {
	$(window).keydown(function(event){
	  if(event.keyCode == 13) {
	    event.preventDefault();
	    return false;
	  }
	});
}
function search_kiai () {
	var sq = $('.query').val();
	if ( sq.length > 3 ) {
		var query = 'query { search_kiai(query:"'+sq+'") { id name avatar bio verified } }';
		jQuery.ajax({
			method: "POST",
			url: graphurl,
			contentType: "application/json",
			data: JSON.stringify({"query": query}),
			beforeSend: function(){
				$('.message').text('');
				$('.result-field').text('');
			},
			success: function(data) {
				var dataresult = data.data.search_kiai;
				var total = dataresult.length;
				if ( total > 0 ){
					$('.message').text('Ditemukan '+total+' dari hasil pencarian..');
					for (var i = 0; i < total; i++) {
						var name = dataresult[i].name;
						var id = dataresult[i].id;
						var bio = dataresult[i].bio;
						var avatar = dataresult[i].avatar;
						if (bio == null){
							var bio = '';
						}
						if ( id !== null) {
							$('.result-field').append('<div uk-grid> <div class="uk-width-1-4"> <img src="img/logo_santrispace.png" width="100%" alt=""> </div> <div class="uk-width-expand"> <p class="uk-margin-small-bottom">'+name+' <br> '+bio+'</p> <a href="/kiai.php?id='+id+'" class="uk-button uk-border-pill uk-button-small uk-margin-remove-top uk-button-default">Sowan</a> <hr> </div> </div>');
						}
					}
				} else {
					$('.message').text('Maaf, belum ada hasil untuk pencarian "'+ sq +'"');
				}
			},
			error: function(data){
				$('.message').text('Ada masalah dengan sistem kami. Coba lagi nanti.');
			}
		});
	} else
	{
		$('.message').text('Gunakan 4 kata kunci atau lebih untuk hasil yang lebih detail.');
	}
};
function search_kitab () {
	var sq = $('.query').val();
	if ( sq.length > 3 ) {
		var query = 'query { search_kitab(query:"'+sq+'") { id name } }';
		jQuery.ajax({
			method: "POST",
			url: graphurl,
			contentType: "application/json",
			data: JSON.stringify({"query": query}),
			beforeSend: function(){
				$('.message').text('');
				$('.result-field').text('');
			},
			success: function(data) {
				var dataresult = data.data.search_kiai;
				var total = dataresult.length;
				if ( total > 0 ){
					$('.message').text('Ditemukan '+total+' dari hasil pencarian..');
					for (var i = 0; i < total; i++) {
						var name = dataresult[i].name;
						var id = dataresult[i].id;
						if ( id !== null) {
							$('.result-field').append('<div uk-grid> <div class="uk-width-1-4"> <img src="img/logo_santrispace.png" width="100%" alt=""> </div> <div class="uk-width-expand"> <p class="uk-margin-small-bottom">'+name+' <br> '+bio+'</p> <a href="/kiai.php?id='+id+'" class="uk-button uk-border-pill uk-button-small uk-margin-remove-top uk-button-default">Sowan</a> <hr> </div> </div>');
						}
					}
				} else {
					$('.message').text('Maaf, belum ada hasil untuk pencarian "'+ sq +'"');
				}
			},
			error: function(data){
				$('.message').text('Ada masalah dengan sistem kami. Coba lagi nanti.');
			}
		});
	} else
	{
		$('.message').text('Gunakan 4 kata kunci atau lebih untuk hasil yang lebih detail.');
	}
};
function search_pesantren () {
	var sq = $('.query').val();
	if ( sq.length > 3 ) {
		var query = 'query { search_pesantren(query:"'+sq+'") { id name address } }';
		jQuery.ajax({
			method: "POST",
			url: graphurl,
			contentType: "application/json",
			data: JSON.stringify({"query": query}),
			beforeSend: function(){
				$('.message').text('');
				$('.result-field').text('');
			},
			success: function(data) {
				var dataresult = data.data.search_pesantren;
				var total = dataresult.length;
				if ( total > 0 ){
					$('.message').text('Ditemukan '+total+' dari hasil pencarian..');
					for (var i = 0; i < total; i++) {
						var name = dataresult[i].name;
						var id = dataresult[i].id;
						var address = dataresult[i].address;
						if ( address == null) {
							address = 'Belum ada alamat pesantren';
						}
						if ( id !== null) {
							$('.search-result').append('<div uk-grid> <div class="uk-width-1-4"> <img src="img/logo_santrispace.png" width="100%" alt=""> </div> <div class="uk-width-expand"> <p class="uk-margin-small-bottom">'+name+' <br> '+address+'</p> <a href="/pesantren.php?id='+id+'" class="uk-button uk-border-pill uk-button-small uk-margin-remove-top uk-button-default">Sowan</a> <hr> </div> </div>');
						}
					}
				} else {
					$('.message').text('Maaf, belum ada hasil untuk pencarian "'+ sq +'"');
				}
			},
			error: function(data){
				$('.message').text('Ada masalah dengan sistem kami. Coba lagi nanti.');
			}
		});
	} else
	{
		$('.message').text('Gunakan 4 kata kunci atau lebih untuk hasil yang lebih detail.');
	}
};
