// JavaScript Document

function empty(x)
{
	return x == '';
}

function numeric(x)
{
   var ValidChars = "0123456789.";
   var numeric=true;
   var Char; 
   for (i = 0; i < x.length && numeric == true; i++) 
   { 
     Char = x.charAt(i); 
     if (ValidChars.indexOf(Char) == -1)	 
		 	numeric = false;		 
		}
   return numeric;   
}

function email(x)
{
  email_regx = /^[^@]+@[^@]+.[a-z]{2,}$/i;
	return !(x.search(email_regx) == -1); 
}
