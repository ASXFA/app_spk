$(function(){
    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#santri-page').addClass('active');

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


    $('#table-santri').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"santriLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-santri').click(function(){
        $('#modal-santri').modal('show');
        $('#exampleModalLabel').html('Tambah Data Santri');
        $('#btnTambah').val('Tambah');
        $('#operation').val('Tambah');
        $('#nama_santri').val('');
        $('#ttl_santri').val('');
        $('#jk_santri').val('');
        $('#kelas_santri').val('');
    })

    $('#formSantri').submit(function(e){
        e.preventDefault();
        var id_santri = $('#id_santri').val();
        var nama = $('#nama_santri').val();
        var ttl = $('#ttl_Santri').val();
        var jk = $('#jk_santri').val();
        var kelas = $('#kelas_santri').val();
        var operation = $('#operation').val();
        if (nama != '' && ttl != '' && jk !='' && kelas !='') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:new FormData(this),
                processData: false,
                contentType: false,
                url:'doSantri',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-santri').DataTable().ajax.reload();
                                $('#btnTambah').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_santri').val('');
                                $('#ttl_santri').val('');
                                $('#jk_santri').val('');
                                $('#kelas_santri').val('');
                                $('#id_santri').val('');
                                $('#modal-santri').modal('hide');
                                if (result.ops == "tambah") {
                                    window.location.href='listAlternatif/'+result.id_santri;
                                }
                            }else if(results.isConfirmed){
                                $('#table-santri').DataTable().ajax.reload();
                                $('#btnTambah').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_santri').val('');
                                $('#ttl_santri').val('');
                                $('#jk_santri').val('');
                                $('#kelas_santri').val('');
                                $('#id_santri').val('');
                                $('#modal-santri').modal('hide');
                                if (result.ops == "tambah") {
                                    window.location.href='listAlternatif/'+result.id_santri;
                                }
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

    $(document).on('click','.editSantri',function(){
        var id = $(this).attr('id');
        $('#modal-santri').modal('show');
        $('#exampleModalLabel').html('Edit Data Santri');
        $('#id_santri').val(id);
        $('#btnTambah').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'santriById',
            success:function(result){
                $('#nama_santri').val(result.nama_santri);
                $('#ttl_santri').val(result.ttl_santri);
                $('#kelas_santri').val(result.kelas_santri);
                $('#jk_santri').val(result.jk_santri);
            }
        })
    })

    $(document).on('click','.deleteSantri',function(){
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
                    url:'deleteSantri',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-santri').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-santri').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})