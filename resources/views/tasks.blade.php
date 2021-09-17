<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MLP To-Do</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <header>
        <img src="{{ asset('images/logo.png') }}">
    </header>
    <main>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <form>
                        <input id="task-title" class="form-control me-2" type="text" placeholder="Insert task name">
                    </form>
                </div>
                <div class="form-group">
                    <a id="task-submit" class="btn btn-primary btn-block">Add</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="task-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Read and refresh the table
            function read_refresh() {
                $('#task-table-body').empty();

                jQuery.ajax({
                    url: "{{ url('/') }}",
                    method: 'get',
                    success: function (response) {
                        let counter = 0;
                        if (response.errors) {
                            console.log(response.errors);
                        } else {
                            response.forEach(function (task) {
                                counter++;
                                let html = '';
                                if(task.completed == true){
                                    html = `
                                    <tr>
                                        <th>` + counter + `</th>
                                        <td class="completed">` + task.title + `</td>
                                        <td class="text-right button-tray">
                                           <a class='task-delete btn btn-danger float-right' data-id='` + task.id + `'><span class="glyphicon glyphicon-remove"></span></a>
                                        </td>
                                    </tr>
                                    `;
                                }else {
                                    html = `
                                    <tr>
                                        <th>` + counter + `</th>
                                        <td>` + task.title + `</td>
                                        <td class="text-right button-tray">
                                            <a class='task-complete btn btn-success' data-id='` + task.id + `'><span class="glyphicon glyphicon-ok"></span></a>
                                            <a class='task-delete btn btn-danger float-right' data-id='` + task.id + `'><span class="glyphicon glyphicon-remove"></span></a>
                                        </td>
                                    </tr>
                                    `;
                                }

                                $('#task-table-body').append(html);
                            });
                        }
                    }
                });
            }

            // Store task
            jQuery('#task-submit').click(function(e){
                e.preventDefault();

                jQuery.ajax({
                    url: "{{ url('/') }}",
                    method: 'post',
                    data: {
                        title: jQuery('#task-title').val()
                    },
                    success: function(response){
                        if(response.errors){
                            console.log(response.errors);
                        } else {
                            $('#task-title').val('');
                            read_refresh();
                        }
                    }
                });
            });

            // Complete Task
            $(document).on('click', '.task-complete', function(e) {
                e.preventDefault();

                let url = "{{ url('/:id') }}";
                url = url.replace(':id', $(this).attr('data-id'));

                jQuery.ajax({
                    url: url,
                    method: 'PATCH',
                    data: [],
                    success: function(response){
                        if(response.errors){
                            console.log(response.errors);
                        } else {
                            read_refresh();
                        }
                    }
                });
            });

            // Delete Task
            $(document).on('click', '.task-delete', function(e) {
                e.preventDefault();

                let id = $(this).attr('data-id');
                let url = "{{ route('destroy', ':id') }}";
                url = url.replace(':id', $(this).attr('data-id'));

                jQuery.ajax({
                    url: url,
                    method: 'DELETE',
                    data: [],
                    success: function (response) {
                        if (response.errors) {
                            console.log(response.errors);
                        } else {
                            read_refresh();
                        }
                    }
                });
            });

            read_refresh();
        });
    </script>
    <footer>
        <p class="text-center">Copyright © 2020 All Rights Reserved.</p>
    </footer>
</div>
</body>
</html>
