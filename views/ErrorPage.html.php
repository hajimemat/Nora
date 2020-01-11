<html>
    <head>
        <title>Nora System Error</title>
    </head>
    <body>
        <h1>System Error Page <small><?=$code?></small></h1>
        <p><?=$message?></p>
        <?php if ($exception): ?>
        <?=$exception?>
        </pre>
        <?php endif; ?>
        <footer>
            (c) Since 2006 Avap.ltd, All Rights Reserved
        </footer>
    </body>
</html>
