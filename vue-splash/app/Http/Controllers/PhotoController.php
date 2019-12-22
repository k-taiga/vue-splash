<?php

namespace App\Http\Controllers;

// フォームリクエスト
use App\Http\Requests\StorePhoto;
use App\Http\Requests\StoreComment;

// モデル
use App\Photo;
use App\Comment;

use Illuminate\Http\Request;

// Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

  public function __construct()
  {
    // 認証が通らなくても使えるメソッド
     $this->middleware('auth')->except(['index', 'download', 'show']);
  }

  public function index()
  {
    // withメソッドはリレーションを事前にロードしておくメソッド N+1対策
    $photos = Photo::with(['owner'])
        ->orderBy(Photo::CREATED_AT,'desc')->paginate();

    return $photos;
  }

  /**
   * 写真投稿
   * @param StorePhoto $request
   * @return \Illuminate\Http\Response
   */
  public function create(StorePhoto $request)
  {
    // 写真投稿の拡張子を取得
    $extention = $request->photo->extension();

    $photo = new Photo();

    // インスタンス生成時に割り当てられたランダムなID値とextentionをファイル名とする
    $photo->filename = $photo->id . '.' . $extention;

    // S3に保存
    // publicは公開状態で保存するため
    // putFileAs(S3の保存場所,保存するファイル,保存する名前,公開設定)
    // putFileだとランダムな名前で保存される
    Storage::cloud()->putFileAs('', $request->photo, $photo->filename,'public');


    // データベースエラー時にファイル削除(ロールバック）を行うためトランザクションを行う
    DB::beginTransaction();

    try {
      // ログインしているユーザーに紐付けて保存
      Auth::user()->photos()->save($photo);
      DB::commit();
    } catch (\Exception $exception) {

      DB::rollback();

      // DBとの不整合を避けるためS3のファイルも削除する
      Storage::cloud()->delete($photo->filename);
      throw $exception;
    }


    return response($photo, 201);
  }

  /**
  * 写真ダウンロード
  * @param Photo $photo
  * @return \Illuminate\Http\Response
  */
  public function download(Photo $photo)
  {
    // 写真の存在チェック
    if (! Storage::cloud()->exists($photo->filename)) {
       abort(404);
    }
    $headers = [
       'Content-Type' => 'application/octet-stream',
       'Content-Disposition' => 'attachment; filename="' . photo->filename .'"',
    ];
    return response(Storage::cloud()->get($photo->filename), 20, $headers);
  }

  /**
  * 写真詳細
  * @param string $id
  * @return Photo
  */
  public function show(string $id)
  {
   $photo = Photo::where('id', $id)->with(['owner'])->first();
   // abortじゃなければ$photoを返す 見つからなけらばabort
   return $photo ?? abort(404);
  }
 }

  /**
  * コメント投稿
  * @param Photo $photo
  * @param StoreComment $request
  * @return \Illuminate\Http\Response
  */
  public function addComment(Photo $photo, StoreComment $request)
  {
    $comment = new Comment();
    $comment->content = $request->get('content');
    $comment->user_id = Auth::user()->id;
    $photo->comments()->save($comment);

    // authorリレーションをロードするためにコメントを取得し直し
    $new_comment = Comment::where('id', $comment->id)->with('author')->first();

    return response($new_comment, 201);
  }
