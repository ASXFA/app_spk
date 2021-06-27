$(function(){
    $('#users-page').addClass('active');

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


    $('#table-users').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"userLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-users').click(function(){
        $('#modalUsers').modal('show');
        $('#exampleModalLabel').html('Tambah Data Pengguna');
        $('#btnTambah').val('Tambah');
        $('#operation').val('Tambah');
        $('#nama_user').val('');
        $('#username').val('');
    })

    $('#btnTambah').attr('disabled','disabled');
    $('#uname').keyup(function(){
        var username = $('#uname').val();
        if (username == "") {
            Toast.fire({
                icon: 'error',
                title: 'Username tidak boleh kosong !'
            })
            $('#btnTambah').attr('disabled','disabled');
        }else{
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{uname:username},
                url:'cekUsername',
                success:function(result){
                    if (result.cond == 0) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Username Sudah ada !'
                        })
                        $('#btnTambah').attr('disabled','disabled');
                    }else{
                        Toast.fire({
                            icon: 'success',
                            title: 'Username bisa digunakan !'
                        })
                        $('#btnTambah').removeAttr('disabled','disabled');
                    }
                }
            })
        }
    })

    $('#formUsers').submit(function(e){
        e.preventDefault();
        var id_users = $('#id_users').val();
        var nama = $('#nama').val();
        var uname = $('#uname').val();
        var jk = $('#jk').val();
        var hp = $('#hp').val();
        var operation = $('#operation').val();
        if (nama != '' && uname != '') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:new FormData(this),
                processData: false,
                contentType: false,
                url:'doUsers',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-users').DataTable().ajax.reload();
                                $('#btnTambah').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama').val('');
                                $('#uname').val('');
                                $('#jk').val('');
                                $('#hp').val('');
                                $('#id_users').val('');
                                $('#modalUsers').modal('hide');
                            }else if(results.isConfirmed){
                                $('#table-users').DataTable().ajax.reload();
                                $('#btnTambah').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama').val('');
                                $('#uname').val('');
                                $('#jk').val('');
                                $('#hp').val('');
                                $('#id_users').val('');
                                $('#modalUsers').modal('hide');
                            }
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: result.msg+' !',
                            timer:3000,
                        })
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

    $(document).on('click','.gantiStatus',function(){
        Swal.fire({
            title: 'Yakin Ganti Status ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ganti !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                var status = $(this).attr('data-status');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id,status:status},
                    url:'gantiStatusUsers',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil diganti !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-users').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-users').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

    $(document).on('click','.editUsers',function(){
        var id = $(this).attr('id');
        $('#modalUsers').modal('show');
        $('#exampleModalLabel').html('Edit Data Pengguna');
        $('#id_users').val(id);
        $('#btnTambah').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'usersById',
            success:function(result){
                $('#nama').val(result.nama);
                $('#uname').val(result.uname);
                $('#hp').val(result.hp);
                $('#jk').val(result.jk);
            }
        })
    })

    $(document).on('click','.deleteUsers',function(){
        Swal.fire({
            title: 'Yakin Di Hapus ?',
            text: 'Data akan terhapus permanen !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id},
                    url:'deleteUsers',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-users').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-users').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})