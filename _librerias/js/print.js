function PrintElem(elem)
{
	Popup($('<div/>').append($(elem).clone()).html());
}

function Popup(data) 
{
	var mywindow = window.open('', 'Print', 'height=400,width=600');
	mywindow.document.write('<html><head>');
	mywindow.document.write('<link rel="stylesheet" href="/owlgroup/_estilos/Style_Print.css" type="text/css" media="print" />');
	mywindow.document.write('</head><body>');
	mywindow.document.write(data);
	mywindow.document.write('</body></html>');

	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10

	mywindow.print();
	mywindow.close();

	return true;
}

function imprimir(id)
{
	var div, imp;
	div = document.getElementById(id);
	imp = window.open(" ","Formato de Impresion"); 
	imp.document.open();    
	imp.document.write("<style>#Hoja { text-align: center;font-family: arial; }#DivImg { margin: 2em 0;height: 100px;position: relative; }#Img { width: 260px;height: 100px;position: absolute;right: 2em; }#Tipo { font-weight: 600;text-align: center;margin: 0 auto; }#Name { font-family:Lucida Calligraphy;font-size: 2em;margin: 1.5em 7em;text-align: center; }#Text { margin: 3em 7em 6em 7em;text-align: center;}#Raya { width: 250px;margin: 0 auto; }#Firma { text-align: center;margin: 0.5em 7em;}</style>");
	imp.document.write('<img src="../../ArchivosEmpresa/marco.png" style="width: 93%;height: 90%;position: absolute;top: 0;left: 0;z-index: 0;opacity: 0.5;border: 1.7em #6BC9F5 solid;">'+div.innerHTML);
	imp.document.close();
	imp.print();   
	imp.close(); 
}

							
/*
<style type="text/css" media="print,screen">

@media print 
 {<style type="text/css" media="print">@page port {size: portrait;}@page land {size: landscape;}.portrait {page: port;}.landscape {page: land;}</style>
*/











