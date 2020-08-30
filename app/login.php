<html>
<head>
    <title>Login to Vacation Manager</title>
</head>

<div class="row">
    <div class="col-md-8">
    <h2 style="margin-left: 14px; margin-top: 7px;">Login</h2><br/>
    <div class="loginFormContent" style="margin-left: 14px; margin-top: 7px;">
        <form id="loginForm" name="loginForm">
            <input type="text" name="username" id="username" placeholder="username..." autocomplete=""/><br/><br/>
            <input type="password" name="password" id="password" placeholder="password..." autocomplete=""/><br/><br/>
            <div id="message"></div>
        <br />
        <br />
        <button type="button" class="btn btn-primary" onclick="tryLogin();">Login</button>
        </form>
        <br />
    </div>

        <script type="application/javascript">
            function tryLogin() {
                $('#message').empty();
                var username = $('#username').val();
                var password = $('#password').val();
                if(username.length > 0 && password.length > 0) {
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/login',
                    type: 'POST',
                    data: {'controller':'User', 'username':username, 'password':password},
                    dataType: 'json', 
                    success: (response) => {
                        console.log(response);
                        $('#message').html(response);
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
                } else {
                    $('#message').html('Missing username or password...');
                }
            }

        </script>


    </div>
    <!-- Sidebar-->
        <div class="col-md-4" >

<!-- nothing here -->
        </div>
</div>
<footer>
    <div class="row" style="margin-left: 14px; margin-top: 7px;">

           <!-- or here -->
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</footer>
