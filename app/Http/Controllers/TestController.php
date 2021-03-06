<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function show(Request $request, $id=null) {
        echo "<br>This is Test Controller.";
        $uri = $request->path();
        echo '<br>URI: '.$uri;
        
        $url = $request->url();
        echo '<br>';
        
        echo 'URL: '.$url;
        $method = $request->method();
        echo '<br>';
        
        echo 'Method: '.$method;
     }
     public function index(){
         echo "get on!";
     }
     public function create() {
        echo 'create';
     }
     public function store(Request $request) {
        echo 'store';
     }
     public function edit($id) {
        echo 'edit';
     }
     public function update(Request $request, $id) {
        echo 'update';
     }
     public function destroy($id) {
        echo 'destroy';
     }
}
