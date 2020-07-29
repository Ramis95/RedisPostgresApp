<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script
        src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>



<?
$arr = [

    [
        'id' => 1,
        'command' => '*100#',
        'method' => 'get_balance',
        'text1' => 'Hello',
        'text2' => 'World',
        'text3' => 'My name',
    ],
    [
        'id' => 2,
        'method' => 'get_cashback',
        'command' => '*103#',
        'text1' => 'My text',
    ],
    [
        'id' => 3,
        'method' => 'get_bonus',
        'command' => '*556#',
        'text1' => 'My text',
    ]

];



?>

<div style="text-align: center">
    <h2>Command storage
    </h2>

    <table class="command_table">
        <thead>
        <tr>
            <th>id</th>
            <th>команда</th>
            <th>метод</th>
            <th>текст</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?
        foreach ($commands as $key => $value) {?>
        <tr>

            <td><?=$value['id']?></td>
            <td><?=$value['command']?></td>
            <td><?=$value['method']?></td>
            <td>
                <button class="show_text" data-command_id="<?=$value['id']?>">Показать</button>
            </td>
            <td>
                <button class="delete dlt_command" data-command_id="<?=$value['id']?>">Удалить</button>
            </td>

        </tr>


        <?}?>


        <tr style="background: gainsboro;">
            <td colspan="5">
                <p style=" margin: 0; font-weight: bold">Добавить новую команду</p>
            </td>
        </tr>

        <tr class="new_command_tr">
            <td></td>
            <td>
                <input class="enter" name="new_command" placeholder="Команда">
            </td>
            <td>
                <input class="enter" name="new_command_method" placeholder="Метод">
            </td>
            <td>
                <button class="save_command">Сохранить</button>
            </td>
            <td>
{{--                <button class="cancel">Отмена</button>--}}
            </td>
        </tr>


        </tbody>
    </table>

{{--    <div style="text-align: center">--}}
{{--        <button class="add_command">--}}
{{--            Добавить команду--}}
{{--        </button>--}}
{{--    </div>--}}

</div>

<script>
    $(document).ready(function () {

        var add_command_interface = false;
        var text_table = false;

        $('.add_command').on('click', function () {
            /* Open interface */

            if (add_command_interface == false) {

                $('.command_table tbody tr:last').after('    <tr class="new_command_tr">\n' +
                    '        <td></td>\n' +
                    '        <td>\n' +
                    '        <input class="enter" name="new_command" placeholder="Команда">\n' +
                    '        </td>\n' +
                    '        <td>\n' +
                    '        <input class="enter" name="new_command_method" placeholder="Метод">\n' +
                    '        </td>\n' +
                    '        <td>\n' +
                    '        <button class="save_command">Сохранить</button>\n' +
                    '        </td>\n' +
                    '        <td>\n' +
                    '        <button class="cancel">Отмена</button>\n' +
                    '        </td>\n' +
                    '        </tr>');

                add_command_interface = true;
            }

        });



        $(document.body).on('click', '.save_command', function () {
            /* Save new command in Posgres */

            $('.new_tr').remove();
            $('.show_text').text('Показать');
            $('.show_text').removeClass('open');

            var command = $('[name=new_command]').val();
            var method = $('[name=new_command_method]').val();

            if(command == '' || method == '')
            {
                alert('Вы забыли что-то указать');
                return false;
            }


            $.ajax({

                url: '/api/save_new_command',
                type: "POST",
                data: {command: command, method: method},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {

                    if(data.status == 'success')
                    {
                        var new_command = ' <tr>\n' +
                            '\n' +
                            '            <td>'+ data.id +'</td>\n'+
                            '            <td>'+ command +'</td>\n'+
                            '            <td>'+ method +'</td>\n'+
                            '            <td>\n'+
                            '                <button class="show_text" data-command_id="'+ data.id +'">Показать</button>\n'+
                            '            </td>\n'+
                            '            <td>\n'+
                            '                <button class="delete dlt_command" data-command_id="'+ data.id +'">Удалить</button>\n'+
                            '            </td>\n'+
                            '\n'+
                            '        </tr>';

                        var command_table_length = $(".command_table tbody tr").length - 2;
                        $('.command_table tbody tr:nth-child(' + command_table_length+')').after(new_command);

                        $('[name=new_command]').val('');
                        $('[name=new_command_method]').val('');

                    }

                },

                error: function (msg) {
                    alert('Ошибка');
                }

            });


        });

        $(document.body).on('click', '.save_text', function () {
            /* Save new text in Posgres */

            var command_id = $(this).data('command_id');

            var key = $('.new_tr[data-command_id='+ command_id +'] [name=new_text_key]').val();
            var text = $('.new_tr[data-command_id='+ command_id +'] [name=new_text]').val();

            if(key == '' || text == '')
            {
                alert('Вы забыли что-то указать');
                return false;
            }

            $.ajax({

                url: '/api/save_text',
                type: "POST",
                data: {command_id: command_id, key: key, text: text},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {
                    if(data.status == 'success')
                    {

                        var new_command = ' <tr> \n' +
                            '<td> \n' +
                            key +
                            '</td>\n' +
                            '<td>\n' +
                            text +
                            '</td>\n' +
                            '<td>\n' +
                            '<button class="delete dlt_text" data-command_id="'+ command_id +'" data-text_id="' + data.id + '">Удалить текст</button> \n' +
                            '</td> \n' +
                            '</tr>';

                        var text_table_length = $(".new_tr[data-command_id="+ command_id +"] .text_table tbody tr").length - 1;
                        // var text_table_length = 1;

                        $('.new_tr[data-command_id='+ command_id +'] .text_table tbody tr:nth-child(' + text_table_length+')').after(new_command);

                        $('.new_tr[data-command_id='+ command_id +'] [name=new_text_key]').val('');
                        $('.new_tr[data-command_id='+ command_id +'] [name=new_text]').val('');

                    }

                },

                error: function (msg) {
                    alert('Ошибка');
                }

            });


        });

        $(document.body).on('click', '.dlt_command', function () {

            /* Delete command in Posgres */

            var command_id = $(this).data('command_id');
            var parent_td = $(this).parent('td');
            var parent_tr = $(parent_td).parent('tr');
            var agree = confirm('Вы действительно хотите удалить команду?');

           if(agree)
           {

               $.ajax({

                   url: '/api/delete_command',
                   type: "POST",
                   data: {command_id: command_id},
                   headers: {
                       'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                   },

                   success: function (data) {

                       if(data.status == 'success')
                       {
                            $(parent_tr).remove();
                       }

                   },

                   error: function (msg) {
                       alert('Ошибка');
                   }

               });

           }

        });

        $(document.body).on('click', '.dlt_text', function (){

            var command_id = $(this).data('command_id');
            var text_id = $(this).data('text_id');
            var parent_td = $(this).parent('td');
            var parent_tr = $(parent_td).parent('tr');
            var agree = confirm('Вы действительно хотите удалить текст?');

            if(agree) {

                $.ajax({

                    url: '/api/delete_text',
                    type: "POST",
                    data: {text_id: text_id},
                    async: false,
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (data) {

                        if (data.status == 'success') {
                            $(parent_tr).remove();
                        }
                    },
                });
            }

        });

        $(document.body).on('click', '.show_text', function () {
            /* Show text for this command */
            var command_id = $(this).data('command_id');
            var table_tr = '';

            if ($(this).hasClass('open')) {

                $(this).text('Показать');
                $(this).removeClass('open');

                $('.new_tr[data-command_id=' + command_id + ']').remove();

                return;
            }

            $(this).text('Скрыть');
            $(this).addClass('open');

            var parent_tr = $(this).parent("td");
            var parent_td = $(parent_tr).parent('tr');


            $.ajax({

                url: '/api/get_text',
                type: "POST",
                data: {command_id: command_id},
                async: false,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {


                    if(data.status == 'success' && Object.keys(data.text).length > 0)
                    {


                        for(i = 0; i<Object.keys(data.text).length; i++)
                        {
                            table_tr +=     '<tr>\n' +
                                            '  <td>\n' +
                                                data.text[i].key +
                                            '  </td>\n' +
                                            '  <td>\n' +
                                                data.text[i].text +
                                            '  </td>\n' +
                                            '  <td>\n' +
                                            '   <button class="delete dlt_text"  data-command_id = "' + command_id + '" data-text_id = "' + data.text[i].id + '">Удалить текст</button>\n' +
                                            '  </td>\n' +
                                            '</tr>\n';

                        }
                    }
                },

                error: function (msg) {
                    alert('Ошибка');
                }

            });

            var text_table = '<tr class="new_tr" data-command_id = ' + command_id + '>\n' +
                '            <td colspan="5">\n' +
                '                <table class="text_table">\n' +
                '                    <thead>\n' +
                '                    <tr>\n' +
                '                        <th>\n' +
                '                            Ключ \n' +
                '                        </th>\n' +
                '\n' +
                '                        <th>\n' +
                '                            Текст \n' +
                '                        </th>\n' +
                '\n' +
                '                        <th>\n' +
                '                             \n' +
                '                        </th>\n' +
                '\n' +
                '                    </tr>\n' +
                '                    </thead>\n' +
                '\n' +
                '                    <tbody>\n' +
                '<tr></tr>' +
                                table_tr +
                '<tr> \n'+
                '<td> \n'+
                '<input class="enter" name="new_text_key" placeholder="Ключ">\n'+
                '</td>\n'+
                '<td>\n'+
                '<input class="enter" name="new_text" placeholder="Текст">\n'+
                '</td>\n'+
                '<td>\n'+
                '<button class="save_text" data-command_id="'+ command_id +'">Сохранить</button>\n'+
                '</td>\n'+
                '</tr>\n'+
                '                    </tbody>\n' +
                '                </table>\n' +
                '            </td>\n' +
                '\n' +
                '        </tr>';


            $(parent_td).after(text_table);

        });


    });
</script>


<style>



    .command_table {
        min-width: 1110px;
        margin-bottom: 55px;
    }

    .enter {
        height: 27px;
        border-radius: 2px;
        border: 1px dotted grey;
        text-align: center;
        width: 150px;
    }

    table {
        margin: auto;
        border: 1px dotted #989898;
    }

    table thead tr th {
        padding: 10px 60px;
    }

    table thead {
        background: gainsboro;
        border-bottom: 1px dotted #989898;
    }

    table tbody tr {
        border-bottom: 1px dotted #989898;
    }

    table tbody tr td {
        border-bottom: 1px dotted #989898;
        padding: 10px 5px;
    }

    table tbody tr:last-child td {
        border: none !important;
    }

    button {
        cursor: pointer;
    }

    .show_text, .save_command, .save_text {
        border: none;
        background: #29bb21b5;
        width: 100px;
        padding: 3px 3px;
        font-weight: bold;
        font-size: 16px;
        color: white;
        font-family: 'Nunito', sans-serif;
        border-radius: 3px;
    }

    .show_text.open {
        background: #636B72;
    }

    .delete, .cancel {
        border: none;
        background: #bb2121b5;
        font-family: 'Nunito', sans-serif;
        padding: 3px 15px;
        font-weight: bold;
        font-size: 16px;
        color: white;
        border-radius: 3px;
    }

    .add_command {
        margin-top: 20px;
        font-weight: bold;
        color: #636b6f;
        background: gainsboro;
        font-size: 16px;
        border: 1px dotted gray;
        padding: 10px 19px;
        border-radius: 3px;
        border-collapse: separate;
        border-spacing: 2px;
        color: #636b6f;
        width: 1110px;
        margin-bottom: 40px;
        font-family: 'Nunito', sans-serif;
    }

    .text_table {
        width: 100%;
    }

    .add_text_line {
        margin: 15px 0 25px 0;
        width: 100%;
    }

    .text_table thead th {
        font-size: 13px !important;
        background: transparent !important;
        padding: 4px !important;
        padding-top: 5px !important;
    }

    .new_tr td {
        background: gainsboro;
    }

    .text_table thead {
        background: white;
    }

    .text_table tbody td {
        font-size: 15px !important;
        color: black;
    }

    .add_text_line {
        margin-top: 20px;
        font-weight: bold;
        color: #636b6f;
        background: white;
        font-size: 16px;
        border: 1px dotted gray;
        padding: 10px 19px;
        border-radius: 3px;
        border-collapse: separate;
        border-spacing: 2px;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
    }


</style>


{{--        <div class="flex-center position-ref full-height">--}}
{{--            @if (Route::has('login'))--}}
{{--                <div class="top-right links">--}}
{{--                    @auth--}}
{{--                        <a href="{{ url('/home') }}">Home</a>--}}
{{--                    @else--}}
{{--                        <a href="{{ route('login') }}">Login</a>--}}

{{--                        @if (Route::has('register'))--}}
{{--                            <a href="{{ route('register') }}">Register</a>--}}
{{--                        @endif--}}
{{--                    @endauth--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            <div class="content">--}}
{{--                <div class="title m-b-md">--}}
{{--                    Laravel--}}
{{--                </div>--}}

{{--                <div class="links">--}}
{{--                    <a href="https://laravel.com/docs">Docs</a>--}}
{{--                    <a href="https://laracasts.com">Laracasts</a>--}}
{{--                    <a href="https://laravel-news.com">News</a>--}}
{{--                    <a href="https://blog.laravel.com">Blog</a>--}}
{{--                    <a href="https://nova.laravel.com">Nova</a>--}}
{{--                    <a href="https://forge.laravel.com">Forge</a>--}}
{{--                    <a href="https://vapor.laravel.com">Vapor</a>--}}
{{--                    <a href="https://github.com/laravel/laravel">GitHub</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


</body>
</html>
