<?php

use Unicaen\Framework\Application\Application;

header("HTTP/1.1 503 Service Unavailable");

$remoteAddr = $_SERVER['REMOTE_ADDR'];
$forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

$message = Application::getInstance()->config()['maintenance']['messageInfo'] ?? 'L\'application est actuellement indisponible';

?><!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Meta -->
    <meta charset="utf-8">

    <title>Maintenance - OSE</title>
</head>

<body>
<div id="navbar">
    <h1 class="title">OSE</h1>
    <p class="info">
        REMOTE_ADDR=<?= $remoteAddr; ?>, HTTP_X_FORWARDED_FOR=<?= $forwarded; ?>
    </p>
</div>

<div id="contenu">

    <h1>OSE
        <small>Organisation des Services d'Enseignement</small>
    </h1>
    <p class="lead">
        <?php if ($message instanceof Throwable): ?>
    <h2>Une erreur est survenue !</h2>
    <p><?= $message->getMessage() ?></p>
    <p style="color:darkred"><?= $message->getFile() ?> ligne <?= $message->getLine() ?></p>
    <?php else: ?>
        <?= $message ?>
    <?php endif; ?>
    </p>

</div>
<style>

    body {
        margin: 0px;
        padding: 0px;
    }

    #navbar {

        font-size: 14px;
        line-height: 1.42857;
        color: #333;
        background-image: linear-gradient(to bottom, #3C3C3C 0px, #222 100%);
        background-repeat: repeat-x;
        background-color: #222;
        border: 1px #080808 solid;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    #navbar h1 {
        float: left;
        color: #9D9D9D;
        margin-top: 5px;
        margin-left: 5px;
    }

    #navbar .info {
        color: #555;
        text-align: right;
        margin-right: 5px;
    }

    #contenu {
        margin: 2em;
        padding: 1em;
        background-color: #f2dede;
        border-radius: 6px;
        border: 1px #d5c0c0 solid;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

</style>
</body>
</html>
<?php die();