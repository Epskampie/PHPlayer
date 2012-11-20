$(function() {
	var uploadURL = 'http://localhost:8080/phplayer/web/app_dev.php/upload/file';
	var moveURL = 'http://localhost:8080/phplayer/web/app_dev.php/upload/move';
	var filesURL = 'http://localhost:8080/phplayer/web/app_dev.php/upload/files';

	var artist = $('#artist').val();
	var album = $('#album').val();
	var uploadCount = 0;
	var nameChanged = false;

	$('.uploadArea').on('drop', function(e) {
		e.preventDefault();
		e.stopPropagation();

		console.log('drop!');
		$('.uploadArea').removeClass('showMessage');

		var files = e.originalEvent.dataTransfer.files;
		for (var i=0; i < files.length; i++) {
			var file = files[i];

			console.log(file);

			sendFile(file);
		}
	});

	function sendFile(file) {
		uploadCount++;
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
			if (uploadCount === 0 && nameChanged) {
				moveUploads(artist, album, $('#artist').val(), $('#album').val());
			}
			update();
		};
		xhr.upload.onprogress = function(event) {
			handleProgress(event);
		};
		xhr.upload.onloadstart = function(event) {
		};

		// prepare FormData
		var formData = new FormData();
		formData.append('form[myfile]', file);
		console.log(artist);
		console.log(album);
		formData.append('form[artist]', artist);
		formData.append('form[album]', album);
		xhr.send(formData);
	}

	function handleProgress(event) {
		console.log('Progress!');
	}

	function moveUploads(oldArtist, oldAlbum, newArtist, newAlbum) {
		var url = encodeURI(moveURL+'/'+oldArtist+'/'+oldAlbum+'/'+newArtist+'/'+newAlbum);
		console.log('url: ', url);
		$.ajax({
			url: url,
			method: 'post',
			success: function() {
				artist = newArtist;
				album = newAlbum;
				console.log(newArtist, newAlbum, artist, album);
				update();
			},
			error: function() {
				console.log('error!');
			}
		});
	}

	// Inputs handling
	$('#applyMove').on('click', function() {
		console.log('upload count: ', uploadCount);
		if (uploadCount > 0) {
			nameChanged = true;
		} else {
			moveUploads(artist, album, $('#artist').val(), $('#album').val());
		}
	});

	function update() {
		var url = encodeURI(filesURL+'/'+artist+'/'+album);
		$('#files').load(url);
	}

});
