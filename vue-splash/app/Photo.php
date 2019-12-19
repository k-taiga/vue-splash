<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{

	// プライマリーキーの型
	protected $keyType = 'string';

	const ID_LENGTH = 12;

	// JSONに含めるアクセサ
	protected $appends = [
	    'url',
	];

    // JSONに含める属性 反対に含めないものはhidden
    protected $visible = [
        'id', 'owner', 'url',
    ];


	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		if (!array_get($this->attributes, 'id')) {
			$this->setId();
		}
	}

	// ランダムなID値をidにセットする
	private function setId()
	{
		$this->attributes['id'] = $this->getRandomId();
	}

	// ランダムなID値を作成する
	private function getRandomId()
	{
		$characters = array_merge(
			range(0,9),range('a', 'z'),range('A','Z'),['-', '_']
		);

		$length = count($characters);

		$id = "";

		for ($i = 0; $i < self::ID_LENGTH; $i++) {
			$id .= $characters[random_int(0, $length - 1)];
		}

		return $id;
	}

	/**
	 * リレーションシップ - usersテーブル
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function owner()
	{
		// user_idがuserモデルのidに紐づく
		return $this->belongsTo('App\User', 'user_id', 'id', 'users');
	}

	/**
	 * アクセサ - url
	 * @return string
	 */
	public function getUrlAttribute()
	{
		// url メソッドは S3 上のファイルの公開 URL を返却
		return Storage::cloud()->url($this->attributes['filename']);
	}
}
