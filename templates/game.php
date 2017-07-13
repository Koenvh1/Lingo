<?php include "header.php"; ?>
<body>
<div class="menu-container">
    <div class="menu-header menu-header-small">
        <div class="row menu-row">
            <div class="col-4 text-left">
                <a href="start" class="btn btn-secondary">Menu</a>
            </div>
            <div class="col-4 text-center">
                <img src="img/logo.svg" alt="Logo" style="height: 40px; width: auto">
            </div>
            <div class="col-4 text-right">
                <label class="text-primary text-lg-right">Woorden <span class="lingo-right-words">0</span></label>
            </div>
        </div>
    </div>
    <div class="text-center">
        <table class="lingo" style="margin: auto">
            <tbody>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            </tbody>
        </table>
        <div class="lingo-progress" style="margin: 10px auto">
            <div class="lingo-progress-bar"></div>
        </div>
    </div>
</div>

<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.color-animation.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/annyang.js"></script>
<script src="js/lingo.js"></script>
<script>
    $(document).ready(function () {
        Lingo.language = "<?php echo $_GET["language"]; ?>";
        Lingo.time = <?php echo $_GET["time"]; ?>;
        Lingo.letters = <?php echo $_GET["letters"]; ?>;
        $.post("play/init", {amount: <?php echo $_GET["aidLetters"]; ?>, first: <?php echo $_GET["first"]; ?>, language: Lingo.language, letters: Lingo.letters}, function (data) {
            Lingo.rightLetters = JSON.parse(data);
            Lingo.init();
        });
    });
</script>
</body>
</html>