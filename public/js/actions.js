function setDatatableTriggers() {
    $('#datatable').attr('style', 'width: 100% !important; margin: 0 !important;');

    $('div.dataTables_filter').find('.btn-refresh').remove();
    $('<span class="fa fa-refresh btn-refresh" style="margin: 0 5px; cursor: pointer; transition: transform 100s;"></span>').appendTo('div.dataTables_filter');
    
    $(".btn-refresh").on("click", function() {
        $(this).css('transform', 'rotate(72000deg)');
        $("#datatable").dataTable().fnDraw();
    });

    $(".btn-new").on('click', function() {
        var obj = $("#datatable").attr('data-content');
        
        obj = obj.charAt(0).toUpperCase() + obj.substr(1);

        $('#modal-default').find('.modal-title').html("<strong>Adicionar " + obj + "</strong>");

        var content;
        content = '';
        if(obj.toLowerCase() == 'categoria') {
            content += '<div class="form-group">';
                content += '<label for="nome-categoria">Nome</label>';
                content += '<input id="nome-categoria" data-tag="input" name="nome" maxlength="50" class="form-control" />';
            content += '</div>';
        } else if(obj.toLowerCase() == 'produto') {
            content += '<div class="form-group">';
                content += '<label for="categoria-produto">Categoria</label>';
                content += '<select id="categoria-produto" data-input-type="select2" data-tag="input" name="id_categoria" class="form-control"></select>';                
            content += '</div>';

            content += '<div class="row">';
                content += '<div class="form-group col-sm-6">';
                    content += '<label for="nome-produto">Nome</label>';
                    content += '<input id="nome-produto" data-tag="input" name="nome" maxlength="50" class="form-control" />'
                content += '</div>';

                content += '<div class="form-group col-sm-6">';
                    content += '<label for="nome-produto">Marca</label>';
                    content += '<input id="marca-produto" data-tag="input" name="marca" maxlength="100" class="form-control" />'
                content += '</div>';
            content += '</div>';

            content += '<div class="row">';
                content += '<div class="form-group col-sm-4">';
                    content += '<label for="preco-produto">Preço</label>';
                    content += '<input type="number" id="preco-produto" data-tag="input" name="preco" class="form-control" />'
                content += '</div>';

                content += '<div class="form-group col-sm-8">';
                    content += '<label for="imagem-produto">Imagem</label>';
                    content += '<input type="file" accept=".jpeg,.jpg,.png,.bmp,.gif,.svg" id="image-produto" data-tag="input" name="imagem" class="form-control" />'
                content += '</div>';
            content += '</div>';

            content += '<div class="form-group">';
                content += '<label for="descricao-produto">Descrição</label>';
                content += '<textarea id="descricao-produto" name="descricao" data-tag="input" class="form-control" style="resize: vertical;"></textarea>';
            content += '</div>';
        } else if(obj.toLowerCase() == 'usuário') {
            content += '<div class="row">';
                content += '<div class="form-group col-sm-8">';
                    content += '<label for="nome-usuario">Nome</label>';
                    content += '<input id="nome-usuario" data-tag="input" name="name" maxlength="190" class="form-control" required />';
                content += '</div>';

                content += '<div class="form-group col-sm-4">';
                    content += '<label for="role-usuario">Cargo</label>';
                    content += '<select id="role-usuario" data-input-type="select2" data-tag="input" name="id_role" class="form-control"></select>';
                content += '</div>';
            content += '</div>';

            content += '<div class="form-group">';
                content += '<label for="email-usuario">Email</label>';
                content += '<input type="email" id="email-usuario" data-tag="input" name="email" maxlength="190" class="form-control" required />';
            content += '</div>';

            content += '<div class="row">';
                content += '<div class="form-group col-sm-6">';
                    content += '<label for="senha-usuario">Senha</label>';
                    content += '<input id="senha-usuario" type="password" data-tag="input" name="password" maxlength="190" class="form-control" required />';
                content += '</div>';

                content += '<div class="form-group col-sm-6">';
                    content += '<label for="senha-confirmacao-usuario">Confirmar Senha</label>';
                    content += '<input id="senha-confirmacao-usuario" type="password" data-tag="input" name="password_confirmation" maxlength="190" class="form-control" required />';
                content += '</div>';
            content += '</div>';
        }
        
        $('#modal-default').find('.modal-body').html(content);

        $('#modal-default').find('.modal-footer').find('.btn-action').html("Adicionar");
        
        $('#modal-default').find('.modal-footer .btn-action').attr("action", $(this).attr('action'));
        $('#modal-default').find('.modal-footer .btn-action').attr("method", "post");
        
        $('#categoria-produto').select2({
            placeholder: "Selecione uma categoria",
            allowClear: true,
            width: '100%',
            ajax: {
            url: "./categorias/search",
                dataType: 'json',
                quietMillis: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                },
                processResults: function (data) {                    
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nome,                                
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });

        $('#role-usuario').select2({
            placeholder: "Selecione um cargo",
            allowClear: true,
            width: '100%',
            ajax: {
            url: "./roles/search",
                dataType: 'json',
                quietMillis: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }
                
                    // Query parameters will be ?search=[term]&page=[page]
                    return query;
                },
                processResults: function (data) {                    
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,                                
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });
    });

    $('.btn-delete').on('click', function() {
        var obj = $("#datatable").attr('data-content');

        obj = obj.charAt(0).toUpperCase() + obj.substr(1);

        if(obj.slice(-1) == "a") {
            var pron = "essa";
        } else {
            var pron = "este";
        }

        $('#modal-default').find('.modal-title').html("<strong>Confirmação de Deletamento</strong>");

        $('#modal-default').find('.modal-body').html("<p>Tem certeza que deseja deletar " + pron + " " + obj + "?</p>");

        $('#modal-default').find('.modal-footer').find('.btn-action').html("Deletar");
        
        $('#modal-default').find('.modal-footer .btn-action').attr("action", $(this).attr('action'));
        $('#modal-default').find('.modal-footer .btn-action').attr("method", "delete");
    });

    $('.btn-edit').on('click', function() {
        var route = $(this).attr('action');

        var obj = $("#datatable").attr('data-content');
        
        obj = obj.charAt(0).toUpperCase() + obj.substr(1);

        $('#modal-default').find('.modal-title').html("<strong>Alteração de " + obj + "</strong>");

        $.get(route, function(data) {   
            if(obj.toLowerCase() == 'produto') {         
                var content = '';
                content += '<div class="form-group">';
                    content += '<label for="categoria-produto">Categoria</label>';
                    content += '<select id="categoria-produto" data-input-type="select2" data-tag="input" name="id_categoria" class="form-control"></select>';                
                content += '</div>';

                content += '<div class="row">';
                    content += '<div class="form-group col-sm-6">';
                        content += '<label for="nome-produto">Nome</label>';
                        content += '<input id="nome-produto" data-tag="input" name="nome" maxlength="50" class="form-control" value="' + data.nome + '" />';
                    content += '</div>';
                    content += '<div class="form-group col-sm-6">';
                        content += '<label for="nome-produto">Marca</label>';
                        content += '<input id="marca-produto" data-tag="input" name="marca" maxlength="100" class="form-control" value="' + data.marca + '" />';
                    content += '</div>';
                content += '</div>';
                
                content += '<div class="row">';
                    content += '<div class="form-group col-sm-4">';
                        content += '<label for="preco-produto">Preço</label>';
                        content += '<input type="number" id="preco-produto" data-tag="input" name="preco" class="form-control" value="' + data.preco + '" />';
                    content += '</div>';
                    content += '<div class="form-group col-sm-8">';
                        content += '<label for="imagem-produto">Imagem</label>';
                        content += '<input type="file" accept=".jpeg,.jpg,.png,.bmp,.gif,.svg" id="image-produto" data-tag="input" name="imagem" class="form-control" value="' + data.imagem + '" />'
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group">';
                    content += '<label for="descricao-produto">Descrição</label>';
                    content += '<textarea id="descricao-produto" name="descricao" data-tag="input" class="form-control" style="resize: vertical;">' + ((data.descricao != null) ? data.descricao : '') + '</textarea>';
                content += '</div>';                
                
                $('#modal-default').find('.modal-body').html(content);
                
                $('#categoria-produto').select2({
                    placeholder: "Selecione uma categoria",
                    allowClear: true,
                    width: '100%',
                    ajax: {
                    url: "./categorias/search",
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                page: params.page || 1
                            }
                        
                            // Query parameters will be ?search=[term]&page=[page]
                            return query;
                        },
                        processResults: function (data) {                    
                            // Tranforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.nome,                                
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });

                $("#categoria-produto").empty().append('<option value="' + data.categoria.id + '">' + data.categoria.nome + '</option>').val(data.categoria.id).trigger('change');
            } else if(obj.toLowerCase() == 'categoria') {
                var content = '';
                content += '<div class="form-group">';
                    content += '<label for="nome-categoria">Nome</label>';
                    content += '<input id="nome-categoria" data-tag="input" name="nome" maxlength="50" class="form-control" value="' + data.nome + '" />';
                content += '</div>';

                $('#modal-default').find('.modal-body').html(content);
            }

        });
        
        $('#modal-default').find('.modal-footer').find('.btn-action').html("Alterar");
            
        $('#modal-default').find('.modal-footer .btn-action').attr("action", $(this).attr('action'));
        $('#modal-default').find('.modal-footer .btn-action').attr("method", "put");
    });

    $('.btn-action').on('click', function(e) {
        e.preventDefault();
        
        var me = $(this);
        
        if (me.data('requestRunning')) {
            return;
        }
        
        $('#modal-default').modal('toggle');

        me.data('requestRunning', true);

        var route = $(this).attr('action');
        var token = $('meta[name="csrf-token"]').attr("content");
        var method = $(this).attr('method');
        var dataToSend = {};
        var imageObj;

        
        $(this).closest('.modal').find('[data-tag="input"]').each(function(index) {
            var value = $(this).val();
            var thisname = $(this).attr('name');            

            if(thisname == 'imagem') {
                if(value != "") {
                    uploadImage($(this));
                    dataToSend[thisname] = $(this)[0].files[0].name.toLowerCase();
                } else {
                    dataToSend[thisname] = $(this).attr('value');
                }
            } else {
                dataToSend[thisname] = value;
            }            
        });        
                
        $.ajax({
            url: route,
            type: method,
            data: {_method: method, _token : token, data: dataToSend},
            success: function(msg) {
                $("input").css('border-color', '');
                if(msg.fail) {
                    $('#modal-default .modal-body .error-msg').remove();

                    for(var key in msg.errors) {
                        $("input[name='" + key + "']").css('border-color', 'red');

                        for(var msgkey in msg.errors[key]) {                            
                            $('#modal-default .modal-body').append('<p class="text-red text-bold error-msg">' + msg.errors[key][msgkey] + '</p>');
                        }
                    }

                    $('#modal-default').modal('toggle');
                } else {
                    $("#datatable").dataTable().fnDraw();
                }
            },
            error: function(error) {
                $("input").css('border-color', '');
                $('#modal-default').modal('toggle');
            },
            complete: function() {
                me.data('requestRunning', false);                
            }
        });
    });

    $('.image-see').on('click', function() {
        var getUrl = window.location;
        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

        var fileurl = baseUrl + '/public/' + $(this).attr('data-filedir');

        $('#modal-image').find('.modal-title').html("<strong>" + $(this).attr('data-filedir').split('/').pop() + "</strong>");

        $('#modal-image').find('.modal-body').html("<img style='width: 100%;' src='" + fileurl + "'></img>");
    });

    function uploadImage(me) {
        var file_data = $(me).prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
        });                

        var filename;
        $.ajax({
            url: "./produtos/uploadImage",
            data: form_data,
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.fail) {                            
                    alert(data.errors['file']);                    
                }
                else {                    
                    return data;
                }
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);                                                   
            }
        });        
    }
}