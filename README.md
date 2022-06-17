## Giới thiệu
Với botble quản lý file bằng File Manager, với một số trường hợp cần phân quyền thì cần viết custom code để upload file riêng biệt.
Với plugin này cho phép việc tạo code thông qua command, chỉ cần seting theo đúng là có thể sử dụng.

## Cài đặt
1. Tiến hành copy plugin vào thư mục platforms/plugins
2. Trên giao diện quản lý plugin của admin, tiến hành active plugin.
3. Chạy lệnh command:
`` cms:plugin:make:upload-file-generate {plugin : The plugin name} {name : Model name} {base : Base model name} ``
Với các thông số:
- plugin: tên plugins muốn thêm code upload file
- name: tên của phần code upload file, thường sẽ dựa trên tên này để tạo: Model, variable, input
- base: tên model bạn muốn tạo quan hệ cho bảng file được upload.
4. Tiến hành chỉnh sửa code theo phần đính kèm:
Ví dụ: 
