<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orderedproduct;

class CommentController extends Controller
{
    public function all() {
      $data['ops'] = Orderedproduct::whereNotNull('comment')->paginate(10);
      return view('admin.comments.index', $data);
    }

    public function complains() {
      $data['ops'] = Orderedproduct::whereNotNull('comment')->where('comment_type', 'Complain')->paginate(10);
      return view('admin.comments.index', $data);
    }

    public function suggestions() {
      $data['ops'] = Orderedproduct::whereNotNull('comment')->where('comment_type', 'Suggestion')->paginate(10);
      return view('admin.comments.index', $data);
    }
}
