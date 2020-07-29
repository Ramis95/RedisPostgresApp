<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{

    public function showCommands ()
    {
        $commands = [];
        $result = DB::select('SELECT * FROM public.command');


        if($result)
        {
            foreach ($result as $key => $value) {
                $commands[$value->id]['id'] = $value->id;
                $commands[$value->id]['command'] = $value->command;
                $commands[$value->id]['method'] = $value->method;
            }
        }

        $data['commands'] = $commands;

        return view('welcome', $data);
    }

    public function createTables()
    {
        /* Create command table */
        DB::select('CREATE TABLE command (id SERIAL, command varchar(100), method varchar(100), PRIMARY KEY (id)) WITHOUT OIDS');

        /* Create text table */
        DB::select('CREATE TABLE text (id SERIAL, command_id int, key varchar(100), text varchar(255), PRIMARY KEY (id)) WITHOUT OIDS');
    }

    public function saveCommand(Request $request)
    {
        /* Save command in Postgres and return id new command*/

        $response = [];

        $command = "'".$request->post('command')."'";
        $method = "'".$request->post('method')."'";

        $insert_result = DB::insert("INSERT INTO command (command, method) VALUES ( $command, $method)");

        if($insert_result) {
            $result = DB::select("SELECT max(id) as last_id FROM command");

            $response['status'] = 'success';
            $response['code'] = 200;
            $response['id'] = $result[0]->last_id;
            $code = 200;
        }
        else
        {
            $response['status'] = 'error';
            $response['code'] = 500;
            $code = 500;
        }


        return response()->json($response, $code);

    }

    public function deleteCommand(Request $request)
    {
        /* Delete command in Postgres */

        $command = $request->post('command_id');
        DB::delete("DELETE FROM command WHERE id = $command");
        DB::delete("DELETE FROM text WHERE command_id = $command");
        return response()->json(['status' => 'success'], 200);
    }

    public function saveText(Request $request)
    {

        $response = [];

        $command_id = "'".$request->post('command_id')."'";
        $key = "'".$request->post('key')."'";
        $text = "'".$request->post('text')."'";

        $insert_result = DB::insert("INSERT INTO text (command_id, key, text) VALUES ( $command_id, $key, $text)");

        if($insert_result) {
            $result = DB::select("SELECT max(id) as last_id FROM text");

            $response['status'] = 'success';
            $response['code'] = 200;
            $response['id'] = $result[0]->last_id;
            $code = 200;
        }
        else
        {
            $response['status'] = 'error';
            $response['code'] = 500;
            $code = 500;
        }


        return response()->json($response, $code);

//        return response()->json([
//            'status' => 'success',
//            'id' => 66,
//            'key' => 'text1',
//            'text' => 'Hello'
//        ], 200);
    }

    public function getText(Request $request)
    {
        $text = [];
        $command_id = "'".$request->post('command_id')."'";
        $result = DB::select("SELECT * FROM public.text WHERE command_id = $command_id");

        if ($result)
        {
            foreach ($result as $key => $value)
            {
                $text[$key]['id'] = $value->id;
                $text[$key]['command_id'] = $value->command_id;
                $text[$key]['key'] = $value->key;
                $text[$key]['text'] = $value->text;
            }
        }


        return response()->json([
            'status' => 'success',
            'text' => $text
        ], 200);



    }

    public function deleteText(Request $request)
    {
        $text_id = $request->post('text_id');


        DB::delete("DELETE FROM text WHERE id = $text_id");
        return response()->json(['status' => 'success'], 200);
    }



}
