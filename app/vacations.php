<?php

if(!isset($_SESSION['login'])) {
    header('LOCATION:login.php');
}
?>
<!-- MODALS -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="vacationReqPopUp" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><br/>
            <p id="vacationRequestText" style="margin-left: 14px;">Submit Vacation Request</p>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="fname" class="col-form-label">Date From:</label><br />
            <input type="text" id="datefrom" class="dummy">
          </div>
          <div class="form-group">
            <label for="lname" class="col-form-label">Date To:</label><br />
            <input type="text" id="dateto" class="dummy">
          </div>
          <div class="form-group">
            <label for="description" class="col-form-label">Description:</label>
            <textarea class="form-control dummy" id="description" style="resize: none;"></textarea>
          </div>
        </form>
      </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="sendCreate([$('#datefrom').val(),$('#dateto').val(),$('#description').val()]);">Submit</button>
            </div>
            <br />
        </div>
    </div>
</div>
<!-- END MODALS -->
<!-- handlebars -->    
            <script type="text/x-handlebars-template" id="vacationsTableTemplate">
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
                <tr>
    	            {{#each this}}
                    <td class="dummy">{{this}}</td>
                  {{/each}}
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
                    data: {'controller':'VacationManager', 'from':from, 'to':to},
                    dataType: 'json', 
                    success: (response) => {
                        var v = $("#vacationsTableTemplate").html();
                        var vacationsTableTemplate = Handlebars.compile(v);
                        $("#vacationsTable").html(vacationsTableTemplate({ array: response }));
                        window.listQueryVacationsFrom = from;
                        window.listQueryVacationsTo = to;
                        if(!Array.isArray(response)) {$(".table").html(response);}
                        $('#listNumbers').html(from+' - '+to);
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
            
                });
            }
            
            function listQueryMore() {
                window.listQueryVacationsFrom = window.listQueryVacationsFrom+10;
                window.listQueryVacationsTo = window.listQueryVacationsTo+10;
                listQuery(window.listQueryVacationsFrom, window.listQueryVacationsTo);
            }
            
            function listQueryLess() {
                window.listQueryVacationsFrom = window.listQueryVacationsFrom-10;
                window.listQueryVacationsTo = window.listQueryVacationsTo-10;
                if(window.listQueryVacationsFrom >= 10) {
                    listQuery(window.listQueryVacationsFrom, window.listQueryVacationsTo);                    
                } else {
                    listQuery(0, 10);
                }
            }
            
        $(function() {
            $("#datefrom").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#dateto").datepicker({ dateFormat: 'yy-mm-dd' });
        });
            
        function vacationReqPopUp() {
            $('#vacationReqPopUp').modal('show');
        }
        
            function sendCreate(data) {
            if(Array.isArray(data)) {
                var description = data[2].replace(/\./g, '££££'); //tokenize .                 
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/createNewRequest',
                    type: 'POST',
                    data: {'controller':'VacationManager', 'date_from':data[0], 'date_to':data[1], 'description':description},
                    dataType: 'json', 
                    success: (response) => {
                    console.log(response);
                        if(typeof response.status == undefined) { 
                            alert('Create did not succeed...');
                        } else if(response.status == 'success') {
                              listQuery(window.listQueryVacationsFrom, window.listQueryVacationsTo);
                              $('.dummy').val('');
                        }
                    },
                    error: (xhr, desc, err) => {
                        console.log(desc, err);
                    }
                    
                });        
            }
            }
            
            function searchQuery() {
                $.ajax({
                    beforeSend: function(xhr){xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")},
                    url: window.location.origin + '/bootstrap_proj/api/client/searchQuery',
                    type: 'POST',
                    data: {'controller':'VacationManager'},
                    dataType: 'json', 
                    success: (response) => {
                        var v = $("#vacationsTableTemplate").html();
                        var vacationsTableTemplate = Handlebars.compile(v);
                        $("#vacationsTable").html(vacationsTableTemplate({ array: response }));
                        if(!Array.isArray(response)) {$(".table").html(response);}
                        $('#listNumbers').html('');
                        $("#less").hide();
                        $("#more").hide();
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
            <p>Vacation Management</p>
            
            <div id="content">
                <div id="vacationsTable"></div>
                <div id="listNumbers"></div>
            </div>
            </div>

            <!--The sidebar -->
            <div class="col-md-4">

                <div class="well">
                 <button class="btn btn-success" onclick="vacationReqPopUp();">Submit Vacation Request</button>
                </div>
                <div class="well">
                 <button class="btn btn-warning" onclick="searchQuery();">Show Pending Requests</button> 
                </div>
                <div class="well">
                 <button class="btn btn-warning" onclick="listQuery(0,10);">Show All Requests</button>                                                                              
                </div>


        </div>
        <!-- Footer -->

        
        </div>
        <footer>
            <!-- EMPTY -->
        </footer>

</body>

</html>

