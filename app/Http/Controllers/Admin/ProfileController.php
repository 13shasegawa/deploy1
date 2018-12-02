<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\History;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
      $profile = Profile::find($request->id);
      return view('admin.profile.edit',['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
      $this->validate($request, Profile::$rules);

      $profile = new Profile;
      $profile_form = $request->all();

      unset($profile_form['_token']);
      $profile->fill($profile_form)->save();

      $history = new History;
      $history->users_id = $profile->id;
      $history->edited_at = Carbon::now();
      $history->save();

      return redirect('admin/profile/');
    }

    public function index(Request $request)
    {
      $cond_title = $request->cond_title;
      if ($cond_title !=''){
        $posts =Profile::where('name', $cond_title)->get();
      } else {
        $posts = Profile::all();
      }
      logger("=========");
      logger($posts);
      info($posts);
      info("=========");
      \Log::debug("エンジニア募集中");
      logger("=========");
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    public function delete(Request $request)
    {
      $profile = Profile::find($request->id);
      $profile->delete();
      return redirect('admin/profile/');
    }
}
