$(window).load(function(){
// inner variables
var canvas, ctx;
var clockRadius = 75;
var clockImage;

// draw functions :
function canvasClean() { // clear canvas function
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
}

function drawScene() { // The game loop to  be exectued as per the interval timer.
    canvasClean(); // Clean up the canvas

    //Initialisation des variables à l'aide de l'heure locale du navigateur
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    
    // Gestion des heures PM
    if (hours > 12) {
        hours = hours - 12;
    }
    
    //Passage des heures et des minutes en chiffres à virgule.
    var numHour = hours + minutes / 60;
    var numMinute = minutes + seconds / 60;

    ctx.save();

    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.beginPath();

    // Affichage des 12 chiffres de l'horloge
    ctx.font = '16px Sans-Serif';
    ctx.fillStyle = '#000';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    for (var n = 1; n < 13; n++) {
        var theta = (n - 3) * (Math.PI * 2) / 12;
        var x = clockRadius * 0.7 * Math.cos(theta);
        var y = clockRadius * 0.7 * Math.sin(theta);
        ctx.fillText(n, x, y);
    }

    //Affichage des 60 points de l'horloge
    for(var i=1; i<=60; i++){
        var tTheta = (i - 3) * (Math.PI * 2) / 60;
        var x1 = clockRadius * 0.9 * Math.cos(tTheta);
        var y1 = clockRadius * 0.9 * Math.sin(tTheta);
    
        ctx.beginPath();
        ctx.arc(x1,y1,1,0,Math.PI * 2,true);
        ctx.closePath();
        ctx.stroke();
    } 
    
    // Affichage de l'aiguille des secondes (trotteuse)
    ctx.save();
    var theta = (seconds - 15) * 2 * Math.PI / 60;
    ctx.rotate(theta);
    ctx.beginPath();
    ctx.moveTo(-15, -0.5);	// -0.5 et 0.5 définissent une largeur =1 de l'aiguille.
    ctx.lineTo(-15, 0.5);
    ctx.lineTo(clockRadius * 0.8, 0.5);
    ctx.lineTo(clockRadius * 0.8, -0.5);
    ctx.fillStyle = '#000000';  // black
    ctx.fill();
    ctx.restore();
    
    // Affichage de l'aiguille des minutes
    ctx.save();
    var theta = (numMinute - 15) * 2 * Math.PI / 60;
    ctx.rotate(theta);
    ctx.beginPath();
    ctx.moveTo(-15, -4);
    ctx.lineTo(-15, 4);
    ctx.lineTo(clockRadius * 0.8, 1);
    ctx.lineTo(clockRadius * 0.8, -1);
    ctx.fillStyle = '#f08000';  // orange
    ctx.fill();
    ctx.restore();
    
    // Affichage de l'aiguille des heures
    ctx.save();
    var theta = (numHour - 3) * 2 * Math.PI / 12;
    ctx.rotate(theta);
    ctx.beginPath();
    ctx.moveTo(-15, -5);
    ctx.lineTo(-15, 5);
    ctx.lineTo(clockRadius * 0.5, 1);
    ctx.lineTo(clockRadius * 0.5, -1);
    ctx.fillStyle = '#ff0000';  // red
    ctx.fill();
    ctx.restore();

    ctx.restore();
}

// initialization
$(document).ready(function() {

    canvas = $('#clock')[0];
    ctx = canvas.getContext('2d');
    //TODO add stuff to show and update the FPS during the run.
    
    // Mise à jour de l'affichage chaque seconde.
    setInterval(drawScene, 1000); 
});

});