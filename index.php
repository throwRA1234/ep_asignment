<?php

if(!isset($_SESSION['login'])) {
    header('LOCATION:app/login.php');
}
//using spl_autoloader or using composer is better than this, but this is quicker to set up
if(!file_exists('.htaccess')) {
$content = <<<EOF
php_value auto_prepend_file "/var/www/html/bootstrap_proj/init/init.php"

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
EOF;

    file_put_contents('.htaccess', $content);
}
?>
<?php ob_start(); ?>
        <script type="application/javascript">
            function tryLogout() {
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/logout',
                    type: 'POST',
                    data: {'controller':'User'},
                    dataType: 'json', 
                    success: (response) => {
                        console.log(response);
                        window.location.replace("/bootstrap_proj/app/login.php");
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
            }

        </script>
        <div class="row">

            <!-- Main content -->
            <div class="col-md-8">
            <p>Welcome to Vacation Manager...</p>
            <br>
            <p>Click <a onclick="tryLogout();" style="cursor: default;">here</a> to logout</p>
            </div>


            <!--The sidebar -->
            <div class="col-md-4">

        </div>
        <!-- Footer -->
        </div>
                <footer>
            <!-- EMPTY -->
        </footer>

</body>

</html>
