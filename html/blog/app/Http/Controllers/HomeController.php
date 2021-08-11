<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd($memos);
        return view('create');
    }

    public function create()
    {

        return view('create');
    }
    
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);

        //同じタグがあるか確認
        $insertArr = [
            'content' => htmlspecialchars($data['content']),
            'user_id' => $data['user_id'], 
            'status' => 1,
        ];
        if(isset($data['tag'])) {
            $exist_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])->first();
            // dd($is_exist);

            $tag_id = empty($exist_tag['id']) ? Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]) : $exist_tag['id'];
            $insertArr['tag_id'] = $tag_id;
        }
        // $tag_id = Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]);
        // dd($tag_id);

        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す
        $memo_id = Memo::insertGetId($insertArr);
        
        // リダイレクト処理
        return redirect()->route('home');
    }
    
    public function edit($id){
        // 該当するIDのメモをデータベースから取得
        $user = \Auth::user();
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])->first();
        //   dd($memo);
        //取得したメモをViewに渡す
        return view('edit',compact('memo'));
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        // dd($inputs);
        Memo::where('id', $id)->update(['content' => $inputs['content'], 'tag_id' => $inputs['tag_id'] ]);
        return redirect()->route('home')->with('success', 'メモの更新が完了しました！');
    }

    public function delete(Request $request, $id)
    {
        $inputs = $request->all();
        // dd($inputs);
        // 論理削除なので、status=2
        Memo::where('id', $id)->update([ 'status' => 2 ]);
        // ↓は物理削除
        // Memo::where('id', $id)->delete();

        return redirect()->route('home')->with('success', 'メモの削除が完了しました！');
    }
}