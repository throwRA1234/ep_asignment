<?php

if(!isset($_SESSION['login'])) {
    header('LOCATION:login.php');
}

if(!isset($_SESSION['isAdmin'])) {
    exit('This user has no permissions to administer details of other users...');
}
?>
<!-- MODALS -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="updateUserPopUp" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><br/>
            <p id="updateDataText" style="margin-left: 14px;">Update user <span id="usernametitle"></span></p>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="fname" class="col-form-label">First Name:</label>
            <input type="text" class="form-control" id="fname">
          </div>
          <div class="form-group">
            <label for="lname" class="col-form-label">Last Name:</label>
            <input class="form-control" id="lname">
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input class="form-control" id="email">
          </div>
          <div class="form-group">
            <label for="usertype" class="col-form-label">Supervisor:</label>
            <select id="usertype">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
          </div>
        </form>
      </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="sendUpdate([$('#fname').val(), $('#lname').val(),$('#email').val(),$('#usertype').val(),$('#usernametitle').html()]);">Update</button>
            </div>
            <br />
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="createUserPopUp" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><br/>
            <p id="createDataText" style="margin-left: 14px;">Create New User</p>
      <div class="modal-body">
        <form oninput="passresult.value=!!password_sc.value&&(password_c.value==password_sc.value)?'':'Password do not match...'">
          <div class="form-group">
            <label for="fname_c" class="col-form-label">First Name:</label>
            <input type="text" class="form-control dummy2" id="fname_c">
          </div>
          <div class="form-group">
            <label for="lname_c" class="col-form-label">Last Name:</label>
            <input class="form-control dummy2" id="lname_c">
          </div>
          <div class="form-group">
            <label for="email_c" class="col-form-label">Email:</label>
            <input class="form-control dummy2" id="email_c">
          </div>
          <div class="form-group">
            <label for="username_c" class="col-form-label">Username (leave blank for default):</label>
            <input class="form-control dummy2" id="username_c">
          </div>
          <div class="form-group">
            <label for="password_c" class="col-form-label">Password:</label>
            <input class="form-control dummy2" id="password_c" name="password_c">
          </div>
          <div class="form-group">
            <label for="password_sc" class="col-form-label">Confirm Password:</label>
            <input class="form-control dummy2" id="password_sc" name="password_sc">
            <output id="passresult" name="passresult"></output>
          </div>
          <div class="form-group">
            <label for="usertype_c" class="col-form-label">Supervisor:</label>
            <select id="usertype_c">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
          </div>
        </form>
      </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="if($('#passresult').val()==''){$('#createUserPopUp').modal('hide'); sendCreate([$('#fname_c').val(), $('#lname_c').val(),$('#email_c').val(),$('#username_c').val(),$('#password_c').val(), $('#usertype_c').val()]);}">Create</button>
            </div>
            <br />
        </div>
    </div>
</div>
<!-- END MODALS -->
        <div class="row">

            <!-- Main content -->
            <div class="col-md-8">
            <p>User Management</p>
            
            <div id="content">
                <div id="usersTable"></div>
                <br />
                <div id="listNumbers"></div>
            <!-- handlebars -->    
            <script type="text/x-handlebars-template" id="usersTableTemplate">
            <table class="table table-hover">
            <thead>
              <tr>
                {{#each array.[0]}}
                  <th>{{@key}}</th>
                {{/each}}
              </tr>
            </thead>
            <tbody>
              {{#each array}}
                <tr id="{{Username}}">
    	            {{#each this}}
                    <td class="dummy">{{this}}</td>
                  {{/each}}
                      <td><button class="btn btn-primary" onclick="updateUser('{{Username}}');">Edit</button></td>
                      <td><button class="btn btn-primary" onclick="deleteUser('{{Username}}');">Delete</button></td>
                </tr>
              {{/each}}
            </tbody>
            </table>
            <button type="button" id="less" onclick="listQueryLess();" class="btn btn-primary"><</button><button type="button" id="more" class="btn btn-primary" onclick="listQueryMore();" style="float: right;">></button>
       
            </script>
            <!-- end handlebars -->
            <script type="application/javascript">
            $(window).on('load', function() {
                listQuery(0, 10);
            });
            
            function listQuery(from, to) {
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/listQuery',
                    type: 'POST',
                    data: {'controller':'UserManager', 'from':from, 'to':to},
                    dataType: 'json', 
                    success: (response) => {
                        var u = $("#usersTableTemplate").html();
                        var usersTableTemplate = Handlebars.compile(u);
                        $("#usersTable").html(usersTableTemplate({ array: response }));
                        window.listQueryUsersFrom = from;
                        window.listQueryUsersTo = to;
                        if(!Array.isArray(response)) {$(".table").html(response);}
                        $('#listNumbers').html(from+' - '+to);
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
            }
            
            function listQueryMore() {
                window.listQueryUsersFrom = window.listQueryUsersFrom+10;
                window.listQueryUsersTo = window.listQueryUsersTo+10;
                listQuery(window.listQueryUsersFrom, window.listQueryUsersTo);
            }
            
            function listQueryLess() {
                window.listQueryUsersFrom = window.listQueryUsersFrom-10;
                window.listQueryUsersTo = window.listQueryUsersTo-10;
                if(window.listQueryUsersFrom >= 10) {
                    listQuery(window.listQueryUsersFrom, window.listQueryUsersTo);                    
                } else {
                    listQuery(0, 10);
                }
            }
            
            function searchQuery(username) {
            if(username.length == 0) {listQuery(window.listQueryUsersFrom, window.listQueryUsersTo); return undefuned;}
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/searchQuery',
                    type: 'POST',
                    data: {'controller':'UserManager', 'uname':username},
                    dataType: 'json', 
                    success: (response) => {
                        $('#listNumbers').empty();
                        if(Array.isArray(response)) {
                            var u = $("#usersTableTemplate").html();
                            var usersTableTemplate = Handlebars.compile(u);
                            $("#usersTable").html(usersTableTemplate({ array: response }));
                            $("#less").hide();
                            $("#more").hide();
                        } else {
                            $("#usersTable").html('Search succeeded, but found nothing...');
                        }
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
            }
            
            function deleteUser(username) {
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/deleteUser',
                    type: 'POST',
                    data: {'controller':'UserManager', 'uname':username},
                    dataType: 'json', 
                    success: (response) => {
                        if(typeof response.status == 'undefined') { 
                            alert('Delete did not succeed...');
                        } else if(response.status == 'success') {
                            $('#usersTable tr[id="'+username+'"]').remove();
                        }
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
            
            }
            
            function updateUser(username) {
                updateUserModalHelper(username);
                $('#updateUserPopUp').modal('show');
            }
            
            function updateUserModalHelper(username) {
            $('#updateUserPopUp').on('show.bs.modal', function(e) {
                $(e.currentTarget).find('span[id="usernametitle"]').html(username);
                var data = prepopulate(username);
                $(e.currentTarget).find('input[id="fname"]').val(data[0]);
                $(e.currentTarget).find('input[id="lname"]').val(data[1]);
                $(e.currentTarget).find('input[id="email"]').val(data[3]);
                $(e.currentTarget).find('select[id="usertype"]').val(data[4]);
            });
            }
            
            function prepopulate(username){
            var arr = [];
                $('#usersTable tr[id='+'"'+username+'"'+']').find('.dummy').each((i, td) => {arr = arr.concat([td.innerHTML]);});
            return arr;
            }
            
            function sendUpdate(data) {
            if(Array.isArray(data)) {
                var email = data[2].replace(/\./g, '££££'); //tokenize . 
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/updateUser',
                    type: 'POST',
                    data: {'controller':'UserManager', 'fname':data[0], 'lname':data[1], 'email_address':email, 'is_supervisor':data[3] ,'uname':data[4]},
                    dataType: 'json', 
                    success: (response) => {
                        if(typeof response.status == 'undefined') { 
                            alert('Update did not succeed...');
                        } else if(response.status == 'success') {
                            listQuery(window.listQueryUsersFrom, window.listQueryUsersTo);
                        }
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
                    
                });
                }
            }
            
            function createUser() {
                $('#createUserPopUp').modal('show');
            }
            
            function sendCreate(data) {
            if(Array.isArray(data)) {
                var email = data[2].replace(/\./g, '££££'); //tokenize .                 
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/createUser',
                    type: 'POST',
                    data: {'controller':'UserManager', 'fname':data[0], 'lname':data[1], 'email_address':email, 'uname':data[3], 'password':data[4] ,'is_supervisor':data[5]},
                    dataType: 'json', 
                    success: (response) => {
                        if(typeof response.status == 'undefined') { 
                            alert('Create did not succeed...');
                        } else if(response.status == 'success') {
                            listQuery(window.listQueryUsersFrom, window.listQueryUsersTo);
                            $('.dummy2').val('');
                        }
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
                    
                });        
            }
            }
        </script>
            </div>
            </div>

            <!--The sidebar -->
            <div class="col-md-4">

                <div class="well">
                 <button class="btn btn-success" onclick="createUser();">Create User</button>
                </div>
                <div class="well">
                    <h4>User Search</h4>
                    <div class="input-group">
                        <input type="text" class="form-control" name="userfinder" id="userfinder" placeholder="Username here...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="searchQuery($('#userfinder').val());">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                </div>



        </div>
        <!-- Footer -->

        
        </div>
        <footer>
            <!-- EMPTY -->
        </footer>

</body>

</html>
