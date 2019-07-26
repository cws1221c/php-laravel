<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function writePage(Request $req)
    {
        return view('board/write');
    }

    public function writeProcess(Request $req)
    {
//        //타이틀과 컨텐츠는 공백이여서는 안되고
//        //타이틀은 한글, 영문 띄어쓰기만 사용 가능 -- 보너스

        $images=array();
        if($files = $req->file('images'))
        {
            foreach ($files as $file) {
                $file->storeAs('upload',  $file->getClientOriginalName());
                $images[] = $file->getClientOriginalName();
//              $file->move('upload', $name);
            }
        }

        $data = $req->all();

        $rules = [
            'title'=> ['required', 'regex:/^[가-힣a-zA-Z\s]+$/'],
            'content'=>['required']
        ];
        $message = [
            'title.required' => '제목은 반드시 입력되어야 합니다.',
            'title.regex' => '제목은 반드시 한글, 영문 띄어쓰기만 사용가능합니다.',
            'content.required' => '내용 필수 항목입니다.'
        ];
        $validator = \Validator::make($data, $rules, $message);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        auth()->user()->boards()->create(['title'=>$data['title'], 'content'=>$data['content'], 'file'=>implode('/',$images)]);

        return redirect('/')->with('fm', '글이 작성되었습니다.');
    }

    public function listPage(Request $req)
    {
        $list = Board::paginate(5);
        //index페이지에서 화면에 출력
        // 페이징 처리 --- 한페이지당 글의 수 5개씩
        return view('board/index', ['list'=>$list]);
    }
    public function  viewPage(Request $req, $id){
        $data = Board::find($id);
        if(!$data){
            return redirect('/board')->with('fm', '없는 글입니다.');
        }
        return view('board/view', ['data'=>$data]);
    }

    public function getImage(Request $req, $name){
        $file = storage_path('app/upload/'. $name);

        if(!file_exists($file)){
            $file = storage_path('app/upload/notfound.png');
        }
        return response()->download($file);
    }
}
