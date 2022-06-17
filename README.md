## Giới thiệu
Với botble quản lý file bằng File Manager, với một số trường hợp cần phân quyền thì cần viết custom code để upload file riêng biệt.
Với plugin này cho phép việc tạo code thông qua command, chỉ cần seting theo đúng là có thể sử dụng.

## Cài đặt
1. Tiến hành copy plugin vào thư mục platforms/plugins
2. Trên giao diện quản lý plugin của admin, tiến hành active plugin.
3. Chạy lệnh command:
``php artisan cms:plugin:make:upload-file-generate {plugin : The plugin name} {name : Model name} {base : Base model name}``
Với các thông số:
- ``plugin``: tên plugins muốn thêm code upload file
- ``name``: tên của phần code upload file, thường sẽ dựa trên tên này để tạo: Model, variable, input
- ``base``: tên model bạn muốn tạo quan hệ cho bảng file được upload.
4. Tiến hành chỉnh sửa code theo phần đính kèm:
Ví dụ: 
```
php artisan cms:plugin:make:upload-file-generate blog post-file post
------------------
The upload file CRUD for plugin  blog was created in C:\OpenServer\domains\botble.doc\platform/plugins\blog, customize it!
------------------
Application cache cleared!
Add below code into \platform/plugins\blog/config/permissions.php
[
    'name' => 'Post files files',
    'flag' => 'post-file.index',
],
[
    'name'        => 'Create',
    'flag'        => 'post-file.create',
    'parent_flag' => 'post-file.index',
],
[
    'name'        => 'Edit',
    'flag'        => 'post-file.edit',
    'parent_flag' => 'post-file.index',
],
[
    'name'        => 'Delete',
    'flag'        => 'post-file.destroy',
    'parent_flag' => 'post-file.index',
],

Add below code into \platform/plugins\blog/helpers/constants.php
if (!defined('POST_FILE_MODULE_SCREEN_NAME')) {
    define('POST_FILE_MODULE_SCREEN_NAME', 'post-file');
}

Add below code into \platform/plugins\blog/src/Forms/PostForm.php
//Add this code to function buildForm()

->setFormOption('enctype', 'multipart/form-data') after  $this->setupModel(new Post)
Add below code into \platform/plugins\blog/src/Models/Post.php
// Add this code into Post model
/**
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function postFile()
{
    return $this->hasMany('Botble\Blog\Models\PostFile', 'post_id');
}

// Add this code into ::boot function of Post model
protected static function boot()
{
    parent::boot();

    self::deleting(function (Post $post) {
        \PostFile::where('post_id', $post->id)->delete();
    });
}
Add below code into \platform/plugins\blog/src/Providers/BlogServiceProvider.php
// Add into function register()
$this->app->bind(\Botble\Blog\Repositories\Interfaces\PostFileInterface::class, function () {
    return new \Botble\Blog\Repositories\Caches\PostFileCacheDecorator(
        new \Botble\Blog\Repositories\Eloquent\PostFileRepository(new \Botble\Blog\Models\PostFile)
    );
});

// Add into function boot()
Add 'generate', 'file' into ->loadAndPublishConfigurations(['generate', 'file']) after $this->setNamespace

$this->app->register(UploadFileEventServiceProvider::class);
$this->app->register(UploadFileHookServiceProvider::class);
Add below code into \platform/plugins\blog/src/Plugin.php
// Add into function remove()
Schema::dropIfExists('post_files');

Add below code into \platform/plugins\blog/webpack.mix.js
// Add this code to webpack.mix.js
.sass(source + '/resources/assets/sass/post-file-admin.scss', dist + '/css')
.js(source + '/resources/assets/js/post-file-admin.js', dist + '/js')

.copy(source + '/public/images', dist + '/images')

```

5. Tiến hành deactive and reactive lại module được generate code upload file.
6. Tiến hành chạy ``npm run dev`` để build js, sass và publish file về folder public.
7. Tiến hành kiểm tra xem chạy ổn chưa.

------------------
## Một số lưu ý:

1. Version này dựa trên ý tưởng RVMedia của Botble, được xây dựng thành plugins hỗ trợ tự tạo code nhanh hơn.
2. Phần code này giao diện khá là đơn giản, không có preview image, xóa khỏi danh sách file trước khi upload lên. 
--> Phần này sẽ update sau khi mình tìm được đoạn code cũ.
3. Vì là upload theo form nên chỉ nên upload file nhỏ, không có ajax, không sử dụng chunk upload.
--> hi vọng có bạn nào có thời gian cải thiện giúp.
