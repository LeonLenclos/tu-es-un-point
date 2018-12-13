let gamecnv = document.getElementById('gamecnv');

let keys = [];
game = new Game(gamecnv.innerHTML);

setInterval(main, 1);


document.addEventListener('keydown', function(event) {
    if (keys.indexOf(event.key) < 0){
    	keys.push(event.key);
    }

	if(["ArrowRight", "ArrowLeft", "ArrowUp", "ArrowDown"].indexOf(event.key) > -1) {
	    event.preventDefault();
	}
});
document.addEventListener('keyup', function(event) {
    if (keys.indexOf(event.key) >= 0){
    	keys.splice(keys.indexOf(event.key), 1);
    }
});

let loopDelay = 30;
let loopTime =Date.now();
let fpss = [];
function main() {
	
	if (Date.now() - loopTime > loopDelay){
		// fpss.push(Date.now() - loopTime);
		// console.log(fpss.reduce(function(p,c,i,a){return p + (c/a.length)},0));
		loopTime =Date.now();
		game.pressed(keys)
		game.update();
		let display = game.display();
		if (gamecnv.innerHTML != display)
			gamecnv.innerHTML = display;
	}

}
