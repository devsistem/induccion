function submit(formulario, accion)
{
	var form = document.forms[formulario];
	form['accion'].value = accion;
	form.submit();
}

function checkearTodos(formulario, nombre, checked, clicking)
{
	clicking = (clicking==true);
	var form = document.forms[formulario];
	var arrElemento = form.elements;
	for(var i=0; i<arrElemento.length; i++)
	{
		if('checkbox'==arrElemento[i].type && nombre==arrElemento[i].name)
		{
			if(clicking)
			{
				if(arrElemento[i].checked!=checked)
				{
					arrElemento[i].click();
				}
			}
			else
			{
				arrElemento[i].checked = checked;
			}
		}
	}
}

function openPopUpAdmin(file, name)
{

	var url = file;
	var oSize = getSizePopUp(file);
	openPopUp(url, name, oSize.width, oSize.height, 'no', 'yes');
}

function openPopUpFromAdmin(file)
{

	var url = 'admin/' + file;
	var oSize = getSizePopUp(file);
	window.resizeTo(oSize.width + 10, oSize.height + 30);
	window.location.href = url;

}


function getSizePopUp(file)
{
	var hAdmin = 429;
	var w = 608	;
	var h = hAdmin;
	var pi = pathinfo(file);
	//alert(pi.filename);
	switch(pi.filename.toLowerCase())
	{
	
	  /* Banners */

		case 'edit_banners':  h=600; w=680; break;
		case 'edit_categoria':  h=600; w=680; break;
		case 'edit_video':  h=600; w=680; break;
		case 'edit_foto':  h=400; w=780; break;
    case 'edit_mailing_template':  h=700; w=800; break;
    case 'edit_ip':  h=150; w=640; break;
    
		/* Estativcos */
		case 'edit_estatico': h=600; w=780; break;
    
    /* Dinamicos */
		case 'edit_contenidos': h=600; w=780; break;
		case 'edit_texto': h=600; w=780; break;
		case 'edit_multimedia': h=600; w=780; break;

		/* Noticias */
		case 'admin_news': h=600; w=780; break;
    case 'edit_noticia':  h=700; w=800; break;
    case 'edit_evento':  h=700; w=800; break;
    

		/* Producto */
    case 'edit_producto':  h=600; w=780; break;

		/* Foros */
		case 'admin_foro': h=600; w=780; break;
    case 'edit_foro':  h=600; w=780; break;
        
    /* Links */
		case 'adminlinks': h=600; w=780; break;
    case 'editlink':   h=420; w=780; break;

		/* Categorias */
		case 'edit_mailing_grupo':  h=200; w=450; break;

		/* Backend Usuario */
		case 'adminbackendusuario':  h=600; w=780; break;
		case 'editbackendusuario': 	h=600; w=780; break;

		/* Usuario */
		case 'adminusuario': h=hAdmin; break;
		case 'exportusuario':
		case 'editusuario': h=467; break;
	}

	if(!isMSIE())
	{
		h += 16;
		w -= 4;
	}
	return {width:w, height:h};
}


function abrirAdjunto(nombreFormulario, idAdjuntoCampo, descripcionCampo, extensionCampo, funcionActualizar, idAdjuntoTipo, idAdjuntoCategoria)
{
	var formulario = document.forms[nombreFormulario];
	var idAdjunto = ('undefined'==typeof idAdjuntoCampo && idAdjuntoCampo!=null ? formulario[idAdjuntoCampo].value :0);
	var descripcion = ('undefined'==typeof descripcionCampo && descripcionCampo!=null ? formulario[descripcionCampo].value : '');
	var extension = ('undefined'==typeof extensionCampo && extensionCampo!=null ? formulario[extensionCampo].value : '');
	var url;

	funcionActualizar = 'undefined'==typeof funcionActualizar ? '' : funcionActualizar;

	url = 'selectAdjunto.php?idAdjunto=' + idAdjunto;
	url += '&descripcion=' + descripcion;
	url += '&extension=' + extension;
	url += '&funcionActualizar=' + funcionActualizar;
	url += '&formulario=' + nombreFormulario;
	url += '&idAdjuntoCampo=' + idAdjuntoCampo;
	url += '&descripcionCampo=' + descripcionCampo;
	url += '&extensionCampo=' + extensionCampo;
	url += '&buscar_idAdjuntoTipo=' + idAdjuntoTipo;
	url += '&buscar_idCategoria=' + idAdjuntoCategoria;


	openPopUpAdmin(url, 'AdminAdjunto');
}

function abrirCalendario(nombreFormulario, nombreCampo, funcionActualizar, funcionCerrar, tieneHorario)
{
	var formulario = document.forms[nombreFormulario];
	var fecha = formulario[nombreCampo].value;
	var url;

	funcionActualizar = 'undefined'==typeof funcionActualizar ? '' : funcionActualizar;
	funcionCerrar     = 'undefined'==typeof funcionCerrar     ? '' : funcionCerrar;
	tieneHorario      = 'undefined'==typeof tieneHorario      ? 0 : tieneHorario;

	url = '../calendario_popup.php?fecha=' + fecha;
	url += '&funcionActualizar=' + funcionActualizar;
	url += '&funcionCerrar=' + funcionCerrar;
	url += '&tieneHorario=' + tieneHorario;
	url += '&formulario=' + nombreFormulario
	url += '&campo=' + nombreCampo;

	if(formulario[nombreCampo].disabled!=true)
	{
		openPopUp(url,'Calendario', 1, 1,'no','no');
	}
}


function actualizarFecha(formulario, campo, fecha)
{
	try
	{
		var formulario = document.forms[formulario];
		formulario[campo].value = dateToFecha(fecha);
	}
	catch(e){}
}

function borrarCampo(nombreFormulario, nombreCampo)
{
	var formulario = document.forms[nombreFormulario];
	if(formulario[nombreCampo] && formulario[nombreCampo].disabled!=true)
	{
		formulario[nombreCampo].value = '';
	}
}

function dateToFecha(fecha)
{
	var retorno = '';
	if('undefined'!=typeof fecha && fecha)
	{
		retorno = fecha.split('-').reverse().join('-');
	}
	return retorno;
}

function irPagina(pagina)
{
	try
	{
		var form = document.forms['frmPrincipal'];
		form['pagina'].value = pagina;
		submit('frmPrincipal', 'buscar');
	}
	catch(e)
	{
		alert('Error al intentar cambiar de página.');
	}
}

function resizePopUpAdmin()
{
	var oSize = getSizePopUp(window.location.href);
	window.resizeTo(oSize.width + 10, oSize.height + 30);
	window.focus();
}

/* SESSION */
function sessionTimeout()
{
	var toLO = window.setTimeout('sessionAutoLogout()', 10 * 1000);
	openPopUp('sessiontimeout_popup.php?toLO=' + toLO, 'SessionTimeout', 198,155);
}

function sessionAutoLogout()
{
	window.location.href = 'index.php?p=backendlogin';
}

function sessionTimeoutStart()
{
	//var cookies = new Cookies();
	var timeout = 'undefined'==SESSION_TIMEOUT_ALERT ? 600 : SESSION_TIMEOUT_ALERT;

	if(isMSIE())
	{
		timeout += SERVER_TS_DIFF;
	}

	if(false && timeout>1)
	{
		window.setTimeout('sessionTimeout()', timeout * 1000);
	}
}
/* FIN SESSION */

function tsDiff(ts)
{
	var fecha = new Date();
	return (ts - Math.round(fecha.getTime()/1000));
}

function onLoadDefault()
{
	//sessionTimeoutStart();
	if(window.opener)
	{
		resizePopUpAdmin();
	}
}
window.onload = onLoadDefault;