/*
 * This file require zilli.js
 * This file require Jquery.js
 */
function chatBox(receiver, transmitter){
    //CONSTANTS
    var c = this;
    
    //Events
    var onfocus = function(){},
    onclose = function(){};
    
    //PUBLIC VARS
    c.transmitter = transmitter,
    c.receiver = receiver,
    c.chat_id = null,
    c.messageBoxes = [],
    c.node = null,
    c.focused = false,
    c.showed = false;
    
    //PUBLIC METHODS
    
    c.addMessageBox = function(messagebox, init){
        if(init){
            c.messageBoxes.splice(0, 0, messagebox);
            $(messagebox.node.messagebox).insertAfter(c.node.cControl);
            
            //Hide date details
            $(".m_d_date", c.node.content).each(function(){
                $(this).hide();
            });
        }else{
            //Hide date details
            $(".m_d_date", c.node.content).each(function(){
                $(this).hide();
            });
            
            c.messageBoxes.push(messagebox);
            $(c.node.content).append(messagebox.node.messagebox);
            
            if(!c.focused && c.showed){
                setNewMessageAnimation();
            }
        }
        
        var dateLocaleStringOptions = {weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "numeric", minute: "numeric", hour12:"false"},
        dateString = (new Date(messagebox.date_sent)).toLocaleString("es-ES", dateLocaleStringOptions);
        
        //Add tooltip
        tooltip($(messagebox.node.img_message), $(messagebox.node.messagebox), dateString);
    },
    c.appendTo = function(panel){
        $(c.node.chatbox).appendTo(panel);
    },
    c.hide = function(){
        $(c.node.chatbox)
        .removeAttr("data-showed")
        .hide();
        
        c.showed = false;
    },
    c.minimize = function(){
        $(c.node.chatbox).attr("data-minimize", true);
        $(c.node.body).hide();
    },
    c.maximize = function(){
        $(c.node.chatbox).removeAttr("data-minimize");
        $(c.node.body).show();
    },
    c.scroller = function(){
        $(c.node.content).animate({"scrollTop": c.node.content[0].scrollHeight}, "fast");
    },
    c.setRightPosition = function(right){
        $(c.node.chatbox).css({"right": right});
    },
    c.show = function(){
        $(c.node.chatbox)
        .attr("data-showed", true)
        .show("fast");
        
        c.showed = true;
    },
    c.close = function(callback){
        onclose = callback;
        
        return c;
    },
    c.onsendmessage = function(){},
    c.onrecoverymessages = function(){},
    c.focus = function(callback){
        onfocus = callback;
        
        return c;
    };
    
    //private vars and methods
    var newMessageAnimationInterval = null,
    newMessageAnimationStatus = true,
    setNewMessageAnimation = function(){
        if(newMessageAnimationInterval){
            clearNewMessageAnimation();
        }
        
        newMessageAnimationInterval = setInterval(function(){
            if(newMessageAnimationStatus){
                newMessageAnimationStatus = false;
                
                c.node.chatbox.addClass("blink");
            }else{
                newMessageAnimationStatus = true;
                
                c.node.chatbox.removeClass("blink");
            }
        }, 1000);
    },
    clearNewMessageAnimation = function(){
        clearInterval(newMessageAnimationInterval);
        
        c.node.chatbox.removeClass("blink");
    },
    tooltip = function ($hoveredNode, $relativeNode, tooltipInformation){
        var n = {
            tooltip     : $("<div>").attr("class", "tooltip"),
                arrow   : $("<div>").attr("class", "arrow"),
                content : $("<div>").attr("class", "content").text(tooltipInformation)
        };

        //Render node
        n.tooltip
        .append(n.arrow)
        .append(n.content);

        n.tooltip.insertAfter(c.node.chatbox);

        $hoveredNode.hover(function(){
            n.tooltip.show();
            
            var xPosition = $relativeNode.offset().left,
            yPosition = $relativeNode.offset().top;

            n.tooltip
            .css({
                right   : $(window).width() - xPosition + 10,
                top     : yPosition
            });
        }, function(){
            n.tooltip.hide();
        });
    },
    shiftKeyPressed = false;
    
    c.node = {
        chatbox                     : $("<div>").attr({"class":"chatbox"}),
            header                  : $("<div>").attr({"class":"header"}),
                title               : $("<div>").attr({"class":"title"}),
                    indicator       : $("<div>").attr({"class":"indicator"}),
                    tText           : $("<div>").attr({"class":"text"}),
                hControl            : $("<div>").attr({"class":"control"}),
                    oClose          : $("<div>").attr({"class":"option"}),
                clear               : $("<div>").attr({"class":"clear"}),
            body                    : $("<div>").attr({"class":"body"}),
                content             : $("<div>").attr({"class":"content"}),
                    cControl        : $("<div>").attr({"class":"control"}),
                        oRecovery   : $("<div>").attr({"class":"option"}),
                input               : $("<div>").attr({"class":"input"}),
                    iText           : $("<textarea>").attr({"class":"text", "contenteditable":"true"}),
    };

    //Renderizando el control de usuario
    var chatbox_title = c.receiver.name + " " + c.receiver.last_name;
    chatbox_title = chatbox_title.substring(0, 22);
    chatbox_title += (chatbox_title.length >= 22)? "..." : "";

    $(c.node.tText).text(chatbox_title);
    $(c.node.oClose).html("<i class='icon-remove'></i>");
    $(c.node.oRecovery).text("Recuperar mensajes...");

    //Evaluando el estado del contacto para el chatbox
    if(!receiver.connected){
        $(c.node.indicator).hide();
    }

    $(c.node.header)
    .append(c.node.title)
    .append(c.node.hControl)
    .append(c.node.clear);
    $(c.node.body)
    .append(c.node.content)
    .append(c.node.input);
    $(c.node.content)
    .append(c.node.cControl);
    $(c.node.input)
    .append(c.node.iText);
    $(c.node.title)
    .append(c.node.indicator);
    $(c.node.title)
    .append(c.node.tText);
    $(c.node.hControl)
    .append(c.node.oClose);
    $(c.node.cControl)
    .append(c.node.oRecovery);
    $(c.node.chatbox)
    .append(c.node.header);
    $(c.node.chatbox)
    .append(c.node.body);

    
    //MAXIMIZE - MINIMIZE CHATBOX
    $(c.node.title).click(function(){
        if($(c.node.chatbox).attr("data-minimize")){
            c.maximize();
        }else{
            c.minimize();
        }
    });
    //HIDE CHATBOX
    $(c.node.oClose).click(function(){
        //Clear animation interval
        clearNewMessageAnimation();
        
        onclose();
    });
    //SENDMESSAGE CHATBOX
    $(c.node.iText)
    .on("keydown", function(e){
        switch(true){
            case e.keyCode === 13 && !shiftKeyPressed:
                e.preventDefault();
                
                var message = $(this).val();

                if(message.trim()){
                    $(this).val(null);

                    c.onsendmessage(message);
                }
                break;
            case e.keyCode === 16:
                shiftKeyPressed = true;
                break;
        }
    })
    .on("keyup", function(e){
        switch(true){
            case e.keyCode === 16:
                shiftKeyPressed = false;
                break;
        }
    })
    .focus(function(){
        //Change focused status
        c.focused = true;
        
        //Clear animation interval
        clearNewMessageAnimation();
        
        onfocus();
    })
    .blur(function(e){
        //Change focused status
        c.focused = false;
    });
    //RECOVERY OLD MESSAGES
    $(c.node.oRecovery).click(function(){
        c.onrecoverymessages();
    });
    //SET EVENT TO SCROLL FOR SHOW RECOVERY OLD MESSAGES
    $(c.node.content).scroll(function(){
        if(this.scrollTop === 0 &&  this.scrollHeight > (this.offsetHeight * 2)){
            $(c.node.oRecovery).fadeIn(500);
        }
    });
    //SET TIMER FOR DATELINKS
    setInterval(function(){
        for(var x in c.messageBoxes){
            var messagebox = c.messageBoxes[x],
            date_sent = messagebox.date_sent;

            if(date_sent){
                var date = new Date(date_sent),
                date_string = prettyDate(date);

                $(messagebox.node.m_d_date).text(date_string);
            }
        }
    }, 1000);
};

function messageBox(message, receiver, transmitter){
    //CONSTANTS
    var m = this;
    m.MESSAGE_ON = 1,
    m.MESSAGE_OUT = 0;
    
    m.date_sent = new Date(), // String or Date
    m.message = message, // String
    m.transmitter = transmitter, //User Object
    m.receiver = receiver, //User Object
    m.node = null;
    
    //PUBLIC METHODS
    m.setOrentation = function(movement){
        switch(movement){
            case this.MESSAGE_ON:
                _SQE.addClass(this.node.messagebox, "out");
                break;
            case this.MESSAGE_OUT:
                _SQE.addClass(this.node.messagebox, "in");
                break;
        }
    };
    
    m.node = {
        messagebox  : _SQE.mk("div", {"class":"messagebox"}),
            img_message : _SQE.mk("img", {"class":"img_message"}),
            arrow       : _SQE.mk("div", {"class":"arrow"}),
            message     : _SQE.mk("div", {"class":"message"}),
                text_message    : _SQE.mk("div", {"class":"text_message"}),
            clear_one   : _SQE.mk("div", {"class":"clear"}),
            message_detail  : _SQE.mk("div", {"class":"message_detail"}),
                m_d_date        : _SQE.mk("div", {"class":"m_d_date"}),
            clear_two   : _SQE.mk("div", {"class":"clear"})
    };

    //Renderizando el control de usuario
    $(m.node.img_message).attr({"src":m.transmitter.urlimage});
    $(m.node.text_message).text(m.message);
    $(m.node.m_d_date).text("Hace un momento");

    $(m.node.messagebox)
    .append(m.node.img_message)
    .append(m.node.arrow)
    .append(m.node.message)
    .append(m.node.clear_one)
    .append(m.node.message_detail)
    .append(m.node.clear_two);
    $(m.node.message)
    .append(m.node.text_message);
    $(m.node.message_detail)
    .append(m.node.m_d_date);
    
    m.node.messagebox.onclick = function(){
        var display = $(this).attr("data-display");

        if(display){
            $(this).removeAttr("data-display");
            $(m.node.m_d_date).fadeOut();
        }else{
            $(this).attr("data-display", true);
            $(m.node.m_d_date).fadeIn();
        }
    };
}

/* 
 <div class="chatbox">
    <div class="header">
        <div class="title">
            <div class="indicator"></div>
            <div class="text">Aaron Nunez</div>
        </div>
        <div class="control">
            <div class="option">X</div>
        </div>
        <div class="clear"></div>
    </div>
    <div class='body'>
        <div class="content">
            <div class="control">
                <div class="option"></div>
            </div>
            <div class="messagebox out">
                <span class="arrow"></span>
                <div class="message">
                    <div class="text_message">Hola este es un mensja emuy largo ja emuy largo ja emuy largo ja emuy largo</div>
                    <img class="img_message" src="/owlgroup/_imagenes/icon_user.png">
                </div>
                <div class="clear"></div>
                <div class="message_detail">
                    <div class="m_d_date">Hace un momento</div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="messagebox in">
                <span class="arrow"></span>
                <div class="message">
                    <div class="text_message">Hola este</div>
                    <img class="img_message" src="/owlgroup/_imagenes/icon_user.png">
                </div>
                <div class="clear"></div>
                <div class="message_detail">
                    <div class="m_d_date">Hace un momento</div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="input">
            <div contenteditable="true" class="text"></div>
        </div>
    </div>
</div>
 */