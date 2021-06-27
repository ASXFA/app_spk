$(function(){
    $('#li-master').attr('class','nav-item has-treeview menu-open');
    $('#a-master').attr('class','nav-link active');
    $('#objek-page').attr('class','nav-link active');

    // $(document).on('click','.alternatif',function(){
    //     var id = $(this).attr('id');
    //     $('#modalAlternatif').modal({backdrop:'static',show:true});
    //     $('#id_objek_alternatif').val(id);
    //     $('#btnAlternatif').val('Update');
    // })

    $('#formAlternatif').submit(function(e){
        e.preventDefault();
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:new FormData(this),
            processData: false,
            contentType: false,
            url:'../doAlternatif',
            success:function(result){
                if (result.cond == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil disimpan !',
                        timer:1000
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            window.location.href='../listSantri';
                        }else if(results.isConfirmed){
                            window.location.href='../listSantri';
                        }
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Berhasil disimpan !',
                        timer:3000
                    })
                }
            }
        })
    })
})