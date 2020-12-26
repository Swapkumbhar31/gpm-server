<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
      public function __construct()
      {
            $this->middleware('auth');
      }
      public function liveStream()
      {
            return view('admin.livestream');
      }
      public function home()
      {
            return view('admin.home')->with([
                  'page_title' => 'Dashboard'
            ]);
      }
      public function modules()
      {
            return view('admin.modules')->with([
                  'page_title' => 'Modules'
            ]);
      }
      public function students()
      {
            return view('admin.students')->with([
                  'page_title' => 'Members'
            ]);
      }
      public function changepassword()
      {
            return view('admin.changepassword')->with([
                  'page_title' => 'Change Password'
            ]);
      }
      public function chapters($id)
      {
            return view('admin.chapters')->with([
                  'id' => $id,
                  'page_title' => 'Chapters'
            ]);
      }
      public function questions($chapter_id)
      {
            return view('admin.questions')->with([
                  'page_title' => 'Questions'
            ]);
      }
      public function stream()
      {
            return view('admin.stream')->with([
                  'page_title' => 'Stream'
            ]);
      }
}
