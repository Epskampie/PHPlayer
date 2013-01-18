$(function() {
	var searchElem = $('input.search');
	var searchTimeout;
	var albums = [];

	$('.album').each(function(index, albumElem) {
		albumElem = $(albumElem);
		var album = {};

		album.elem = albumElem;
		album.tracksElem = albumElem.find('.tracks');
		album.albumName = albumElem.find('.albumName .name').text().toLowerCase();
		album.artistName = albumElem.find('.artistName .name').text().toLowerCase();

		album.tracks = [];

		albumElem.find('.track').each(function(trackIndex, trackElem) {
			trackElem = $(trackElem);
			var track = {};

			track.elem = trackElem;
			track.title = trackElem.find('.name').text().toLowerCase();

			album.tracks.push(track);
		});

		albums.push(album);
	});

	searchElem.on('keyup', function() {
		clearTimeout(searchTimeout);

		searchTimeout = setTimeout(search, 300);
	});

	function search() {
		var val = searchElem.val().toLowerCase();
		var empty = val === '';

		$(albums).each(function(albumIndex, album) {
			if (album.albumName.indexOf(val) == -1 && album.artistName.indexOf(val) == -1) {
				album.elem.css('display', 'none');
			} else {
				album.elem.css('display', 'block');
			}

			var foundInTracks = false;
			$(album.tracks).each(function(trackIndex, track) {
				if (track.title.indexOf(val) == -1) {
					track.elem.css('display', 'none');
				} else {
					track.elem.css('display', 'block');
					album.elem.css('display', 'block');
					foundInTracks = true;
				}
			});
			album.tracksElem.toggle(foundInTracks && !empty);

		});
	}
});