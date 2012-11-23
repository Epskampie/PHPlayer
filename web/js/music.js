$(function() {

	window.player = document.createElement('audio');
	window.que = new Que();

	$('.tracks').hide();

	$('.album').click(function() {
		$(this).find('.tracks').slideToggle();
	});

	// Buttons

	$('.track .play').click(function(e) {
		e.stopPropagation();
		play($(this).closest('.track'));
	});

	$('.track .enque').click(function(e) {
		e.stopPropagation();
		enque($(this).closest('.track'));
	});

	$('.albumName .play').click(function(e) {
		e.stopPropagation();
		que.clear();
		$(this).closest('.album').find('.track').each(function(index, elem) {
			enque($(elem));
		});
		que.play();
	});

	$('.albumName .enque').click(function(e) {
		e.stopPropagation();
		$(this).closest('.album').find('.track').each(function(index, elem) {
			enque($(elem));
		});
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

		return track;
	}

	// Player
	player.addEventListener('ended', function() {
		console.log('song ended');
		que.playNext();
	});

	// Prevent leaving page when playing 
	window.onbeforeunload = function(){
		if (!player.paused) {
			return 'This will stop the music.';
		}
	};

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
			tile.append('<div class="play">');
			tile.append('<div class="pause">');

			tile.data('track', track);
			var self = this;
			tile.click(function() {
				var track = $(this).data('track');
				self.clickTrack(track);
			});

			return tile;
		};

		this.clear = function() {
			this.pause();
			player.src = '';
			tracks = [];
			index = 0;
			queElem.empty();
		};

		this.playNext = function() {
			this.pause();
			index = Math.min(index+1, tracks.length);
			this.play();
		};

		this.pause = function() {
			player.pause();
			$('.tile').removeClass('playing');
		};

		this.play = function(i) {
			var oldIndex = index;
			if (i !== undefined) {
				index = i;
			}
			var track = tracks[index];
			$('.tile').removeClass('active playing');
			if (track) {
				if (index != oldIndex || player.readyState === 0 || i === undefined) {
					player.src = track.filename;
				}
				player.play();
				track.tile.addClass('active playing');
			}
		};

		this.clickTrack = function(track) {
			for (var i=0; i < tracks.length; i++) {
				if (tracks[i] == track) {
					if (index == i && !player.paused) {
						this.pause();
					} else {
						this.play(i);
					}
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