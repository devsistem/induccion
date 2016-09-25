/*
*  mapa_utils.js
*  alejandro gassmann desarrollo@interlogical.net
*/
function utilMetrosToKilometros(metros){
   var km = 0;
   km = metros / 1000;
   km = Math.round(km*100)/100 
   return km;
}

function utilMetrosToMillas(metros) {
}

function utilSegundosToHoras(segundos){
	  var horas = 0;
	  horas = segundos / 60;
	  horas = Math.round(horas*100)/100 
	  return horas;
}