$(function() {
    var wrapper = $("#wrapper");
    var main    = $("#main");
    var menu    = $("#menu");
    var slide   = function(parts, options) {
        parts.animate(options, 350, "easeOutQuart");
    };
    
    (function() {
        var mainHeight = main.height();
        var menuHeight = menu.height();
        if (mainHeight < menuHeight) {
            main.height(menuHeight);
        }
    })();
    
    $("<div/>").attr("id", "open").append("&#x25B6;").prependTo($("header")).on("click", function() {
        var left    = main.css("left");
        var opening = (left === "auto" || left === "0px");
        
        $(this).html(opening ? "&#x25C0;" : "&#x25B6;");
        slide(wrapper, { backgroundPosition : (opening ? "0px 0px"  : "-301px 0px") });
        slide(main, { left : (opening ? "301px" : "0px") });
        slide(menu, { left : (opening ? "0px" : "-301px") });
    });
    
    $(window).resize(function() {
        if (768 < $(window).width()) {
            wrapper.css("background-position", "");
            main.css("left", "");
            menu.css("left", "");
        }
    });
});
