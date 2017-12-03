function submitForm() {
    Lingo.language = $("input[name=language]:checked").val();//$("input[name=language]:checked").val();
    Lingo.time = $("input[name=time]:checked").val();
    Lingo.letters = $("input[name=letters]:checked").val();
    Lingo.tries = $("input[name=tries]:checked").val();
    if($("input[name=voice]").is(":checked")) {
        Lingo.activateVoice();
    }
    $.post(API_URL + "api/init", {amount: $("input[name=aidLetters]:checked").val(), first: $("input[name=first]").is(":checked"), language: Lingo.language, letters: Lingo.letters}, function (data) {
        Lingo.rightLetters = JSON.parse(data);
        Lingo.startLetters = JSON.parse(data);
        $("#menu").hide();
        $("#overlay").hide();
        $("#game").show();
        Lingo.init();
    });
}

function updateLanguage() {
    var lang = $("input[name=language]:checked").val();
    document.l10n.requestLanguages([lang]);
    if(lang === "en" || lang === "de") {
        $("input[name=letters][value='7']").prop("disabled", true);
        $("input[name=letters][value='8']").prop("disabled", true);
        $("input[name=letters][value='10']").prop("disabled", true);
        if(parseInt($("input[name=letters]:checked").val()) >= 7) {
            $("input[name=letters][value='6']").prop("checked", true);
        }
    } else {
        $("input[name=letters][value='7']").prop("disabled", false);
        $("input[name=letters][value='8']").prop("disabled", false);
        $("input[name=letters][value='10']").prop("disabled", false);
    }
}

$("input[name=language]").click(function () {
    updateLanguage();
});

$("input").change(function () {
    localStorage.setItem("language", $("input[name=language]:checked").val());
    localStorage.setItem("letters", $("input[name=letters]:checked").val());
    localStorage.setItem("aidLetters", $("input[name=aidLetters]:checked").val());
    localStorage.setItem("first", $("input[name=first]").is(":checked").toString());
    localStorage.setItem("time", $("input[name=time]:checked").val());
    localStorage.setItem("tries", $("input[name=tries]:checked").val());
    localStorage.setItem("voice", $("input[name=voice]").is(":checked").toString());
});

/*
$("input[name=aidLettersCustom]").change(function () {
    $("input[name=aidLetters]:last").prop("checked", true).val($("input[name=aidLettersCustom]").val());
});
*/

function showMenu() {
    $("#menu").show();
    $("#overlay").hide();
    $("#game").hide();
}

$(document).ready(function () {
    if(localStorage.getItem("language") !== null) {
        document.l10n.requestLanguages([localStorage.getItem("language")]);
        $("input[name=language][value=" + localStorage.getItem("language") + "]").prop("checked", true);
    }
    if(localStorage.getItem("letters") !== null) $("input[name=letters][value=" + localStorage.getItem("letters") + "]").prop("checked", true);
    if(localStorage.getItem("aidLetters") !== null) $("input[name=aidLetters][value=" + localStorage.getItem("aidLetters") + "]").prop("checked", true);
    if(localStorage.getItem("first") !== null) $("input[name=first]").prop("checked", localStorage.getItem("first") === "true");
    if(localStorage.getItem("time") !== null) $("input[name=time][value=" + localStorage.getItem("time") + "]").prop("checked", true);
    if(localStorage.getItem("tries") !== null) $("input[name=tries][value=" + localStorage.getItem("tries") + "]").prop("checked", true);
    if(localStorage.getItem("voice") !== null) $("input[name=voice]").prop("checked", localStorage.getItem("voice") === "true");

    updateLanguage();

    $(".splashscreen").fadeOut();
});