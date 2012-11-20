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

		this.length = function() {
			return tracks.length;
		}

		this.add = function(track) {
			tracks.push(track);
		};

		this.clear = function() {
			tracks = [];
			index = 0;
		}

		this.playNext = function() {
			player.pause();
			index = Math.min(index+1, tracks.length);
			this.play();
		}

		this.play = function() {
			var track = tracks[index];
			if (track) {
				player.src = track.filename;
				player.play();
			}
		}


	};

	function Track() {
		this.album = '';
		this.artist = '';
		this.filename = '';
		this.artFilename = '';
	}

});