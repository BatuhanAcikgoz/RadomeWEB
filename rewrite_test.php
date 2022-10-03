<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  Rewrite test
 */
?>
<html lang="en">

<head>
    <title>Rewrite Test &bull; RadomeWEB</title>

    <link rel="stylesheet" href="core/assets/vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="core/assets/css/custom.css">

    <style>
        html {
            overflow-y: scroll;
        }

        body {
            background-color: #eceeef;
        }
    </style>
</head>

<body>
<div style="text-align: center">
    <br/><br/><br/>

    <h1>RadomeWEB v2</h1>

    <hr/>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <?php
            if (isset($_GET['route']) && $_GET['route'] == '/rewrite_test') {
                echo '<div class="alert alert-success">Rewrite enabled!</div>';

            } else {
                echo '<div class="alert alert-danger">Rewrite disabled!</div>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
