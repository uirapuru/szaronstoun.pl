var buttonsAmount = null;
var buttonsWidth = null;
var openedPanelWidth = 600;
var buttonIndex = -1;
            
var cache = new Array();
            
$(function(){    
    Cufon.replace('div.big-button > a');
                
    buttonsAmount = $("div.big-button").length;
    buttonsWidth = $("div.big-button").width();
                   
    var History = window.History; // Note: We are using a capital H instead of a lower h
    if ( !History.enabled ) {
        return false;
    }
                
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        var data = State.data;

        _gaq.push(['_trackPageview']);

        if(data.content == undefined)
        {
            $("div#content").fadeOut(600,function(){   
                $("div#content").addClass("hidden");
                touchBigButton(undefined);       
                $(window).resize();
            });
        } else {
            $("div#content").fadeOut(600,function(){
                if($(this).hasClass("hidden"))
                {
                    $(this).removeClass("hidden");
                }
                touchBigButton(data.clicked);
                $(this).html(data.content);
                $(this).fadeIn(600); 
                $(window).resize();
            });
        }
    });
                
    $(window).resize(function(){
        var windowW = $(window).width();           
        var totalW = null;
        var addition = 0;
        var left; 
                    
        if($("div.panelset.opened").length == 0)
        {
            totalW = buttonsAmount * buttonsWidth;
            left = (windowW - totalW) / 2;
        } else {
            buttonIndex = $("div.panelset.opened").index();
                        
            left = windowW / 2 - (buttonIndex * buttonsWidth + openedPanelWidth / 2);
        }
                                        
        $("div#wrapper").css({
            left : left
        });
                   
    });
                
    $("div.big-button > a").click(function(e){
        e.preventDefault();
    });
                
    $("div.big-button").click(function(){
                    
        var href = $(this).children('a').attr('href');
        buttonIndex = $(this).parents("div.panelset").index();
                    
        if(cache[buttonIndex] == undefined)
        {
            $.get(href,{
                format:"json"
            },function(data){
                if(data.status == 'ok')
                {
                    data.clicked = buttonIndex;
                    cache[buttonIndex] = data;
                    History.pushState(cache[buttonIndex],cache[buttonIndex].headTitle,href);
                } else {
                    $("div#content").html(data.message);
                    return false;
                }
            },"json").error(function(){
                alert('Błąd odpowiedzi z serwera!');
            }); 
            return;
        }
                    
        History.pushState(cache[buttonIndex],cache[buttonIndex].headTitle,href);
                    
    });
                
    $("nav a#prev").click(function(){
        $("div.big-button").eq((buttonIndex <= 0 ? buttonsAmount - 1 : buttonIndex - 1)).click(); 
    });
    $("nav a#next").click(function(){
        $("div.big-button").eq((buttonIndex == buttonsAmount -1 ? 0 : buttonIndex + 1)).click(); 
    });
            
    $(window).resize();
});
            
function touchBigButton(index)
{
    buttonIndex = index;
    $("div.panelset").removeClass('opened');
    $("div.big-button").eq(index).parents("div.panelset").addClass('opened');
}
