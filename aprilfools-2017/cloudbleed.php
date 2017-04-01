<!DOCTYPE html>
<html class="no-js" lang="en-US">
<?php
$host = $_SERVER['HTTP_HOST'];
$ray = bin2hex(random_bytes(8));
$time = gmstrftime('%Y-%m-%d %H:%M:%S');
$remote = $_SERVER['REMOTE_ADDR'];
?>
<head>
    <title><?= $host ?> | 522: Connection timed out</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="/aprilfools-2017/cdn-cgi/errors.css" type="text/css" media="screen,projection">
</head>

<body>
    <div id="cb-wrapper">
        <div id="cb-error-details" class="cb-error-details-wrapper">
            <div class="cb-wrapper cb-error-overview">
                <h1>
              <span class="cb-error-type">Error</span>
              <span class="cb-error-code">522</span>
	      <small class="heading-ray-id">Ray ID: <?= $ray ?> &bull; <?= $time ?> UTC</small>
            </h1>
                <h2 class="cb-subheadline">Connection timed out</h2>
            </div>

            <div class="cb-section cb-highlight cb-status-display">
                <div class="cb-wrapper">
                    <div class="cb-columns cols-3">

                        <div id="cb-browser-status" class="cb-column cb-status-item cb-browser-status ">
                            <div class="cb-icon-error-container">
                                <i class="cb-icon cb-icon-browser"></i>
                                <i class="cb-icon-status cb-icon-ok"></i>
                            </div>
                            <span class="cb-status-desc">You</span>
                            <h3 class="cb-status-name">Browser</h3>
                            <span class="cb-status-label">Working</span>
                        </div>

                        <div id="cb-cloudflare-status" class="cb-column cb-status-item cb-cloudflare-status ">
                            <div class="cb-icon-error-container">
                                <i class="cb-icon cb-icon-cloud"></i>
                                <i class="cb-icon-status cb-icon-ok"></i>
                            </div>
                            <span class="cb-status-desc">Bielefeld</span>
                            <h3 class="cb-status-name">Cloudbleed</h3>
                            <span class="cb-status-label">SNAFU</span>
                        </div>

                        <div id="cb-host-status" class="cb-column cb-status-item cb-host-status cb-error-source">
                            <a href="/index.php"><div class="cb-icon-error-container">
                                <i class="cb-icon cb-icon-server"></i>
                                <i class="cb-icon-status cb-icon-error"></i>
                            </div></a>
                            <span class="cb-status-desc"><?= $host ?></span>
                            <h3 class="cb-status-name">Host</h3>
                            <span class="cb-status-label">Error</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="cb-section cb-wrapper">
                <div class="cb-columns two">
                    <div class="cb-column">
                        <h2>What happened?</h2>
                        <p>The initial connection between Cloudbleed's network and the origin web server timed out. As a result, the web page can not be displayed.</p>
                    </div>

                    <div class="cb-column">
                        <h2>What can I do?</h2>
                        <h5>If you're a visitor of this website:</h5>
                        <p>Please try again in a few minutes.</p>

                        <h5>If you're the owner of this website:</h5>
                        <p>Contact your hosting provider letting them know your web server is not completing requests. An Error 522 means that the request was able to connect to your web server, but that the request didn't finish. The most likely cause is that something on your server is hogging resources.</p>
                    </div>
                </div>
            </div>

            <div class="cb-error-footer cb-wrapper">
                <p>
                    <span class="cb-footer-item">Cloudbleed Ray ID: <strong><?= $ray ?></strong></span>
                    <span class="cb-footer-separator">&bull;</span>
                    <span class="cb-footer-item">Your IP: <?= $remote ?></span>
                    <span class="cb-footer-separator">&bull;</span>
                    <span class="cb-footer-item">Performance &amp; security by <a href="https://www.heise.de/security/meldung/Cloudbleed-Geheime-Inhalte-von-Millionen-Webseiten-durch-Cloudflare-oeffentlich-3634075.html">Cloudbleed</a></span>

                </p>
            </div>

        </div>
    </div>
</body>

</html>
