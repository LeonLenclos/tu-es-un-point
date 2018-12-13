const PLAYER_CHAR = '.';
const COIN_CHAR = '$';
const LAVA_CHAR = '~';
const DOOR_CHAR = '∏';
const PRINTER_CHAR = '⊟';


function setCharAt(str,chr,index) {
    if(index > str.length-1){ return str};
    return str.substr(0,index) + chr + str.substr(index+1);
}


class Game {

  constructor(lvlHTML) {
  	// the level from HTML
  	this.lvlHTML = lvlHTML;

  	// The gravity vector
  	this.gravity = new Vector(0,0.1);

  	// Sounds
  	this.soundNape = new Audio("audio/nape.wav")
  	this.soundDoor = new Audio("audio/door.wav")
  	this.soundGameOver = new Audio("audio/gameover.wav")
  	this.soundCoin = new Audio("audio/coin.wav")
  	this.soundJump = new Audio("audio/jump.wav")
  	this.soundWin = new Audio("audio/win.wav")

  	// Launch the game
  	this.reset()
  }

  // reset function, launch or reset the game
  reset(){
  	this.gameover = false;
  	// The world (each lines in an array of strings)
  	this.world = this.lvlHTML.replace(/&gt;/g,">").split("\n");

  	// Height and with of the world
  	this.height = this.world.length;
    this.width = 0;
  	for (var i = 0; i < this.world.length; i++) {
  		if(this.width < this.world[i].length) this.width = this.world[i].length;
  	}
  	

  	// Fill with spaces and create player


	for (var i = 0; i < this.world.length; i++) {
		// create player
		for (var j = this.world[i].length-1; j >= 0 ; j--) {
			if(this.world[i][j] == PLAYER_CHAR){
				this.player = new Player(j, i);
				this.world[i] = setCharAt(this.world[i], ' ', j);
			}			
        }
		// fill with spaces
		if (this.world[i].length < this.width){
			this.world[i] += Array(this.width - this.world[i].length + 1).join(' ');
		}
	}
}
  pressed(keys){
  	for (var i = 0; i < keys.length; i++) {
	  	switch (keys[i]) {
		  case "r":
		    this.reset();
		    break;
		  case "ArrowRight":
		    if(this.player) this.player.moveRight();
		    break;
		  case "ArrowLeft":
		    if(this.player) this.player.moveLeft();
		    break;
		  case "Space":
		  case "ArrowUp":
		    if(this.player){
		    	if (this.player.jump()){
		    		this.soundJump.currentTime = 0;
			    	this.soundJump.play();
		    	}
			}
		}
	}
  }


  isAWall(x, y){
  	// If it is not out of world, not a door, not a coin, not a space, not lava, it is a wall.
  	if(this.world[y]){
  		if (this.world[y][x]) {
  			return this.world[y][x] != DOOR_CHAR && this.world[y][x] != COIN_CHAR && this.world[y][x]  != PRINTER_CHAR && this.world[y][x] != LAVA_CHAR &&  this.world[y][x] != ' ';
  		}
  	}
  	return true;
  }


  nextLvl(){
  	this.soundDoor.play();
  	setTimeout(function(){window.location.href = nxtLvlURL;}, 2000);
  }


  update(){
  	// Play the nape
  	this.soundNape.play();

  	if(this.player){
  	  	// Update player
  	  	this.player.applyForce(this.gravity);
  	  	this.player.update(this);
  	
  	  	// Player in a coin
  	  	if(this.world[this.player.y][this.player.x] == COIN_CHAR){
  	  		// delete the coin
  			this.world[this.player.y] = setCharAt(this.world[this.player.y], ' ', this.player.x);
  	
  	  		// play the sound
  	  		this.soundCoin.currentTime = 0
  	  		this.soundCoin.play()
  	  	}
  	
  	  	// Player in the door
  	
  	  	else if(this.world[this.player.y][this.player.x] == DOOR_CHAR){
  	  		if(typeof mdp !== 'undefined'){
  	  			let typedMdp = prompt("Entrez le mot de passe").toLowerCase();
  	  			if(mdp=="*" || mdp==typedMdp){
  	  				alert("Mot de passe correct !")
  	  			} else {
  	  				alert("Mot de passe incorrect... Accès refusé.");
  	  				this.reset();
  	  				return;
  	  			}
  	  		}
  	  		this.player = null;
  	  		this.nextLvl();
  	  	}
  	
  	  	// Player in the lava
  	
  	  	else if(this.world[this.player.y][this.player.x] == LAVA_CHAR){
  	  		this.player = null;
  	  		this.soundGameOver.play();
  	  		let thisGame = this;
  	  		setTimeout(function(){thisGame.gameover = true;}, 1000);
  	  		
  	  	}

  	  	// Player in the printer
  	
  	  	else if(this.world[this.player.y][this.player.x] == PRINTER_CHAR){
  	  		this.player = null;  	  		
  	  		print(".");
  	  	}


  	}
  }

   display() {
   	if (this.gameover) return "\n   TU ES MORT (appuie sur R pour recommencer)   \n\n";
   	let world = this.world.slice();
   	if (this.player){
   	   	world[this.player.y] = setCharAt(world[this.player.y], PLAYER_CHAR, this.player.x);
   	}
   	let txt = world.join("\n");
    return txt;
  }
}


class Player {
	constructor(x, y) {
		this.pos = new Vector(x, y);
		this.vel = new Vector(0,0);
		this.acc = new Vector(0,0);

		this.jumpForce = new Vector (0,-1.2);
		this.moveForce = new Vector (.06,0);
		this.moveLimit = 1;
		this.landed = false;
	}

	get x() {return Math.floor(this.pos.x);}
	get y() {return Math.floor(this.pos.y);}

	jump() {
		if(this.landed){
			this.landed = false;
			this.applyForce(this.jumpForce)
			return true;
		}
		return false;
	}

	moveLeft() {
		this.applyForce(this.moveForce.negate())
	}

	moveRight() {
		this.applyForce(this.moveForce)
	}

	applyForce(force) {
		this.acc = this.acc.add(force);
	}

	update(game){
	    this.vel = this.vel.add(this.acc);	    
	    this.landed = false;

	    if (game.isAWall(this.x, this.y+1) && this.vel.y > 0){
	    	this.vel.y = 0;
	    	this.landed = true;
	    }
	    if (game.isAWall(this.x, this.y-1) && this.vel.y < 0){
	    	this.vel.y = 0;
	    }
	    if (game.isAWall(this.x-1, this.y) && this.vel.x < 0){
	    	this.vel.x = 0;
	    }
	    if (game.isAWall(this.x+1, this.y) && this.vel.x > 0){
	    	this.vel.x = 0;
	    }

	    if (this.vel.x > 1){
	    	this.vel.x = 1;
	    } else if (this.vel.x < -1){
	    	this.vel.x = -1;
	    }
	    if (this.vel.y > 1){
	    	this.vel.y = 1;	
	    } else if (this.vel.y < -1){
	    	this.vel.y = -1;
	    }
	    this.vel.x *= .95;
		this.pos = this.pos.add(this.vel);
	    this.acc = new Vector(0,0);


	    while(game.isAWall(this.x, this.y)) {this.pos.y --;}
	}
}

