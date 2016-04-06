/*
 * Generate js tasks
 */
//Validate if session is finished
 var verify = setInterval(function _(){
     AjaxGET_TEXT("/owlgroup/_vistas/gad_verify_.php", function(response){
         var data = JSON.parse(response);
         
         if(!data){
             var popup = new $.Popup({
                 afterOpen   : function(){
                     setTimeout(function(){
                        debugger;
                        var urlid = (window.Storage)? "/" + window.localStorage.getItem("urlId") : "";
                        
                        window.location.href = window.location.origin + urlid;
                     }, 5000);
                 },
                 closeContent: null,
                 modal       : true
             });

             popup.open("/owlgroup/_vistas/se_alert.php?type=session_died");
             clearInterval(verify);
         }
     });
 }, 60000);


 //Valida Asistencia de Curso
 function _assist(interval){
     setTimeout(function(){
         var popup = new $.Popup({
             afterOpen   : function(){
                 var tiempo = 9;
                 var setInt = setInterval(function(){
                     $("TimerTime").html(tiempo);
                     if(tiempo == 0){
                         $.post("/owlgroup/room.php?asistencia=finaliza",function(success){
                            var urlid = (window.Storage)? "/" + window.localStorage.getItem("urlId") : "";
                             
                            window.location.href = window.location.origin + urlid;
                         }, "json")
                         
                         clearInterval(setInt);
                     }else{
                         tiempo--;
                     }
                 }, 1000);
                 $("#VAssist").click(function(){
                     console.log("renueva interval: "+setInt);
                     console.log("nuevo tiempo: "+interval);
                     _assist(interval);
                     clearInterval(setInt);
                     popup.close();
                 })
             },
             closeContent: null,
             modal       : true
         });
         popup.open("/owlgroup/_vistas/se_alert.php?type=verifyAssist");
     },interval*1000)
 }


 function _concurrencia(interval){
     setTimeout(function(){
         var popup = new $.Popup({
             afterOpen   : function(){
                 var tiempo = 9;
                 var setInt = setInterval(function(){
                     $("TimerTime").html(tiempo);
                     if(tiempo == 0){
                        popup.close();
                        clearInterval(setInt);
                        
                        window.location.href = window.document.origin + "/owlgroup/cierra_session.php";
                     }else{
                         tiempo--;
                     }
                 }, 1000);
                 $("#VAssist").click(function(){
                     _concurrencia(interval);
                     clearInterval(setInt);
                     popup.close();
                 })
             },
             closeContent: null,
             modal       : true
         });
         popup.open("/owlgroup/_vistas/se_alert.php?type=verifyConcurrencia");
     },interval*1000)
 }

 window.onbeforeunload = closeSession;
 function closeSession(){
     if(location.pathname == "/owlgroup/room.php") {
         $.ajax({
             async: false,
             cache: false,
             url: "/owlgroup/room.php?asistencia=finaliza",
             type: "post"
         });
         return null;
     }
 }
/*
$(window).on('beforeunload',function(e){
  if(location.pathname == "/owlgroup/room.php"){
    console.log("1");
    e.preventDefault();
    $.post("/owlgroup/room.php?asistencia=finaliza");
  }
})*/