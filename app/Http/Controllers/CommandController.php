<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommandController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    function route() {
        $exitCode = \Artisan::call('route:cache');
        return 'Routes cache cleared';
    }

    //Clear config cache:
    function config() {
        $exitCode = \Artisan::call('config:cache');
        return 'Config cache cleared';
    }

    // Clear application cache:
    function clear() {
        $exitCode = \Artisan::call('cache:clear');
        return 'Application cache cleared';
    }

    // Clear view cache:
    function view() {
        $exitCode = \Artisan::call('view:clear');
        return 'View cache cleared';
    }

    //Create symlink
    function symlink() {
        $exitCode = symlink ( '/home/cfcul/app_becas/public/storage' , '/home/cfcul/becas.cfcultura.com.ar/storage' );
        return 'Symlink created';
    }
}
