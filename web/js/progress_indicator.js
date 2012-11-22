window.requestAnimFrame = (function(){
  return  window.requestAnimationFrame       || 
          window.webkitRequestAnimationFrame || 
          window.mozRequestAnimationFrame    || 
          window.oRequestAnimationFrame      || 
          window.msRequestAnimationFrame     || 
          function( callback ){
            window.setTimeout(callback, 1000 / 60);
          };
})();

function ProgressIndicator(canvas) {
	if (!canvas) return;
	console.log(canvas);

	var LINEWIDTH = 15;
	var RADIUS = (canvas.width - LINEWIDTH) / 2;

	var ctx = canvas.getContext('2d');
	var blocks = [];

	ctx.fillstyle = '#10b3ff';
	ctx.strokeStyle = '#10b3ff';
	ctx.lineWidth = LINEWIDTH;

	this.find = function(url) {
		for (var i=0; i<blocks.length; i++) {
			if (blocks[i].url == url) {
				return blocks[i];
			}
		}
	};

	this.done = function() {
		for (var i=0; i<blocks.length; i++) {
			if (blocks[i].progress < 1.0) {
				return false;
			}
		}
		return true;
	};

	this.set = function(url, progress) {
		var block = this.find(url);
		if (!block) {
			block = {
				url: url
			};
			blocks.push(block);
		}
		block.progress = progress;
	};

	this.draw = function() {
		ctx.clearRect(0,0,canvas.width, canvas.height);

		if (!this.done()) {
			// Rotate a bit
			ctx.translate(canvas.width/2, canvas.height/2);
			ctx.rotate(0.01);
			ctx.translate(-canvas.width/2, -canvas.height/2);

			// Draw background circle
			ctx.globalAlpha = 0.5;
			ctx.beginPath();
			ctx.arc(
				canvas.width/2,
				canvas.height/2,
				RADIUS,
				0,
				2*Math.PI,
				false
			);
			ctx.stroke();

			// Draw pieces
			var part = 2 * Math.PI / blocks.length;
			for (var i=0; i<blocks.length; i++) {
				var block = blocks[i];

				ctx.globalAlpha = 1.0;
				ctx.beginPath();
				ctx.arc(
					canvas.width/2,
					canvas.height/2,
					RADIUS,
					i * part,
					i * part + block.progress * part,
					false
				);
				ctx.stroke();
			}
		}

		var self = this;
		window.requestAnimFrame(function() {
			self.draw();
		});
	};
	this.draw();

}