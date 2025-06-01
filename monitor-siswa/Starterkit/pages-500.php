<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

    <head>
        
        <?php includeFileWithVariables('partials/title-meta.php', array('title' => '500 Error'));?>

        <?php include 'partials/head-css.php'; ?>

    </head>

    <body>

        <div class="">
            <div class="container">
                <div class="row justify-content-center align-items-center min-vh-100">
                    <div class="col-lg-5">
                        <div class="text-center">
                            <img src="assets/images/error-500.png" alt="404 error" class="img-fluid">
                            <div class="mt-5 pt-5 text-center">
                                <a class="btn btn-primary waves-effect waves-light" href="index.php">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'partials/vendor-scripts.php'; ?>

        <script src="assets/js/app.js"></script>

    </body>
</html>
