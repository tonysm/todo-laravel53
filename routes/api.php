<?php

Route::resource('tasks', 'TasksController', ['only' => ['store', 'index', 'update', 'destroy']]);
