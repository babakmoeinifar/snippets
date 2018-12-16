<?

php artisan cache:clear php artisan config:clear composer dump-autoload

pa vendor:publish --tag=laravel-pagination

php artisan migrate:rollback --step=3

<small>{{$errors->first('fieldName')}}</small>

<li class="{{Request::is('/') ? active : ''}}"><a href="/">Home</a></li>
//=====================================validate===========================================
$this->validate($request,[
'image'		=>'mimes:jpg,jpeg,bmp,png | max:500',
'gender'	=>'in:male,female',
'birthdate' =>'before:2015-01-01',
'age'		=>'integer|between:1,100',
'favorites' =>'array',
'favorites.*' =>'integer' //dakhele array favorite balaei ra validate mikone 
'favorites.*.name' =>'string' //dakhele array favorite bala balaei va dakhele array balaei(array tu dar tu)ra validate mikone 
'military'	=>['required_if:gender,male , in:payanKhedmat,moaf'],
'password'  => 'required_without:user_id',
'field_c'   => 'required_with:field_d',


]);

//=====================================response===========================================

//set header
return response()->header('X-FOO','something');

//download file
$file = storage_path('app\public\test.txt');
return response()->download($file);
return response()->download($file,'nameForDownload.txt');

//show file
$file = storage_path('app\public\test.pdf');
return response()->file($file);

//set header for file
return response()->file($file,['X-FOO'=>'someText']);

//set application json
return response()->json(['a','b']);

//=====================================logs===========================================
Log::info('done!');
Log::error('error!');
Log::debug('دیباگینگ');
Log::warning('warning!');
Log::notice('اعلان');
Log::critical('بحرانی');
Log::alert('اطلاع رسانی سریع!');
Log::emergency('emergency!');

//=======================================others==============================================
Route::get('order/{id?}'); 			//means id is optional - in controller method ($id = null){}

//form action for delete by route() method
action="{{route('remove',['id'=> $user->id])}}"

//run in shell
./vendor/bin/phpunit tests/Feature/ViewIndexPageTest.php

//carry inputs from a page to redirected page
return redirect()->withInput();

//try catch block
try{
	//codes
}
catch(\Exception $e){
	//codes
	Log::error($e);
	throw($e);
}

//convert title to slug
str_slug($title);

//Answers of REST
۱xx - informational;
۲xx - success;
۳xx - redirection;
۴xx - client error;
۵xx - server error.

//just Next and Prev Pagination method in controller
simplePaginate()

//Pagination method for view
{{ $posts->links() }}
{{ $posts->onEachSide(4)->links() }}

//Pagination method for view and for SEARCH - keeps url values for next or prev page - Sayad Lesson117 minute 30
{{ $posts->appends(request()->query()->links()) }}

//Pagination method for view and for Jump to an ID in page like #top - Sayad Lesson117 minute 31.30
{{ $posts->fragment('top')->links() }}

//--------------------------mutators(getters and setters)--------------------------------------------------
//............GETTERS..........
//get part of record for example first name of column (name = Babak Moeinifar) and result should be Babak
public function getFirstNameAttribute(){
	return explode(' ',$this->name)[0];	
}
//Call it
$user->firstName;	or 		$user->firstname; 		or 			$user->first_name;

//another example for getting it's email service provider like gmail.com
public function getEmailProviderAttribute(){
	$provider = explode('@',$this->email)[1];
	return explode('.',$provider)[0];
}

//rewrite from DB - name is an attribute in DB table
public function getNameAttribute(){
	return strtolower($this->attributes['name']);		//attribute[''] means from DB not from functions
}

//laravel built-in getter
getAttribute();

//............SETTERS..........
//sayad tut lesson 120 minute15 
public function setFirstNameAttribute($value){
	$name = $this->attributes['name'];
	$this->attributes['name'];
}

//===========================================query builders======================================================
//for example group by order_id. In database.php 'strict' should change to false
->groupeBy('order_id');

//show query builder syntax for checking
return $result->toSql();

//variety of where
1.	where		whereIn		whereNotIn		whereBetween		whereNotBetween		whereNull		whereNotNull
2.	whereColumn('username','password')		whereExists			whereRaw
//many where in on function
3.	where(function($query){
		$query->where('total','>','5000');
		$query->where('price','<','100');
	});

//get id in same time when insert id
$id = User::insertGetId();

//inner join tables
DB::table('users')->join('posts','users.id','=','posts.user_id')
				  ->select('users.username','posts.title as post_title');

DB::table('users')->leftJoin('posts','users.id','=','posts.user_id')
				  ->select('users.username','posts.title as post_title');

//columns of users * colum of posts
DB::table('users')->crossJoin('posts');

//for ex: orders in last 24 hours
where('created_at',Carbon::now()->subHours(24))->get();

//============================================models============================================================
//Options for Model
use SoftDeletes;
//Laravel built-in setters
public $dates = ['deleted_at'];		//is needed if protected $timestamps =false;
protected $dateFormat = 'y-m-d h:i:s';
protected $table
protected $primaryKey
protected $timestamps
protected $connection
protected $fillable
protected $with
protected $withCount
protected $perPage

//---------INSERT--------
//insert data 1st way
$post = new Post();
$post->field = '';
$post->save();

//insert data 2nd way - $fillable support
Post::create(['field'=>'']);

//insert data 3rd way - $fillable support
$post = new Post(['field'=>'']);
$post->save();

//insert data by if is not exists ,create it 
Post::firstOrCreate(['field'=>'123','field2'=>'456']);
Post::(firstOrNew(['field'=>'123','field2'=>'456']))->save();

//---------UPDATE--------
//update data 1st way
$post = Post::find('1');
$post->title = 'aaaa';
$post->save();

//update data 2nd way
Post::where('id','1')->update(['field'=>'']);

//update data 2nd way
Post::updateOrCreate(['id'=>$id],['field'=>'']);	//or
Post::updateOrCreate(['field'=>'']);

//---------DELETE--------
Post::find(1)->delete();     		//if() neede
Post::where('id',1)->delete();		//if() not require
Post::destroy(1);					//if() not require

//---------create custom GLOBAL scope (in lesson 106 of sayad azami) in our model
protected static function boot(){
	parent::boot();
	static::addGlobalScope('idsGreaterThan15',function($query){
		$query->where('id','>',15);
	});
}

//---------create custom scope (in lesson 106 of sayad azami) in our model
//static scope
public function scopeFelan($query){
	$query->whereIn('id',[10,20,30]);
}	//how to call it in next line
Post::felan()->get();

//or dynamic scope
public function scopeFelan($query,$ids=[]){
	$query->whereIn('id',$ids);
}
Post::felan([10,20,30])->get();

//-----relations------

$user->posts 				//with get() extra query
$user->posts() 				//just use and not get()

$user->posts()->where('status',1)->get)();

//users where has more than 3 posts and Opposite
User::has('posts','>','3')->get();
User::doesntHave('posts')->get();

//where condition on posts and Opposite
User::whereHas('posts',function($query){
	$query->where('title','like','%om%');
})->get();

User::whereDoesntHave('posts',function($query){
	$query->where('title','like','%om%');
})->get();

//counts it's posts
User::withCount(['posts as POSTS','tags'])->get();

//eager loading
User::with('posts')->get();
User::with('posts:user_id,id,age')->get();		//with extra fields custom
User::with(['profile'=>function($query){
	$query->where('age',20);
}])->get();


//----------create(save)--------
$user = User::find($id);
$profile = new Profile(['age' => 18, 'height' => 180, 'bio'=>'nothing...!']);
$profile->user_id = $user->id;

way1-	$profile->save();

way2-   $user->profile()->save($profile);

way3-   $user = new User(['name' => 'foooo','email' => 'foooddsss@bar.com','password' => bcrypt('123456')]); $user->save();

way4-   $user->posts()->save(new Post(['title' => 't', 'body' => 'b']));

way5-   $user->posts()->saveMany([
            new Post(['title' => 'post1', 'body' => 'post1 body']),
            new Post(['title' => 'post2', 'body' => 'post2 body']),
            new Post(['title' => 'post3', 'body' => 'post3 body']),
        ]);

way6-   $user->posts[0]->title = 'foo';		//id=1
        $user->posts[1]->title = 'bar';		//id=2
        $result = $user->push();

way7-   //attach a profile to a new user that creates in same time for BELONGTO
		$profile = new Profile(['age' => 18, 'height' => 180, 'bio'=>'nothing...!']);
		$user = new User(['name'=>'foo']);		$user->save();
		$profile->user()->associate($user);		$profile->save();

//------for MANY TO MANY------
way1-	$post = Post::find($id);
		$post->tags()->attach([1,2,3]);			//opposite is detach

way2-   sync()
way3-   syncWithoutDetach()
way4-   toggle()

//----------------------authorize----------------------
abort_if($project->user_id !== auth()->id(), 403);		//in controller
abort_unless(auth()->user()->owns($project), 403);
abort_if(\Gate::denies('update',$project), 403);
abort_unless(\Gate::allows('update',$project), 403);
auth()->user()->can('update',$project);
auth()->user()->cannot('update',$project);
$this->authorize('update',$project);					//best

middlware('can:update,project');						//in web.php
middlware("can('update',project)");						//in web.php
@can('update', $project)  /*codes*/ @endcan				//in view
return $project->user_id == $user->id;					//in Policy

//define Gate in AuthServiceProvider in boot method
Gate::define('update-post',function($user,$post=null){
	return $post->user_id == $user->id;
})

//use Gate in Controller
$post = Post::where('user_id',1)->first();
if(Gate::allows('update-post',$post)){//codes}
if(auth()->user()->can('update-post',$post)){//codes}
if($this->authorize('update-post',$post)){//codes}

//=====================================js===========================================
laravel ecommerce lesson #77 in minute 15 has a good example for using functions
if($('#paypal').is(':checked')){ alert('do something');}






//=====================================mysql=========================================

//give access to a db from a user
GRANT ALL on dbName TO 'userName'@'localhost';

//Lynda tut 018 Use environment variables
//first remove db name line compelete from .env file
//ست کردن دیتابیس پروژه از مای اسکیول که این دیتابیس ساخته شده و با این دستور تخصیص میده
set -x DB_DATABASE db_irenkala

//drop a table from tinker
php artisan tinker
//Then
Schema::drop('books')
//=====================================phpstorm=========================================
alt+up or alt+down 	move to methods
alt+j 		 select same words
alt+ctl+o    remove not used classes from header file
alt+ctrl+m   extract method

//=====================================git=========================================
git tag -a v0.1 -m "aaaaa"
git tag
git push origin --tags
git checkout v0.2


//=====================================linux=========================================
ctrl+f1		go to console when gdm not working