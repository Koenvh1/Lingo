function submitForm() {
    Lingo.language = $("#lang").html().trim();//$("input[name=language]:checked").val();
    Lingo.time = $("input[name=time]:checked").val();
    Lingo.letters = $("input[name=letters]:checked").val();
    if($("input[name=voice]").is(":checked")) {
        Lingo.activateVoice();
    }
    $.post(API_URL + "api/init", {amount: $("input[name=aidLetters]:checked").val(), first: $("input[name=first]").is(":checked"), language: Lingo.language, letters: Lingo.letters}, function (data) {
        Lingo.rightLetters = JSON.parse(data);
        $("#menu").hide();
        $("#overlay").hide();
        $("#game").show();
        Lingo.init();
    });
}

$("input[name=language]").click(function () {
    var lang = $(this).val();
    document.l10n.requestLanguages([lang]);
});

$("input").change(function () {
    localStorage.setItem("language", $("input[name=language]:checked").val());
    localStorage.setItem("letters", $("input[name=letters]:checked").val());
    localStorage.setItem("aidLetters", $("input[name=aidLetters]:checked").val());
    localStorage.setItem("first", $("input[name=first]").is(":checked").toString());
    localStorage.setItem("time", $("input[name=time]:checked").val());
    localStorage.setItem("voice", $("input[name=voice]").is(":checked").toString());
});

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
    if(localStorage.getItem("voice") !== null) $("input[name=voice]").prop("checked", localStorage.getItem("voice") === "true");

    $(".splashscreen").fadeOut();
});