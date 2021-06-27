$(function(){
    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#jenis-page').addClass('active');

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


    $('#table-jenis-kriteria').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"jenisLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-jenis').click(function(){
        $('#modal-jenis-kriteria').modal({backdrop: 'static',show:true});
        $('#title-jenis').html('Tambah Jenis Kriteria');
        $('#btnJenis').val('Tambah');
        $('#operation').val('Tambah');
        $('#nama_jenis').val('');
        $('#nilai_jenis').val('');
    })

    $('#formJenis').submit(function(e){
        e.preventDefault();
        var nama_jenis = $('#nama_jenis').val();
        var nilai_jenis = $('#nilai_jenis').val();
        if (nama_jenis != '' && nilai_jenis != '') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data: new FormData(this),
                processData: false,
                contentType: false,
                url:'doJenis',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                                $('#btnJenis').val('');
                                $('#operation').val('');
                                $('#nama_jenis').val('');
                                $('#nilai_jenis').val('');
                                $('#id_jenis').val('');
                                $('#modal-jenis-kriteria').modal('hide');
                            }else if(results.isConfirmed){
                                location.reload();
                                $('#btnJenis').val('');
                                $('#operation').val('');
                                $('#nama_jenis').val('');
                                $('#nilai_jenis').val('');
                                $('#id_jenis').val('');
                                $('#modal-jenis-kriteria').modal('hide');
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
                icon: 'warning',
                title: 'Field Tidak boleh kosong !',
                showConfirmButton: false,
                timer: 2000
            })
        }
    })

    $(document).on('click','.editJenis',function(){
        var id = $(this).attr('id');
        $('#modal-jenis-kriteria').modal({backdrop:'static',show:true});
        $('#title-jenis').html('Tambah Jenis Kriteria');
        $('#id_jenis').val(id);
        $('#btnJenis').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_jenis:id},
            url:'jenisById',
            success:function(result){
                $('#nama_jenis').val(result.nama_jenis);
                $('#nilai_jenis').val(result.nilai_jenis);
            }
        })
    })

    $(document).on('click','.deleteJenis',function(){
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
                    data:{id_jenis:id},
                    url:'deleteJenis',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }else if(results.isConfirmed){
                                location.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})