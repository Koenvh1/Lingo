<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{ BASE_URL }}">
    <meta charset="UTF-8">
    <title>Lingo</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- link rel="stylesheet" href="css/bootstrap.bootswatch.css" -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/lingo.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12" style="margin-top: 50px;" id="menu">
    <div class="frame">
        <h1>Lingo</h1>
        <form class="form" action="javascript:submitForm();">
            <div class="form-group">
                <h5>Language:</h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="nl" checked> Dutch
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="en" disabled> English
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="language" value="de"> German
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5>Letters:</h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="letters" value="5"> 5 letters
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="letters" value="6" checked> 6 letters
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5>Aid letters:</h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="aidLetters" value="1"> 1 aid letter
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="aidLetters" value="2" checked> 2 aid letters
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="first" checked> First letter always given
                    </label>
                </div>
            </div>
            <div class="form-group">
                <h5>Time:</h5>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="5"> 5 sec
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="15" checked> 15 sec
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="30"> 30 sec
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="60"> 1 min
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="time" value="0"> unlimited
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg">Start</button>
            </div>
        </form>
    </div>
</div>
<div id="game" style="margin: auto; display: none">
    <table class="lingo" style="margin: auto">
        <tbody>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
        </tbody>
    </table>
    <div class="lingo-progress" style="margin: auto">
        <div class="lingo-progress-bar"></div>
    </div>
</div>
<div id="overlay">
    <span>
        <span class="lingo-end-message">The right word was:</span><br>
        <span class="lingo-right"></span><br>
        <button class="btn btn-primary btn-lg" onclick="showMenu();">Menu</button> <button class="btn btn-primary btn-lg" onclick="submitForm();">Next</button>
    </span>
</div>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.color-animation.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/annyang.js"></script>
<script src="js/lingo.js"></script>
<script>
    function submitForm() {
        Lingo.language = $("input[name=language]:checked").val();
        Lingo.time = $("input[name=time]:checked").val();
        Lingo.letters = $("input[name=letters]:checked").val();
        $.post("play/init", {amount: $("input[name=aidLetters]:checked").val(), first: $("input[name=first]").is(":checked"), language: Lingo.language, letters: Lingo.letters}, function (data) {
            Lingo.rightLetters = JSON.parse(data);
            $("#menu").hide();
            $("#overlay").hide();
            $("#game").show();
            Lingo.init();
        });
    }

    function showMenu() {
        $("#menu").show();
        $("#overlay").hide();
        $("#game").hide();
    }
</script>
</body>
</html>