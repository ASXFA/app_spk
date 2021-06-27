$(function(){
    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#selisih-page').addClass('active');

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


    $('#table-selisih').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"selisihLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-selisih').click(function(){
        $('#modalSelisih').modal({backdrop: 'static',show:true});
        $('#exampleModalLabel').html('Tambah Data Selisih')
        $('#btnSelisih').val('Tambah');
        $('#operation').val('Tambah');
        $('#nilai_selisih').val('');
        $('#bobot_selisih').val('');
        $('#keterangan_selisih').val('');
        $('#id_selisih').val('');
    })

    $('#formSelisih').submit(function(e){
        e.preventDefault();
        var id_selisih = $('#id_selisih').val();
        var nilai_selisih = $('#nilai_selisih').val();
        var bobot_selisih = $('#bobot_selisih').val();
        var keterangan_selisih = $('#keterangan_selisih').val();
        var operation = $('#operation').val();
        if (nilai_selisih != '' && bobot_selisih != '' && keterangan_selisih != '') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:new FormData(this),
                processData: false,
                contentType: false,
                url:'doSelisih',
                success:function(result){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil '+operation+' !',
                        timer:3000,
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            $('#table-selisih').DataTable().ajax.reload();
                            $('#modalSelisih').modal('hide');
                        }else if(results.isConfirmed){
                            $('#table-selisih').DataTable().ajax.reload();
                            $('#modalSelisih').modal('hide');
                        }
                    })
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

    $(document).on('click','.editSelisih',function(){
        var id = $(this).attr('id');
        $('#modalSelisih').modal({backdrop:'static',show:true});
        $('#id_selisih').val(id);
        $('#btnSelisih').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_selisih:id},
            url:'selisihById',
            success:function(result){
                $('#nilai_selisih').val(result.nilai_selisih);
                $('#bobot_selisih').val(result.bobot_selisih);
                $('#keterangan_selisih').val(result.keterangan_selisih);
            }
        })
    })

    $(document).on('click','.deleteSelisih',function(){
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
                    data:{id_selisih:id},
                    url:'deleteSelisih',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-selisih').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-selisih').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})