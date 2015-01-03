<?php
if (!defined('VERSIONCT')) {
    die;
}
if(!$session->logged_in){ ?>

	<script type="text/javascript">window.location.href = "<?php echo $config['WEB_ROOT'].$config['home_page'];?>"</script><meta http-equiv="refresh" content="0; url=<?php echo $config['WEB_ROOT'].$config['home_page'];?>"/>

<?php die; }

else if (($config['ENABLE_CALLS'] == 1) && ($session->logged_in) && (isset($_GET['tech']))) {

        $tech = $database->getUserInfo($_GET['tech']);

        if ($tech['allowcalls'] == "Y") {

        if (!preg_match('/(Chrome|Firefox)/', $_SERVER['HTTP_USER_AGENT'])) {
                echo "<button class='btn btn-danger btn-xs btn-block'>".$lang['CALL_MESSAGE_7']."</button>";
            }


         ?>



        <div class="container">
        <div class="row">



<h1 class="pull-right dotter <?php if(($session->userlevel) > 7) { echo "hidden";}?>" id="conference-name"><?php echo $lang['VIDEO_CALL']." ".$_GET['tech'];?> </h1>





<script src="admin/js/firebase.js"></script>
<script src="admin/js/RTCMultiConnection.js"></script>




            <button id="setup-new-conference" class="setup btn btn-warning <?php if(($session->userlevel) > 7) {?>hidden<?php } ?>">Call <?php echo $_GET['tech'];?></button>


            <div id="rooms-list"></div>


            <div id="videos-container" class="col-md-12"></div>

            <?php if(($session->userlevel) < 8) {?><div class="cleaner"></div><a href="line.php?event=end" target="_top" class="ignore btn btn-danger btn-block"><span class="glyphicon glyphicon-phone-alt"></span></a><?php } ?>


        <script>
        var connection = new RTCMultiConnection();

        connection.session = {
            audio: true,
            video: true
        };
        connection.onstream = function(e) {
            e.mediaElement.width = 600;
            e.mediaElement.classList.add("vidoje");
            videosContainer.insertBefore(e.mediaElement, videosContainer.firstChild);
        };

        connection.onstreamended = function(e) {
            e.mediaElement.style.opacity = 0;
            setTimeout(function() {
                if (e.mediaElement.parentNode) {
                    e.mediaElement.parentNode.removeChild(e.mediaElement);
                    top.location.href='line.php?event=end';
                }
            }, 1000);
        };

        var sessions = {};

        connection.onNewSession = function(session) {
            if (sessions[session.sessionid]) return;
            sessions[session.sessionid] = session;
            parent.top.$('#framer').addClass('incall');
            var tr = document.createElement('div');
            tr.innerHTML = '<center><img src="admin/img/phone.gif" alt="ringing" class="ringier" /><div class="cleaner ringier"></div><button class="join btn btn-success animated flash"><span class="glyphicon glyphicon-earphone"></span></button><a id="shutit" href="line.php?event=end" target="_top" class="ignore btn btn-danger" ><span class="glyphicon glyphicon-phone-alt"></span></a>'+'<a class="join btn btn-primary" target="_blank"> ' + session.extra['session-name'] + '</a>'+'<a class="join btn btn-default" target="_blank" href="ticket.php?statuser=' + session.extra['session-name'] + '"><span class="glyphicon glyphicon-list-alt"></span></a></center><audio src="admin/sound/ring-1.mp3" class="hidden ringier" autoplay loop></audio>';
            roomsList.insertBefore(tr, roomsList.firstChild);
            var joinRoomButton = tr.querySelector('.join');
            joinRoomButton.setAttribute('data-sessionid', session.sessionid);
            joinRoomButton.onclick = function() {
                this.disabled = true;
                var sessionid = this.getAttribute('data-sessionid');
                session = sessions[sessionid];
                if (!session) throw 'No such session exists.';
                $( ".ringier" ).remove();
                connection.join(session);
            };
        };

        var videosContainer = document.getElementById('videos-container') || document.body;
        var roomsList = document.getElementById('rooms-list');
        document.getElementById('setup-new-conference').onclick = function() {
            this.disabled = true;
            connection.extra = {
                'session-name': document.getElementById('conference-name').value || '<?php echo $session->username;?>'
            };
            connection.open();
        };
        // setup signaling to search existing sessions
        connection.connect();
        (function() {
            var uniqueToken = document.getElementById('unique-token');
            if (uniqueToken)
                if (location.hash.length > 2) uniqueToken.parentNode.parentNode.parentNode.innerHTML = '<h2 style="text-align:center;"><a href="' + location.href + '" target="_blank">Share this link</a></h2>';
                else uniqueToken.innerHTML = uniqueToken.parentNode.parentNode.href = '#' + (Math.random() * new Date().getTime()).toString(36).toUpperCase().replace(/\./g, '-');
        })();

        </script>


        <?php if(($session->userlevel) < 8) { ?>
        <script>document.getElementById('setup-new-conference').click();</script>
        <?php } ?>

                </div>
        </div>

        <?php } else { ?><script type="text/javascript">window.location.href = "line.php?event=offline"</script><meta http-equiv="refresh" content="0; url=line.php?event=offline"/><?php } ?>

<?php } else { ?><script type="text/javascript">window.location.href = "line.php?event=off"</script><meta http-equiv="refresh" content="0; url=line.php?event=off"/><?php } ?>