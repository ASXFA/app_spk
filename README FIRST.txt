Struktur file : 
1. Bila pakai XAMPP : xampp/htdocs/wisata
2. Bila pakai Laragon : laragon/www/wisata

nama database di mysql harus 'wisata' (hapus tanda petik)

akses aplikasi : localhost/wisata
untuk akses ke halaman admin : localhost/wisata/auth

user account : admin / 12345

Jangan lupa untuk ganti nama aplikasinya, karna ada beberapa yang masih menggunakan kata "Nama Aplikasi". ada di : 
1. wisata/application/view/front/template/layout.html
2. wisata/application/view/front/index.html
3. wisata/application/view/front/template/footer.html
4. wisata/application/view/back/template/layout.html
5. wisata/application/view/back/template/top_bar.html

cara ganti dengan mudah, buka filenya, search 'nama aplikasi' (tanpa tanda petik). kemudian replace dengan yang diinginkan. 


terimakasih