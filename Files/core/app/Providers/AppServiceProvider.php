<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\GeneralSetting as GS;
use App\Category;
use App\Product;
use Carbon\Carbon;
use App\Orderedproduct;
use App\Menu;
use App\Social;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      $gs = GS::first();
      $categories = Category::where('status', 1)->get();
      $menus = Menu::all();
      $socials = Social::all();

      if (!session()->has('browserid')) {
        session()->put('browserid', uniqid());
      }
      Schema::defaultStringLength(191);
      View::share('gs', $gs);
      View::share('categories', $categories);
      View::share('menus', $menus);
      View::share('socials', $socials);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
