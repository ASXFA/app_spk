$(function(){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    $('#btn_lama').click(function(){
        var attr = $('#pass_lama').attr('type');
        if (attr == "password") {
            $('#btn_lama').html('<i class="fa fa-eye"></i>');
            $('#pass_lama').attr('type','text');
        }else{
            $('#btn_lama').html('<i class="fa fa-eye-slash"></i>');
            $('#pass_lama').attr('type','password');
        }
    })
    $('#btn_baru').click(function(){
        var attr = $('#pass_baru').attr('type');
        if (attr == "password") {
            $('#btn_baru').html('<i class="fa fa-eye"></i>');
            $('#pass_baru').attr('type','text');
        }else{
            $('#btn_baru').html('<i class="fa fa-eye-slash"></i>');
            $('#pass_baru').attr('type','password');
        }
    })
    $('#btn_conf').click(function(){
        var attr = $('#conf_pass').attr('type');
        if (attr == "password") {
            $('#btn_conf').html('<i class="fa fa-eye"></i>');
            $('#conf_pass').attr('type','text');
        }else{
            $('#btn_conf').html('<i class="fa fa-eye-slash"></i>');
            $('#conf_pass').attr('type','password');
        }
    })

    $('#uname').keyup(function(){
        var uname = $('#uname').val();
        if (uname == "") {
            Toast.fire({
                icon: 'error',
                title: 'Username tidak boleh kosong !'
            })
            $('#saveProfil').attr('disabled','disabled');
        }else{
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{uname:uname},
                url:'cekUsername',
                success:function(result){
                    if (result.cond == 0) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Username Sudah ada !'
                        })
                        $('#saveProfil').attr('disabled','disabled');
                    }else{
                        Toast.fire({
                            icon: 'success',
                            title: 'Username bisa digunakan !'
                        })
                        $('#saveProfil').removeAttr('disabled','disabled');
                    }
                }
            })
        }
    })

    $('#formEditProfil').submit(function(e){
        e.preventDefault();
        var nama = $('#nama').val();
        var uname= $('#uname').val();
        var hp= $('#hp').val();
        var jk= $('#jk').val();

        if (nama != "" && uname != "" && jk != "" && hp != "") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{nama:nama,uname:uname,jk:jk,hp:hp},
                url:'aksi_editProfil',
                success:function(result){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil diganti !',
                        timer:3000,
                    }).then((results) => {
                        if (results.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }else if(results.isConfirmed){
                            location.reload();
                        }
                    });
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
    $('#savePassword').attr('disabled','disabled');
    $('#conf_pass').keyup(function(){
        var pass_baru = $('#pass_baru').val();
        var conf_pass = $('#conf_pass').val();
        if (pass_baru != conf_pass) {
            $('#conf_pass').attr('class','form-control border border-danger');
            $('#savePassword').attr('disabled','disabled');
        }else{
            $('#conf_pass').attr('class','form-control border border-success');
            $('#savePassword').removeAttr('disabled','disabled');
        }
    })
    

    $('#formEditPassword').submit(function(e){
        e.preventDefault();
        var pass_lama = $('#pass_lama').val();
        var pass_baru = $('#pass_baru').val();
        var conf_pass = $('#conf_pass').val();

        if (pass_lama != "" && pass_baru != "" && conf_pass !="") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{pass_lama:pass_lama,pass_baru:pass_baru},
                url:'editPassword',
                success:function(result){
                    if (result.cond == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Peringatan !',
                            text: 'Password Lama Anda Salah !'
                        })
                    }else if(result.cond == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil diganti !',
                            timer:3000,
                        }).then((results) => {
                            if (results.dismiss === Swal.DismissReason.timer) {
                                window.location.href='logout';
                            }else if(results.isConfirmed){
                                window.location.href='logout';
                            }
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