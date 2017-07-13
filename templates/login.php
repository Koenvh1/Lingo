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

            </div>
        </div>
    </div>
    <div>
        <form class="form-signin">
            <label class="menu-title">Please sign in</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" style="margin: 5px" required autofocus>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" style="margin: 5px" required>
            <button class="btn btn-primary btn-block" style="margin: 5px" type="submit">Sign in</button>
        </form>
    </div>
</div>

<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.color-animation.js"></script>
<script src="js/tether.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/annyang.js"></script>
<script src="js/lingo.js"></script>
</body>
</html>