var Lingo = {
    init: function () {
        $(".lingo-letter > div").click(function () {
            $(this).focus();
        });

        $(".lingo-letter > div").keydown(function (e) {
            e.preventDefault();
        });

        $(".lingo-letter > div").keyup(function (e) {
            console.log("keyCode: " + e.keyCode);
            var currentIndex = parseInt($(this).parent().parent().index());

            if (e.keyCode === 46) { //delete
                $(this).html(".");
            } else if (e.keyCode === 8) { //backspace
                $(this).html(".");
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).focus();
            } else if (e.keyCode === 74) { // j
                if($(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).html().trim() === "I"){
                    $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).html("IJ");
                } else if ($(this).html().trim() === "I" && $(this).is(":last-child")) {
                    $(this).html("IJ");
                } else {
                    $(this).html(String.fromCharCode(e.keyCode));
                    $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
                }
            } else if (String.fromCharCode(e.keyCode).match(/[A-Z]/i)) {
                $(this).html(String.fromCharCode(e.keyCode));
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
            } else if (e.keyCode === 37) { //arrow left
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).focus();
            } else if (e.keyCode === 39) { //arrow right
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
            } else if (e.keyCode === 13) { //enter
                Lingo.check();
            }
        });

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

        }
    },
    setSize: function (size) {
        $(".lingo-letter, .lingo-letter > div").css({
            "width": size + "px",
            "height": size + "px",
            "font-size": (0.7 * parseInt(size)) + "px"
        });
    },
    
    check: function () {
        var word = [];
        $('.lingo-current > td > div > div').each(function(i, selected){
            word[i] = $(selected).html().trim();
        });
        $.post("play/check", { word: word.join("") }, function (data) {
            var json = JSON.parse(data);
            if(json.error != null){
                alert(json.error);
            } else {
                $('.lingo-current > td > div').each(function(i, selected){
                    if (json.letters[i] === 1) {
                        $(selected).addClass("lingo-letter-yellow");
                    } else if (json.letters[i] === 2) {
                        $(selected).addClass("lingo-letter-red");
                    }
                });
            }
            if(json.win){
                alert("Congratulations!");
            } else {
                Lingo.nextGuess();
            }
        });
    },

    nextGuess: function () {
        var index = $(".lingo-current").index();
        $('.lingo-current > td > div > div').removeAttr("contenteditable").removeAttr("tabindex");
        $(".lingo-current").removeClass("lingo-current");
        /*
        $('.lingo > tr').each(function(i, selected){
            if($(selected).hasClass("lingo-current")){
                index = i;
                $(selected).removeClass("current");
            }
        });
        */
        index = index + 1;
        $('.lingo > tbody > tr').eq(index).addClass("lingo-current");
        $('.lingo-current > td > div > div').prop("contenteditable", false).prop("tabindex", 0).html(".");
        $('.lingo-current > td > div > div').eq(0).focus();
    },
};

