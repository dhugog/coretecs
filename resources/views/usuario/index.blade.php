@extends('adminlte::page')

@section('title', 'Categorias')

@section('components')
    @include('components.modal')    
@endsection

@section('content')
    <div class="box box-danger">
        <div class="box-header">
            <div class="row">
                <div class="col-sm-6 pull-left">
                    <h3><span class="fa fa-fw fa-folder-open-o"></span> Usuários</h3>
                </div>
                <div class="col-sm-6 pull-right text-right">
                    <button class="btn btn-success btn-new" action="{{ route('usuarios.store') }}" data-toggle="modal" data-target="#modal-default" style="margin-top: 20px; margin-bottom: 10px;"><span class="fa fa-plus-circle"></span> Novo</button>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered" id="datatable" data-content="usuário">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Criado Em</th>
                        <th>Atualizado Em</th>
                        <th>Opções</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ URL::asset('js/actions.js') }}"></script>

    <script>
        $(function() {            
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                stateDuration: 0,
                rowReorder: false,
                searching: true,
                info: true,
                searchDelay: 350,
                pageLength: 10,
                lengthChange: true,
                ajax: "{!! route('usuarios.data') !!}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role.name', name: 'role.name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: 'auto' }
                ],
                // Quando terminar de carregar os dados
                "drawCallback": function() {
                    setDatatableTriggers();
                }
            });
        });
    </script>
@endpush