var Lingo = {
    language: "",
    time: 0,
    tries: 5,
    letters: 6,
    startLetters: [],
    rightLetters: [],
    size: 50,
    mobile: false,
    previousContent: null,
    enterPressed: true,

    /**
     * Initialize the object
     */
    init: function () {
        Lingo.reset();

        $(".lingo-letter > div").click(function () {
            $(this).focus();
        });

        $(".lingo-letter > div").focus(function () {
            Lingo.previousContent = $(this).html().trim().toUpperCase(); //Set previousContent for mobile (to recognise the new character)
        });

        $(".lingo-letter > div").keydown(function (e) {
            e.preventDefault();
            Lingo.previousContent = $(this).html().trim().toUpperCase();
        });

        $(".lingo-letter > div").bind("input keyup", function (e) {

            if(typeof e.keyCode === "undefined" || e.keyCode === 229) { //If mobile
                var difference = getDifference(Lingo.previousContent, $(this).html().trim().toUpperCase()); //Get new character
                if(difference.trim().length === 0 || $(this).html().trim().indexOf("<BR>") !== -1) {
                    $(this).html(Lingo.previousContent);
                } else {
                    e.keyCode = difference.charCodeAt(0); //Set keycode from new character
                }
            }
            e.preventDefault();
            //console.log("keyCode: " + e.keyCode);
            var currentIndex = parseInt($(this).parent().parent().index()); //Get current square index in row

            if (e.keyCode === 46) { //delete
                if(!Lingo.enterPressed) {
                    $(this).html(".");
                }
            } else if (e.keyCode === 8) { //backspace
                if(!Lingo.enterPressed) {
                    if ($(this).html().trim() === ".") {
                        $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).html(".").focus();
                    }
                    $(this).html(".");
                }
            } else if (e.keyCode === 74 && Lingo.language === "nl") { // j
                if(!Lingo.enterPressed) {
                    if (($(this).html().trim() === "I" || Lingo.previousContent === "I") && $(this).parent().parent().is(":last-child")) {
                        $(this).html("IJ");
                    } else if ($(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).html().trim() === "I") {
                        $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).html("IJ");
                        $(this).html(Lingo.previousContent);
                    } else {
                        $(this).html(String.fromCharCode(e.keyCode));
                        $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
                    }
                }
            } else if (e.keyCode === 37) { //arrow left
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex - 1).focus();
            } else if (e.keyCode === 39) { //arrow right
                $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
            } else if (e.keyCode === 13) { //enter
                if(!Lingo.enterPressed) {
                    $(this).blur();
                    Lingo.enterPressed = true; //Prevent multiple enters firing
                    Lingo.check();
                }
            } else if (String.fromCharCode(e.keyCode).match(/[A-Z]/i)) {
                if(!Lingo.enterPressed) {
                    $(this).html(String.fromCharCode(e.keyCode));
                    $(".lingo-current > td > .lingo-letter > div").eq(currentIndex + 1).focus();
                }
            }
        });

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            console.log("Mobile on");
            Lingo.mobile = true;
            //$("div[contenteditable=false]").prop("contenteditable", true); //Make editable for mobile keyboard
            Lingo.setMobileSize();
            /*
            $(window).resize(function () {
                Lingo.setMobileSize();
            });
            */
        }

        $(".lingo-progress").outerWidth($(".lingo").outerWidth());

        //Start first guess
        var audio = new Audio("./audio/newletter.mp3");
        audio.play();
        Lingo.nextGuess();
    },

    setMobileSize: function () {
        var deviceWidth = $(window).width() > $(window).height() ? $(window).height() : $(window).width();
        var squareSize = (deviceWidth - ((parseInt(Lingo.letters) + 2) * 3)) / Lingo.letters; //Set width to screen width
        //console.log(squareSize);
        Lingo.setSize(Math.ceil(squareSize));

        //Fixed time bar to the top of the page
        $(".lingo").css({
            "margin-top": $(".lingo-progress").outerHeight() - 1
        });
        $(".lingo-progress").css({
            position: "fixed",
            top: "50px",
            left: $(".lingo").offset().left
        });
    },

    reset: function () {
        Lingo.stopTimer();
        $(".lingo-letter > div").off("click").off("keydown").off("keyup").off("focus");
        $(".lingo > tbody").html("");
        for(var i = 0; i < Lingo.tries; i++) {
            $(".lingo > tbody").append("<tr></tr>");
        }
        for(var i = 0; i < Lingo.letters; i++) {
            $(".lingo > tbody > tr").append("" +
                "<td>" +
                "<div class='lingo-letter'>" +
                "<div></div>" +
                "</div>" +
                "</td>");
        }
        $(".lingo-current").removeClass("lingo-current");
    },

    setSize: function (size) {
        Lingo.size = size;
        $(".lingo-letter, .lingo-letter > div").css({
            "width": size + "px",
            "height": size + "px",
            "font-size": (0.7 * parseInt(size)) + "px"
        });
    },
    
    check: function () {
        Lingo.stopTimer();

        var word = []; //Get all squares and put them in an array
        $('.lingo-current > td > div > div').each(function(i, selected){
            word[i] = $(selected).html().trim();
        });
        $.post(API_URL + "api/check", { word: word.join(""), language: Lingo.language }, function (data) {
            var json = JSON.parse(data);
            if(json.error !== null){
                var audio = new Audio("./audio/timeup.mp3");
                audio.play();
                alert($(".lingo-doesNotExist").html().replace("%s", json.error));
            } else {
                //Show state of each square, one by one, 220ms after each other
                $('.lingo-current > td > div').each(function(i, selected){
                    setTimeout(function () {
                        Lingo.showCheck(json, i, selected);
                    }, i * 220);
                });
            }
            setTimeout(function () {
                if(json.win){
                    /*
                    $('.lingo-current > td > div').each(function(i, selected){
                        setTimeout(function () {
                            $(selected).addClass("lingo-letter-win");
                        }, (parseInt(Lingo.letters) * 100) - (i * 100));
                        setTimeout(function () {
                            $(selected).removeClass("lingo-letter-win");
                        }, (2 * parseInt(Lingo.letters) * 100) - (i * 100));
                    });
                    */
                    Lingo.nextWord(true);
                } else {
                    Lingo.nextGuess();
                }
            }, Lingo.letters * 220);
        });
    },

    showCheck: function (json, i, selected) {
        if (json.letters[i] === 1) {
            var audio = new Audio("./audio/letter1.mp3");
            audio.play();
            $(selected).addClass("lingo-letter-yellow");
        } else if (json.letters[i] === 2) {
            var audio = new Audio("./audio/letter2.mp3");
            audio.play();
            $(selected).addClass("lingo-letter-red");
        } else {
            var audio = new Audio("./audio/letter0.mp3");
            audio.play();
        }
    },

    nextGuess: function () {
        var index = $(".lingo-current").index(); //Current row index
        if(index !== -1){ //Check first guess (initializer)
            $('.lingo-current > td > div > div').removeAttr("contenteditable").removeAttr("tabindex"); //Disable focus and editing

            $('.lingo-current > td > div > div').each(function (i, selected) { //Add right letters to right letters array so they appear automatically next guess
                if ($(selected).parent().hasClass("lingo-letter-red")) {
                    Lingo.rightLetters[i] = $(selected).html().trim().toUpperCase();
                }
            });
            $(".lingo-current").removeClass("lingo-current"); //Remove current row
        }
        if(index + 1 < $(".lingo > tbody > tr").length) { //If not the last row
            index = index + 1;
            $('.lingo > tbody > tr').eq(index).addClass("lingo-current"); //Make current
            $('.lingo-current > td > div > div').prop("contenteditable", false).prop("tabindex", 0).attr("autocomplete", "off").prop("spellcheck", false).attr("autocorrect", "off").html(".");
            $('.lingo-current > td > div > div').each(function(i, selected){ //Set right letters
                 $(selected).html(Lingo.rightLetters[i]);
            });
            if(Lingo.mobile) {
                $("div[contenteditable=false]").prop("contenteditable", true);
                $('.lingo-current > td > div > div').eq(0).blur(); //Make sure keyboard pops up
            }
            $('.lingo-current > td > div > div').eq(0).trigger("click"); //Focus first letter

            Lingo.enterPressed = false; //Enable enter again
            Lingo.startTimer();
        } else {
            Lingo.nextWord(false); //Game over
        }
    },

    stopTimer: function () {
        $(".lingo-progress-bar").stop();
        $(".lingo-progress-bar").css("width", "0px");
    },

    startTimer: function () {
        Lingo.stopTimer();

        if(parseInt(Lingo.time) !== 0) {
            $(".lingo-progress-bar").animate({
                width: "100%"
            }, Lingo.time * 1000, "linear", function () {
                Lingo.enterPressed = true;
                setTimeout(function() {
                    Lingo.nextGuess();
                }, 100);
                var audio = new Audio("./audio/timeup.mp3");
                audio.play();
            });
        }
    },

    nextWord: function (won) {
        var audio;
        if (won) {
            $(".lingo-right-words").html(parseInt($(".lingo-right-words").html()) + 1);
            audio = new Audio("./audio/guesscorrect.mp3");
        } else {
            audio = new Audio("./audio/guessfail.mp3");
        }
        audio.play();
        $.post(API_URL + "api/right", function (data) {
            var json = JSON.parse(data);
            $(".lingo-right").html(json.word);
            $("#overlay").css({
                top: $(".lingo").position().top,
                left: "calc(50% - " + ($(".lingo").outerWidth() / 2) + "px)",
                height: $(".lingo").outerHeight() + $(".lingo-progress").outerHeight()
            });
            $("#overlay").outerWidth($(".lingo").outerWidth());
            $("#overlay").fadeIn();
        });
    },

    activateVoice: function () {
        if(annyang) {
            if (Lingo.language === "nl") {
                annyang.setLanguage("nl-NL");
            } else if (Lingo.language === "de") {
                annyang.setLanguage("de-DE");
            }
            // Add our commands to annyang
            annyang.addCommands({
                "help": function () {
                    console.log("HELP");
                }
            });
            annyang.debug(true);

            annyang.addCallback("result", function (e) {
                for (var i = 0; i < 5; i++) {
                    if (typeof e[i] !== "undefined") {
                        e[i] = e[i].replace("lange ij", "|");
                        var wordArray = e[i].split(" ").slice(parseInt(Lingo.letters) * -1); //Get the last x single letters
                        console.log(wordArray);
                        var success = true;
                        if (wordArray.length === parseInt(Lingo.letters)) {
                            for (var j = 0; j < parseInt(Lingo.letters); j++) {
                                if (wordArray[j].length !== 1) {
                                    success = false;
                                }
                            }
                        } else {
                            success = false;
                        }
                        if (success) {
                            //var actualWord = wordArray[(parseInt(Lingo.letters) * -1) - 1];
                            $('.lingo-current > td > div > div').each(function (k, selected) {
                                $(selected).html((wordArray[k].toUpperCase() === "|" ? "IJ" : wordArray[k].toUpperCase()));
                            });
                            Lingo.enterPressed = true;
                            Lingo.check();
                            break;
                        }
                    }
                }
                console.log(e);
            });
            // Start listening.
            annyang.start();
        } else {
            alert("Voice control not available. Only works on Google Chrome.");
        }
    },
};

function getDifference(a, b)
{
    var i = 0;
    var j = 0;
    var result = "";

    while (j < b.length)
    {
        if (a[i] !== b[j] || i === a.length)
            result += b[j];
        else
            i++;
        j++;
    }
    return result;
}
