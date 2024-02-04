ماکرو (Macro) چیه؟
با ماکرو می‌تونیم به یک کلاس داخلی فریم‌ورک مثلا Request یا Response متدهای دلخواه اضافه کنیم و اون رو توسعه بدیم. این اضافه کردن موقع Run-Time رخ میده. با ماکرو، کلاس‌هایی رو میشه توسعه داد که از یک Trait به اسم Macroable استفاده می‌کنن. برای مثال می‌خوایم به کلاس Request یک قابلیت جدید اضافه کنیم:

Request::macro('hello', function($argument) {
    return 'Hello';
});
و از اون می‌تونیم به این صورت اضافه کنیم:

echo Request::hello(); // Hello

----------------
return redirect()->away('https://www.google.com');
----------------
وقتی توی اعتبارسنجی بصورت زیر از bail استفاده کنیم:

$this->validate($request, [
    'username' => 'bail|required|string|max:255|exists:users'
]);
اگه اولین شرط اعتبارسنجی با خطا مواجه بشه، اعتبارسنجی برای این ورودی متوقف میشه و بقیه شرط‌ها بررسی نمیشن. مثلاً اگه اعتبارسنجی از required رد نشه، آیتم‌های بعدی مثل exists که یک کوئری به دیتابیس هست بررسی نمیشه.
-----------------
class Post extends Model
{
    public function scopeActive($query)
    {
        return $query->where('active', 1)
                     ->where('status', 'published')
                     ->whereNull('deleted_at');
    }
}
اینجا یک Scope به اسم active ساختیم که نحوه استفاده از اون بصورت زیر هست:

$post = Post::active()->get();
---------------------
