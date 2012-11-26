$(function() {
	var uploadURL = Routing.generate('phplayer_music_upload_file');
	var filesURL = 'http://localhost:8080/phplayer/web/app_dev.php/upload/files';

	var artist = $('#artist').val();
	var album = $('#album').val();
	window.indicator = new ProgressIndicator($('canvas').get(0));
	var uploadCount = 0;
	var nameChanged = false;

	update();

	// Uploading on drop

	$('.uploadArea').on('drop', function(e) {
		e.preventDefault();
		e.stopPropagation();

		console.log('drop!');
		$('.uploadArea').removeClass('showMessage');

		var files = e.originalEvent.dataTransfer.files;
		for (var i=0; i < files.length; i++) {
			var file = files[i];
			sendFile(file);
		}
	});

	function sendFile(file) {
		uploadCount++;
		indicator.set(file.name, 0.0);

		var xhr = new XMLHttpRequest();
		xhr.open('POST', uploadURL);
		xhr.onload = function(e) {
			console.log('Uploading successfull!', xhr);
			//handleComplete(file.size);
		};
		xhr.onerror = function() {
			console.log('Error uploading file');
			//handleComplete(file.size);
		};
		xhr.onloadend = function() {
			console.log('Uploading ended.');
			uploadCount--;
			if (uploadCount === 0) {
				if (nameChanged) {
					moveUploads(artist, album, $('#artist').val(), $('#album').val());
				} else {
					guessFilenames();
				}
			}
			update();
		};
		xhr.upload.onprogress = function(e) {
			indicator.set(file.name, e.loaded / e.total);
		};
		xhr.upload.onloadstart = function(event) {
		};

		// prepare FormData
		var formData = new FormData();
		formData.append('form[myfile]', file);
		formData.append('form[artist]', artist);
		formData.append('form[album]', album);
		xhr.send(formData);
	}

	// Moving uploads
	$('#applyMove').hide();
	if (defaultFilenames()) {
		$('#inputs').hide();
	}

	$('#inputs input').on('keydown', function() {
		$('#applyMove').slideDown();
	});

	$('#applyMove').on('click', function() {
		if (uploadCount > 0) {
			nameChanged = true;
		} else {
			moveUploads(artist, album, $('#artist').val(), $('#album').val());
		}
		$(this).slideUp();
	});

	function moveUploads(oldArtist, oldAlbum, newArtist, newAlbum) {
		$.ajax({
			url: Routing.generate('phplayer_music_upload_movefiles', {
				oldArtist: oldArtist,
				oldAlbum: oldAlbum,
				newArtist: newArtist,
				newAlbum: newAlbum
			}),
			method: 'post',
			success: function() {
				artist = newArtist;
				album = newAlbum;

				$('#artist').val(artist);
				$('#album').val(album);

				update();
			},
			error: function() {
				console.log('error!');
			}
		});
	}

	// Showing most recent status

	function update() {
		$.ajax({
			url: Routing.generate('phplayer_music_upload_listfiles', {
				artist: artist, 
				album: album
			}),
			success: function(data) {
				elems = $(data);
				$('#files').html(elems);
				if (elems.find('.track').length > 0 && uploadCount === 0) {
					$('#inputs').slideDown();
					$('.uploadArea').removeClass('showMessage');
				}
			}
		});

		$.ajax({
			url: Routing.generate('phplayer_music_upload_getarturl', {
				artist: artist, 
				album: album
			}),
			success: function(data) {
				if (data) {
					$('.albumArt').css({
						backgroundImage: 'url("'+data+'")'
					});
				}
			}
		});

		if (!defaultFilenames()) {
			$('#inputs').slideDown();
			$('.uploadArea').removeClass('showMessage');
		}
	}

	function guessFilenames() {
		if (!defaultFilenames()) {
			console.log('Filenames not default anymore, so not guessing.');
			return;
		}

		console.log('Guessing filenames');

		$.ajax({
			url: Routing.generate('phplayer_music_upload_guessfilenames', {
				artist: artist, 
				album: album
			}),
			success: function(data) {
				console.log(data);
				if (data && data.status == 'rename') {
					moveUploads(artist, album, data.newArtist, data.newAlbum);
				} else {
					// Let the user do it
					$('#inputs').slideDown();
				}
			}
		});
	}

	function defaultFilenames() {
		return (artist == 'Artist') && (album == 'Album');
	}

	// Rename

	$('.track .rename').live('click', function(e) { 
		e.preventDefault();

		var filename = $(this).closest('.track').data('name');
		var newFilename = prompt('Choose new filename:', filename);

		if (newFilename) {
			$.post(Routing.generate('phplayer_music_upload_renamefile', {
				artist: artist, 
				album: album,
				track: filename,
				newTrackName: newFilename
			}), function() {
				update();
			});
		}
	});

	// Confirm on certain actions
	$('a.confirm').click(function() {
		return confirm('Are you sure?');
	});

});
