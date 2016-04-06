/*
 * This file require AjaxZilli.js
 * This file require zilli.js
 * This file require Jquery.js
 */
function Login(settings){
    var f = this,
    url_request = settings.urlRequest,
    url_captcha = settings.urlImg,
    label = {
        title           : "Iniciar Sesión",
        subtitle        : "Plataforma Educativa",
        lbluser         : "Usuario",
        lblpassword     : "Contraseña",
        lblcaptcha      : "Ingrese las letras en blanco",
        btnsign         : "Iniciar Sesión"
    };
    
    f.formbox = _SQS.id(settings.id),
    f.node = {
        form     : _SQE.mk("form", {"action": "javascript:void(null);"}),
            title   : _SQE.mk("div", {"class":"title"}),
            subtitle    : _SQE.mk("div", {"class":"subtitle"}),
            message     : _SQE.mk("div", {"class":"message"}),
            lbluser     : _SQE.mk("label"),
            txtuser     : _SQE.mk("input", {"type":"text", "name":"user"}),
            lblpassword : _SQE.mk("label"),
            txtpassword : _SQE.mk("input", {"type":"password", "name":"password"}),
            img         : _SQE.mk("img"),
            lblcaptcha  : _SQE.mk("label"),
            txtcaptcha  : _SQE.mk("input", {"type":"text", "name":"captcha"}),
            btnsign     : _SQE.mk("input", {"type":"submit", "value": label.btnsign})
    };
    
    //Render
    $(f.node.img).hide();
    $(f.node.lblcaptcha).hide();
    $(f.node.txtcaptcha).hide();
    
    $(f.node.title).text(label.title);
    $(f.node.subtitle).text(label.subtitle);
    $(f.node.lbluser).text(label.lbluser);
    $(f.node.lblpassword).text(label.lblpassword);
    $(f.node.lblcaptcha).text(label.lblcaptcha);
    
    _SQE.addChild(f.node.form, f.node.title);
    _SQE.addChild(f.node.form, f.node.subtitle);
    _SQE.addChild(f.node.form, f.node.message);
    _SQE.addChild(f.node.form, f.node.lbluser);
    _SQE.addChild(f.node.form, f.node.txtuser);
    _SQE.addChild(f.node.form, f.node.lblpassword);
    _SQE.addChild(f.node.form, f.node.txtpassword);
    _SQE.addChild(f.node.form, f.node.img);
    _SQE.addChild(f.node.form, f.node.lblcaptcha);
    _SQE.addChild(f.node.form, f.node.txtcaptcha);
    _SQE.addChild(f.node.form, f.node.btnsign);
    _SQE.addChild(f.formbox, f.node.form);
    
    //Events
    f.node.btnsign.onclick = function(){
        f.send();
    };
    
    //Methods
    f.send = function(){
        if(!url_request){
            console.log("You don't established the url of request");
            return;
        }
        
        var form = f.node.form,
        elements = form.elements,
        param_string = "";
        
        _SQS.each(elements, function(elem){
            var value, success = false;
            
            switch (elem.type) {
                case "text":
                case "password":
                    value = elem.value;
                    success = true;
                    break;
            }
            
            if(success){
                value = value.replace(/'/g, '"').replace(/&/g, " ");
                param_string += "&" + elem.name + "=" + encodeURI(value);
            }
        });

        AjaxPOST_TEXT(url_request, param_string, function(responseText){
            var data = JSON.parse(responseText);
            
            if(data.success){
                window.location.href = data.url_redirect; 
            }else{
                if(data.message){
                    $(f.node.message).text(data.message);
                    $(f.node.message).fadeIn(1000);
                }
                
                if(data.err){
                    console.log(data.err);
                }
                
                if(data.captcha){
                    $(f.node.txtpassword).val("");
                    
                    f.node.img.src = url_captcha;
                    $(f.node.img).show();
                    $(f.node.lblcaptcha).show();
                    $(f.node.txtcaptcha).show();
                }
            }
        });
    };
    
    f.send();
};
/*
<form>
    <div class="title">Iniciar Sesión</div>
    <div class="subtitle">Plataforma Educativa</div>
    <div class="message" id="message"></div>
    <label>Usuario</label>
    <input type="text" name="Usuario">
    <label>Contraseña</label>
    <input type="password" name="Contrasena">
    <input type="submit" value="Iniciar Sesión">
</form> 
*/