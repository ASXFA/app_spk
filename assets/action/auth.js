$(function(){

    $('#btn-show-pass').click(function(){
        var attr = $('#pass').attr('type');
        if (attr == "password") {
            $('#btn-show-pass').html('<i class="zmdi zmdi-eye-off"></i>');
            $('#pass').attr('type','text');
        }else if(attr == "text"){
            $('#btn-show-pass').html('<i class="zmdi zmdi-eye"></i>');
            $('#pass').attr('type','password');
        }
    })

    $('#form-login').submit(function(e){
        e.preventDefault();
        var uname = $('#uname').val();
        var pass = $('#pass').val();
        if (uname != "" && pass != "") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                processData: false,
                contentType: false,
                data:new FormData(this),
                url:'action_login',
                success:function(results){
                    if (results.condition == 2) {
                        let timerInterval
                        Swal.fire({
                            icon:'success',
                            title:'Sukses Login !',
                            text:results.pesan,
                            html: 'Mohon Tunggu, Sedang mengalihkan ',
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href=results.url;
                            }
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed !',
                            text: results.pesan
                        });
                    }
                }
            })
        }else{
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })
})