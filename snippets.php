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

//-----------------------------------Gate-------------------------------
//define a method in User class
public function canUpdatePost(){
	if($user->id == 1){return true;} //for admin
	return $post->user_id == $user->id;
}
//define Gate in AuthServiceProvider in boot method
Gate::define('update-post',function($user,$model = null){
	return $user->canUpdatePost();
})

//use Gate in Controller
if(Gate::allows('update-post',Post::class)){//codes}
if(auth()->user()->can('update-post',Post::class)){//codes}
if($this->authorize('update-post',Post::class)){//codes}

//in view
@can('update-post') ... @else ... @endcan
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
ctrl+r      search in terminal
sudo systemctl disable docker.socket
dmesg 		show log from init system
lsmod 	rmmod 	modprob 	- lspci 	lsusb 	lshw 	lshal 
ls | less 		ls | grep felan
pstree | less 		-		pstree | head 		-		pstree | tail   (bottom)
tail /var/log/syslog 		(آخرین اتفاقاتی که تو سیستم افتاده)
//for system monitoring on any file or log file
tail -f /var/log/syslog 		(آخرین اتفاقاتی که تو سیستم افتاده و باز میمونه و اتفاقات جدید مثل پلاگ شدن رو نشون میده)
whereis		-		ldd felanApp (see libraries that using)
jcal 					taghvim
man commandName 		(press h in manual)
tree dirName

ln fromFile.txt toFile.txt 			(hardlink-same inode)
unlink hardLinkFile.txt 			(removes linked file)
ln -s newFile.txt softlink.txt 		(**softlink-different inode) (softlink.txt -> newFile.txt)
which subl 	- 	ls -ltrh outputOfWhich 		

//------------------------bash-----------------------------
echo -e "hello\n"		
ls; echo done; 			(two seperate command in one line) 
ls && echo done 		(two command in one line that must execute all if one not run others wont run) echo $?
ls || echo done			(two command or)
echo done || ls; echo or
exec ls 						(run ls after that close shell)
MYMOOD=happy 					(define local variable)
export MYMOOD=happy 			(define global variable)
unset MYMOOD
history
!! 						(runs last command)
history | grep sudo
!?MYMOOD? 				(runs last command that has MYMOOD)
echo $PATH
PATH=$PATH:/home/babak/bin 		(add environment path)
vi .bashrc export PATH="$PATH:/home/babak/bin/"                     (add permanet environment path)
echo $PATH - export PATH=copy from echo $PATH and change it 		(edit environment path)

//------------------------streams----------------------------
ls -1 | sort 	----	ls -1 | sort -r   ----	sort file
sort -n file    					(sort in numeric)
sort file | uniq -c{for count} -u{doesnt show doubled word} -d{just shows doubled word}	 	(remove same words and shows just one of theme) 	{sort is nessessary in this command}
ls > newFile 	(خروجی را بریز به فابل) (redirect datas to newFile)
ls x* m* > outputFile 2> errorFile or &> &>> (خروجی استاندارد را بریز به outputFile و خروجی ارور را بریز به errorFile)
ls a* >> outputFile 			(محتویات فایل را حذف نکن فقط خروجی جدید را تهش اضافه کن)
ls a* x* 1> outputFile 2>&1 	خروجی ارور را بریز همونجا که خروجی استاندارد رو میریزی
ls a* x* > /dev/null 2> errorFile 	خروجی استاندارد را بریز تو هیچجا و نمایش نده
cat -n myFile        or         nl myFile 		(شماره گذاری خطوط خروجی)
cut -f1 d, 		(cut filed 1 seperator is ,)
paste 		join
cat file | tr ' ' '-' > newFile 	(translate from ' ' to '-') 	- {tr cant run alone}
cat file | tr '\t' ' ' | tr ' ' '-'
//regex
grep -v a friends  بگرد تو فایل دوستان دنبال غیر از a ها  (v is reverse)
egrep "^[a-h].*a$" friends 	شروع میشه با از a تا h هرچندتا و آخرش هم a باشه
tr ' ' ',' < newFile 	تمام فاصله هارو به کاما تبدیل کن ، ورودی از فایل newFile
sed 's/A/B' file 		(subtitude from 'A' to 'B' first word s is subtitude)   				- {sed can run alone}
sed 's/A/B/g' file 		(subtitude from 'A' to 'B' in all words - g is global)   				- {sed can run alone}
sed 's/you/shoma' file
{regex can use in sed command like} sed -r "s/^a/b/"

//------------------------file management-----------------------
ls -1 	{shows list 1 line 1 line} 		-	ls -R (Recursive) 	- rm -R myDir 	-  ls *jpg 
ls -ltrhi 	{shows list long and (h)human readable and (t)sort in time created and (r)reverse time and (i) inode}
cp source destination 		- 	mv file1 file2 file3 dir 	(multiple move-last one is destination)
mv myFile newFile 		(rename directory)
cp -f (f is overwrite-f is default in user mode) 	-i (interactive ask Y/N question for delete and overwrite)
cp -b (will copy and makes backup of file) 			-p (preserve the attributes-keeps attributes)
mkdir 	rmdir 	- 	mkdir -p dir1/dir2 	(makes child and Parent dirs)
rm * -i 	(remove all files here interactive)
cp * newFolder 	(copy all to newFolder) 	-  	rm newFolder/* 		(remove everything in newFolder)*/
ls f? 	(f and one character) 	- ls f?? 	(فایل سه حرفی که با f شروع میشه)
ls [a-z]* 	(matches all a,..,z) 	- ls [0-9a-zA-Z]* 	- ls [!x]* 	(not x) 	- ls ?[1e]* 
lsof 	(ls open files)
cp ???.* /tmp
//-----------------------------Search----------------------------
find . 	(find all in this dir) 		- find . -name "f*" 	- find . -iname "*f*"  (i is case insensitive)
find . -iname "???"  (all files in three characters)
find /babak -type d -iname "b*" 	(find just (d) for directories - (f) for files - (l) link)
find /babak -size -20M  or +2G 	or 	+100KB 	or 	 0 	 or   50MB  -	-size 0 == -empty
find . -atime -6 	or  -ctime 		or 		-mtime 	 (in days)	or -amin -cmin -mmin
find . -cmin -30 -exec cp -R "{}" backup/ \; نتیجه جستجو رو کپی کن به دارکتوری بک آپ
find . -name "a*" - type f | xargs rm 	خروجی جستجو رو به بده به xarg تا پاکش کنه
locate fileName 		(full text search in linux-veryyyyy faster than find)
sudo updatedb 			(updates db of locate) - conf is in /etc/updatedb.conf

ls | xargs echo filam ina hastan 		اول دستور جلوی xargs رو مینویسه بعد خروجی دستور اول رو جلوش انجام میده
ls | tee myFile 	خروجی ls رو میریزه تو myFile و همزمان نمایش میده چکار کرده
file -i flan 	(shows type of file)(i for mime format)
gzip myFile 	- 	gunzip myFile.gz
tar -cf myArchive.tar * 		(cf is create file - tar just collapse all files to one file[archive])
tar -cfz myArchive.tar.gz * 	(z for gzip)
tar -xf myArchive.tar.gz 		(extract- opens file)
tar -xfv myArchive.tar.gz 		(v for verbose! - show operaions in working)
tar -rf myArchive.tar newFile 	(appends newFile to tar archive)
sudo dd if=ubuntu.iso of=/dev/sdb/ bs=4096 		(advanced copy- if=input file - of=outputfile - bs=blocksize)
sudo dd if=/dev/sda1 of=backupsda1.dd bs=4096 	(backup of system)
sudo dd of=/dev/sda1 if=backupsda1.dd bs=4096 	(recover backup system) - 4096 fast is for cd dvd - bs=100M or 1G
ftp weecode.ir << END 	وصل شو به سرور ورودی بگیر در خطوط بعد تا زمانی که END تایپ شد تمام

//----------------------------process-----------------------------
& بعد هردستور اونو تو بک گراند ران میکنه و با دستور jobs قابل دیدنه
jobs -l   (l is long - returns pid)
bg پروسس فریز شده با ctrl+z را دوباره ران میکند 	bg %2
fg %2 پروسس شماره دو را به فورگراند میاره
kill %1		(normall close with save)		-	kill -9 16028<pid> 	  (force close)
killall sleep     هرچی دستور اسمش اسلیپ هست رو ببند
nohup اول دستور بذاریم اون دستور رو در هر صورتی حتی خروج ما اجرا میکنه
nohup script.sh > mynohup.out 2>&1 & 		-	cat mynohup.out
ps -ef 	دیدن همه پروسس ها + ppid 		-	ps aux | head 			دیدن همه پروسس ها 	- ps -l
ps -C xeyes --sort +comm,-sid -o user,pid,time,comm 	(C is search for)(o is Output columns)
free -m or -g (shows free ram in mb or gb)

//-------------------------------vi----------------------------------
i  insert mode 		-press Esc then -> u undo in command mode
replace a word in command mode on a word -> c then w
delete a word in command mode on a word  -> d then w
/ search forward 	- ? search backward
:q 	-:q!  !یعنی مطمعنم دارم چکار میکنم 	-:w! 
:w (write file) 	-:w myFile.txt (write to new file) 
:e! if you fucked file it reloads file from disk and refresh it
:! (runs shell commands) --like--> :!ls
ZZ (Exit and save if  modified- without : - its faster than :wq if large file not modified)
:help copy    -  :help save 

x cut
dd Delete Line
p paste last deleted text after cursor	-	P before

//-----------------------------Partiotions----------------------------
sudo fdisk -l /dev/sda
sudo fdisk  /dev/sda
sudo gdisk /dev/sda 	(modern command) (GPT disks) (in gdisk you can change partition name)

sudo mkfs -t ext4 -L driveName /dev/sda2 	(make file system == format) 	- sudo mkfs.ext4 /dev/sda2
ls /sbin/mk* 	(shows all mkfs commands)
mkswap /dev/sda3
blkid /dev/sda1		(returns UUID of disk)

mount /dev/sda1 /media/babak	- mount -o ro /dev/sda1 /media/babak 	(o is output - ro is readonly - rw)
umount /media/babak   or    umount /dev/sda1		- mount  (shows all mounted disks)
swapon -a 		- 	swapoff   - swapon -s

//-----------------------------system health---------------------------
cat /etc/fstab   (Bootup file)
sudo blkid
fsck /dev/sdb1 		- fsck UUID="" 			(file system check)
sudo tune2fs -l /dev/sda1
df -TH 				(disk free remains) (H or h human) (T for disk type)
du /Desktop -hc 	(disk usage) (c for total) (-s for just summary)
sudo debugfs /dev/sdb1
//---------------------------------------------------------------------