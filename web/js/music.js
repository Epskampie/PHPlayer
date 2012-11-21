$(function() {

	var player = document.createElement('audio');
	window.que = new Que();

	$('.tracks').hide();

	// Interaction
	$('.album').click(function() {
		$(this).find('.tracks').slideToggle();
	});

	$('.track .play').click(function(e) {
		e.stopPropagation();
		play($(this).closest('.track'));
	});

	$('.track .enque').click(function(e) {
		e.stopPropagation();
		enque($(this).closest('.track'));
	});

	function play(trackElem) {
		var track = elem2track(trackElem);

		que.clear();
		que.add(track);
		que.play();
	}

	function enque(trackElem) {
		var track = elem2track(trackElem);

		que.add(track);
		if (que.length() == 1) {
			que.play();
		}
	}

	function elem2track(trackElem) {
		var track = new Track();
		
		track.filename = $(trackElem).data('url');
		track.name = $(trackElem).data('name');
		track.viewName = $(trackElem).data('view-name');
		track.artist = $(trackElem).closest('.artist').data('name');
		track.album = $(trackElem).closest('.album').data('name');
		track.artUrl = $(trackElem).closest('.album').data('art-url');
		console.log('HA', track.artUrl);

		return track;
	}

	// Player
	player.addEventListener('ended', function() {
		console.log('song ended');
		que.playNext();
	});

	function Que() {
		var index = 0;
		var tracks = [];
		var queElem = $('#que');

		this.length = function() {
			return tracks.length;
		};

		this.add = function(track) {
			tracks.push(track);
			track.tile = this.buildTile(track);
			queElem.append(track.tile);
			queElem.width(track.tile.outerWidth(true) * tracks.length);
		};

		this.buildTile = function(track) {
			var tile = $('<div class="tile" />');

			tile.append('<img src="' + track.artUrl + '" />');
			tile.append('<div class="trackViewName">' + track.viewName + '</div>');
			tile.append('<div class="artistName">' + track.artist + '</div>');

			tile.data('track', track);
			var self = this;
			tile.click(function() {
				var track = $(this).data('track');
				self.playTrack(track);
			});

			return tile;
		};

		this.clear = function() {
			player.pause();
			tracks = [];
			index = 0;
			queElem.empty();
		};

		this.playNext = function() {
			player.pause();
			index = Math.min(index+1, tracks.length);
			this.play();
		};

		this.play = function(i) {
			if (i !== undefined) {
				index = i;
			}
			var track = tracks[index];
			if (track) {
				player.src = track.filename;
				player.play();
			}
		};

		this.playTrack = function(track) {
			for (var i=0; i < tracks.length; i++) {
				if (tracks[i] == track) {
					this.play(i);
					return;
				}
			}
		};
	}

	function Track() {
		this.album = '';
		this.artist = '';
		this.filename = '';
		this.name = '';
		this.viewName = '';
		this.artUrl = '';
		this.tile = $();
	}

});