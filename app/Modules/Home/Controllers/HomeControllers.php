<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\TopMenu;
use App\Models\BottomMenu;

class HomeControllers extends Controller {

    use \TraitsFunc;

    public function index()
    {   
    	$data['topMenu'] = TopMenu::orderBy('sort','ASC')->get();
    	$data['bottomMenu'] = BottomMenu::orderBy('sort','ASC')->get();
        return view('Home.Views.index');
    }

    
}
