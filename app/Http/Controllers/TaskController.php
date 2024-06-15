<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

/*
| GET|HEAD  | task                            | task.index          | App\Http\Controllers\TaskController@index   | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| POST      | task                            | task.store          | App\Http\Controllers\TaskController@store   | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/create                     | task.create         | App\Http\Controllers\TaskController@create  | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/{task}                     | task.show           | App\Http\Controllers\TaskController@show    | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| PUT|PATCH | task/{task}                     | task.update         | App\Http\Controllers\TaskController@update  | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| DELETE    | task/{task}                     | task.destroy        | App\Http\Controllers\TaskController@destroy | web                                                |
|           |                                 |                     |                                             | App\Http\Middleware\Authenticate                   |
| GET|HEAD  | task/{task}/edit                | task.edit           | App\Http\Controllers\TaskController@edit    | web
*/

class TaskController extends Controller
{
    /**Список заявок*/
    public function index()
    {
        // заголовки таблицы
        $table_headers = ['ID', 'Тема', 'Постановщик', 'Создана', 'Исполнитель', 'Статус', 'Посл.активность'];
        // массив заявок
        $tasks = Task::all();
        for ($i = 0; $i < count($tasks); ++$i) {
            $tasks[$i]->author->name = mb_substr($tasks[$i]->author->name, 0, 1).'.';
            $tasks[$i]->author->patronym = mb_substr($tasks[$i]->author->patronym, 0, 1).'.';
            if ($tasks[$i]->executor) {
                $tasks[$i]->executor->name = mb_substr($tasks[$i]->executor->name, 0, 1).'.';
                $tasks[$i]->executor->patronym = mb_substr($tasks[$i]->executor->patronym, 0, 1).'.';
            }
        }

        return view('task.index', ['tasks' => $tasks, 'table_headers' => $table_headers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /* Страница задачи */
    public function show($id)
    {
        $task = Task::find($id);
        $comments = Comment::where('task_id', $id)->orderBy('created_at', 'desc')->get();

        return view('task.show', ['task' => $task, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
