@extends('plantilla')

@section('content')

<div class="content-wrapper" style="min-height: 247px;">

  <!-- Content Header (Page header) -->
  <div class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1 class="m-0 text-dark">Anuncios</h1>

        </div><!-- /.col -->

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>

            <li class="breadcrumb-item active">Anuncios</li>

          </ol>

        </div><!-- /.col -->

      </div><!-- /.row -->

    </div><!-- /.container-fluid -->

  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">

    <div class="container-fluid">

      <div class="row">

        <div class="col-lg-12">

          <div class="card card-primary card-outline">

            <div class="card-header">

              <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#crearAnuncio">Crear nuevo anuncio</button>

            </div>

            <div class="card-body">

              <table class="table table-bordered table-striped dt-responsive" id="tablaAnuncios" width="100%">

                <thead>

                  <tr>

                    <th width="10px">#</th>
                    <th width="500px">Código</th>
                    <th>Página</th>
                    <th>Acciones</th>         

                  </tr> 

                </thead>  

              </table>

    
              </div>

            </div>

          </div>         
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

<!--=====================================
Crear Anuncio
======================================-->

<div class="modal" id="crearAnuncio">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <form action="{{url('/')}}/anuncios" method="post" enctype="multipart/form-data">

        @csrf

        <div class="modal-header bg-info">
          <h4 class="modal-title">Crear Anuncio</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

        
          {{-- Pagina del anuncio --}}
                    
          <div class="input-group mb-3">

            <div class="input-group-append input-group-text">
              <i class="fas fa-list-ul"></i>
            </div>

            <input type="text" class="form-control" name="pagina_anuncio" placeholder="Ingrese la pagina del anuncio" required> 

          </div> 

          {{-- codigo anuncio --}}
                  
         

          <hr class="pb-2">

          <textarea name="codigo_anuncio" class="form-control summernote-articulos" required></textarea>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-between">

          <div>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>

          <div>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>

        </div>

      </form>

    </div>

  </div>

</div>

<!--=====================================
Modal Editar Anuncio
======================================-->

@if (isset($status))
                 
  @if ($status == 200)
    
    @foreach ($anuncios as $key => $value)

      <div class="modal" id="editarAnuncio">

      <div class="modal-dialog modal-lg">

        <div class="modal-content">

           <form action="{{url('/')}}/anuncios/{{$value->id_anuncio}}" method="post" enctype="multipart/form-data">

              @method('PUT')

              @csrf

            <div class="modal-header bg-info">
              <h4 class="modal-title">Editar Anuncio</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

               
              {{-- Pagina del anuncio --}}
                        
              <div class="input-group mb-3">

                <div class="input-group-append input-group-text">
                  <i class="fas fa-list-ul"></i>
                </div>

                <input type="text" class="form-control" name="pagina_anuncio" value="{{$value->pagina_anuncio}}" required> 

              </div> 

              {{-- codigo anuncio --}}
                      
            

              <hr class="pb-2">

              <textarea name="codigo_anuncio" class="form-control summernote-editar-articulo" required>{{$value->codigo_anuncio}}</textarea>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-between">

              <div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              </div>

              <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>

            </div>

          </form>

        </div>

      </div>

    </div>

  @endforeach

  <script>
  $("#editarAnuncio").modal()
  </script>

  @endif

@endif

@if (Session::has("ok-crear"))

  <script>
      notie.alert({ type: 1, text: '¡El anuncio ha sido creado correctamente!', time: 10 })
 </script>

@endif



@if (Session::has("no-validacion"))

  <script>
      notie.alert({ type: 2, text: '¡Hay campos no válidos en el formulario!', time: 10 })
 </script>

@endif

@if (Session::has("error"))

  <script>
      notie.alert({ type: 3, text: '¡Error en el gestor de anuncios!', time: 10 })
 </script>

@endif

@if (Session::has("ok-editar"))

  <script>
      notie.alert({ type: 1, text: '¡El anuncio ha sido actualizado correctamente!', time: 10 })
 </script>

@endif

@endsection