window.addEventListener('load', e =>{


    dashboard();
    password();
    ajax();
    slick();
    inputmask()
	



});

 


 
function dashboard(){


     
	


    // AQUI ESTAN TODAS LAS FUNCIONES DE CONTENIDO EMERGENTE
    $('.open_register_form').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('.created_user').fadeIn(500); 
        $('.contenedor_respuesta').html('').hide();
        $('.contenedor_form').show();

    });




    $('.Open_event_created').click(function(){

        var id_document = $(this).val();

        if (id_document !== null) {

            $('documento_activo').html('');
            $('documento_activo').show();


        } else{
           $('documento_activo').html('');
           $('documento_activo').hide();
        }

        $('.contenedor_emergente').fadeIn(400);
        $('.contenedor_info').hide();
        $('.add_event').fadeIn(500);


    })



    $('.move_document').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#move_document').fadeIn(500);
    })


    $('#open_create_folder').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#add_folder').fadeIn(500);
    });


    $('#open_create_document').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#add_file').show();
    });


    $('#changePassword').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#changePasswor_cont').fadeIn(500);
    })


    $('#updatedProfile').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#changeProfile').fadeIn(500);
    });


    $('#Open_event_recordatorio').click(function(){
        $('.contenedor_emergente').fadeIn(400);
        $('#add_recordatorio').fadeIn(500);
    });


    $('.exit_button').click(function(){
        $('.contenedor_emergente').fadeOut(400);
        $('.contenedor_info').fadeOut(500);
        $('.contenedor_respuesta').html("");
    })




    // Aqui esta todas las opciones del menu:
    let notification_show = false;
    $('#Show_Notification').click(function(){

        if (notification_show === false){
            $('#cont_emer_notificaciones').show();
            notification_show = true;
        } else{
            $('#cont_emer_notificaciones').hide();
            notification_show = false;
        }
    })


    let nav = false;
    $('#icon_responsive_boton').click(function(){
        if (nav === false){
            $('#left_nav').addClass('nav_activate');
            $('.background_nav_responsive').show();
            nav = true
        } else{
            $('.background_nav_responsive').hide();
            $('#left_nav').removeClass('nav_activate');
            nav = false
        }
    })

    $('.background_nav_responsive').click(function(){
        $(this).hide();
        $('#left_nav').removeClass('nav_activate');
        nav = false

    })




    // AQUI CARGAMOS TODOS LAS FUNCIONES DEL REGISTRO FINAL
    $('#next_slide_registration').click(function(e){
        e.preventDefault();
        $('.contenedor_inicio').hide();
        $('#contenedor_formulario').show();
    })


    $('.close').click(function(){
        $('.error_respuesta').html("");
        $(this).parent().hide();

    })


    




    $('.Filter_document').click(function(e){

        // Aqui llamamos el id del H6 (This)
        var id = $(this).attr('id');

        // Llamamos y ocultamos todos los div
        const documentos = $('.documentos');
        documentos.hide();

        // Cargamos los div correspondientes
        switch(id) {
          case 'all_Publico':

                $('.Publico').show();
                //documentos.classList.contains('Publico').show();

            break;
          case 'all_Privado':
                $('.Privado').show();
            break;
          default:
            let variable = 'filter_'+id;
            $('.'+variable).show();

        }


    });





    // Aquí trabajamos la parte de calendario
    
    $('body').on('click', 'button.show_more_button', function() {
        if (this.classList.contains('activate')) {
            $(this).removeClass('activate')
            $(this).parent().parent().parent().children('.more_info').hide()
        } else{
            $(this).addClass('activate')
            $(this).parent().parent().parent().children('.more_info').show()
        } 
    });
    



    $('.opcion_change_register').click(function(){

        if (this.classList.contains('interno')) {
            $('.campos_externos').hide();
            $('.opcion_change_register').removeClass('activate')
            $(this).addClass('activate');
        } else{
            $('.campos_externos').show();
            $('.opcion_change_register').removeClass('activate')
            $(this).addClass('activate');
        }
    })




    $('.opcion_change_document').click(function(){

        if (this.classList.contains('upload')) {
            $('.campos_externos').hide();
            $('.opcion_change_document').removeClass('activate')
            $(this).addClass('activate');
        } else{
            $('.campos_externos').show();
            $('.opcion_change_document').removeClass('activate')


            $(this).addClass('activate');
        }
    })





    $('#carpeta').on('change', function(e){
        e.preventDefault();

        var val = $('#carpeta').val() 

        // Aqui tomamos los options del datalist
        var carpeta = $('#brow_forder option').filter(function() {
            return this.value == val;

        }).data('folder');
        /* if value doesn't match an option, xyz will be undefined*/
        var msg = carpeta ? 'carpeta=' + carpeta : 'No Match';
        //alert(msg)

        $('#carpeta_data').val(carpeta);
    });





     $('#documento_id').on('change', function(e){
        e.preventDefault();

        var val = $('#documento_id').val()

        // Aqui tomamos los options del datalist
        var documento_id = $('#brow_documentos option').filter(function() {
            return this.value == val;

        }).data('documentos_event');
        /* if value doesn't match an option, xyz will be undefined*/
        var msg = documento_id ? 'documento_id=' + documento_id : 'No Match';

        $('#documento_result').val(documento_id);

        let data = {'data': documento_id}
        console.log(data)
    });




    $('#select_user_folder').hide();
    $('#select_tipo').on('change', function(){


        var val = $(this).val()
        if (val === 'Privado') {
            $('#select_user_folder').hide();
        } else{
            $('#select_user_folder').show();
            
        }

    });


    let calendar = false;
    $('#open_menu_calendar_responsive').click(function(){
        $('.contenedor_opciones_calendar').addClass('contenedor_opciones_calendar_on');
        //$('.background_nav_responsive').show();
        //calendar = true
    });

    $('#boton_close').click(function(){
        $('.contenedor_opciones_calendar').removeClass('contenedor_opciones_calendar_on');
       // $('.background_nav_responsive').hide();
       // calendar = false
    })

    // Datatables:
    $('#dataTables').DataTable();



}




function password(){


    $('#password_input').keyup(function(){
        $(this).addClass('red_bordercolor');

        var paswd=  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/;

        if (this.value.length < 0) {
             $(this).removeClass('green_bordercolor').removeClass('red_bordercolor');

             $('.min_text').removeClass('green_text').removeClass('red_text')
        }

        if(this.value.match(paswd)){
            $(this).removeClass('red_bordercolor').addClass('green_bordercolor');
        } else{
            $(this).removeClass('green_bordercolor').addClass('red_bordercolor');
        }



        // Check if had more than 8 character 
        if (this.value.length > 8) {
            $('.min_text').removeClass('red_text').addClass('green_text')
        } else{
            $('.min_text').addClass('red_text').removeClass('green_text')
        } 


        // Check if had one capital charactel
        if (/[A-Z]+/.test($("#password_input").val())) {
            $('.mayu_text').removeClass('red_text').addClass('green_text')
        } else{
            $('.mayu_text').addClass('red_text').removeClass('green_text')
        } 


        // Check if had one number charactel
        if (/[0-9]+/.test($("#password_input").val())) {
            $('.number_text').removeClass('red_text').addClass('green_text')
        } else{
            $('.number_text').addClass('red_text').removeClass('green_text')
        } 


        // Check if had one number charactel
        if (/[!"#$%&'()*+,-.:;<=>?@[\]^_`{|}~]+/.test($("#password_input").val())) {
            $('.caracter_text').removeClass('red_text').addClass('green_text')
        } else{
            $('.caracter_text').addClass('red_text').removeClass('green_text')
        } 


    })


    $('#confirm_password').keyup(function(){
        if ($('#password_input').val() == $('#confirm_password').val()) {
            $(this).removeClass('red_bordercolor').addClass('green_bordercolor');

            $('.Button_submit').removeClass('validacion_input');
             $('.Button_submit').prop( "disabled", false );



        } else{
            $(this).removeClass('green_bordercolor').addClass('red_bordercolor');
        } 
    })








}


function ajax(){



    $.ajaxSetup({
        headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
     });





    $('#registrar_user').click(function(e){
        e.preventDefault();

        var data = $('#form_registrar_user').serialize();
        var url = $('#form_registrar_user').attr('action');
        var method = $('#form_registrar_user').attr('method');


        $.ajax({
            method: method,
            url: url,
            data: data,
            beforeSend: function(){
                $('.loading').show();
            },
            success: function (mensaje) {

                $('.loading').hide();
                $('.contenedor_form').hide();
                $('.contenedor_respuesta').show();
                $('.contenedor_respuesta').html("");
                $('.contenedor_respuesta').append(mensaje).html();

            },
            error: function (xhr, status) {
                $('.loading').hide();
                alert("error");
            }
        }); 

    });




    $('.find_date').on('change', function(e){
        e.preventDefault()


        var data = $('#busqueda_date').serialize();
        var url = $('#busqueda_date').attr('action');
        var method = $('#busqueda_date').attr('method');


        $.ajax({
            method: method,
            url: url,
            data: data,
            beforeSend: function(){
                $('#contenedor_resultados_data').show()
                $("#contenedor_resultados_data").html("<p>Loading...</p>")
            },
            success: function (mensaje) {
                $("#contenedor_resultados_data").html("");
                var result = $('#contenedor_resultados_data').append(mensaje).html(); 
            },
            error: function (xhr, status) {
                $('.contenedor_resultados_data').show();
                $("#contenedor_resultados_data").html("<p>No se encuentra nada relacionado a la palabra</p>");
            }
        });


    });









    $('#search_all').keyup(function(e){
         e.preventDefault();

        let input = document.getElementById('search_all').value;
        let lenght = input.length;

        if (lenght >= 3) {
           // $value=$(this).val();
            var data = $('#busqueda_general').serialize();
            var url = $('#busqueda_general').attr('action');
            var method = $('#busqueda_general').attr('method');

             $.ajax({
                method: method,
                url: url,
                data: data,
                beforeSend: function(){
                    $('.contenedor_resultado_busqueda_rapida').show()
                    $("#busqueda_rapida").html("<p>Loading...</p>")
                },
                success: function (mensaje) {
                    $("#busqueda_rapida").html("");
                    var result = $('#busqueda_rapida').append(mensaje).html();
                },
                error: function (xhr, status) {
                    $('.contenedor_resultado_busqueda_rapida').show();
                    $("#busqueda_rapida").html("<p>No se encuentra nada relacionado a la palabra</p>");
                }
            });

        } else{
            $('.contenedor_resultado_busqueda_rapida').hide()
            $("#busqueda_rapida").html("")
        }


    });




    jQuery("#boton_formulario_cierre_registro_final").click(function (event) {

        //stop submit the form, we will post it manually.
        event.preventDefault();

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        // Get form
        var form = $('#formulario_cierre_registro_final')[0];

       // let data = $('#formulario_cierre_registro_final').serialize();
        let url = $('#formulario_cierre_registro_final').attr('action');
        let method = $('#formulario_cierre_registro_final').attr('method');

        // Create an FormData object
        var data = new FormData(form);

        // If you want to add an extra field for the FormData
        data.append("CustomField", "This is some extra data, testing");

        // disabled the submit button
        $("#boton_formulario_cierre_registro_final").prop("disabled", true);

        $.ajax({
            type: method,
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,

            beforeSend: function(){
                $('.loading').show();
            },


            success: function (data) {
                $('.loading').hide();

                console.log(data)
                jQuery.each(data.errors, function(key, value){
                    jQuery('.alert-danger').show();
                    jQuery('.error_respuesta').append('<p>'+value+'</p>');
                });



                jQuery.each(data.success, function(key, value){
                    console.log('<p>'+value+'</p>')
                    $('.contenedor_inicio').hide();
                    $('#conclusion_site').show();
                });

                $("#boton_formulario_cierre_registro_final").prop("disabled", false);

            },


            error: function (e) {
                $('.loading').hide();

                console.log("ERROR : ", e);
                $("#boton_formulario_cierre_registro_final").prop("disabled", false);

            }
        });

    });



}





function slick(){
    $(".slider_documento").slick({

          // normal options...
          infinite: false,

          slidesToShow: 4,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          arrows: false,

          responsive: [{

              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                infinite: true
              }

            }, {

              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                dots: false
              }

            }, {

              breakpoint: 300,
              settings: "unslick" // destroys slick

            }]
    });
}



function inputmask(){

    // InputMask Number phone:
    var phoneMask = $('.phone_mask');
    Inputmask({"mask": "(999) 999-9999",}).mask(phoneMask);

    const cedulaMask = $('.cedula_mask');
    Inputmask({"mask": "999-9999999-9"}).mask(cedulaMask);

}









/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////


function calendarpicker(){

    /*

    webshim.setOptions('forms-ext', {
        replaceUI: 'auto',
        types: 'date',
        date: {
            startView: 2,
            inlinePicker: true,
            classes: 'hide-inputbtns'
        }
    });
    webshim.setOptions('forms', {
        lazyCustomMessages: true
    });
    //start polyfilling
    webshim.polyfill('forms forms-ext');

    */

}

