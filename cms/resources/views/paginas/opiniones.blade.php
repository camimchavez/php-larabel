@extends('plantilla')

@section('content')

<div class="content-wrapper" style="min-height: 247px;">

   <!-- Content Header (Page header) -->
  <div class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1 class="m-0 text-dark">Opiniones</h1>
          
        </div><!-- /.col -->
        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>

            <li class="breadcrumb-item active">Opiniones</li>

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

            <div class="card-body">

              <table class="table table-bordered table-striped dt-responsive" id="tablaOpiniones" width="100%">

                <thead>

                  <tr>

                    <th width="10px">#</th>
                    <th>Artículo</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Foto</th>  
                    <th>Opinión</th> 
                    <th>Fecha Opinión</th> 
                    <th>Aprobación</th> 
                    <th>Administrador</th> 
                    <th>Respuesta</th>
                    <th>fecha Respuesta</th>                 
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
Modal Editar Opinion
======================================-->

@if (isset($status))
                 
  @if ($status == 200)
    
    @foreach ($opiniones as $key => $value)

      <div class="modal" id="editarOpinion">

        <div class="modal-dialog modal-md">

          <div class="modal-content">

            <form action="{{url('/')}}/opiniones/{{$value->id_opinion}}" method="post" enctype="multipart/form-data">

                @method('PUT')

                @csrf

              <div class="modal-header bg-info">
                <h4 class="modal-title">Editar Opinion</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                {{--  id_articulo --}}
                <div class="input-group mb-3">
                  <div class="input-group-append input-group-text">
                    <i class="fas fa-list-ul"></i>
                  </div>
                  @foreach ($articulos as $art)
                  @if ($art["id_articulo"] == $value["id_art"])
                  
                    <input id="" type="text" class="form-control" name="titulo_articulo" value="{{$art->titulo_articulo}}" required readonly>
                  @endif
                 
                @endforeach
                
                </div>

                {{--  nombre de quien realizo la opinion --}}
                <div class="input-group mb-3">
                  <div class="input-group-append input-group-text">
                    <i class="fas fa-user"></i>
                  </div>
        
                  <input id="" type="text" class="form-control" name="nombre_opinion" value="{{$value->nombre_opinion}}" required readonly>
                
                </div>

                {{-- correo de quien realizo la opinion --}}
                <div class="input-group mb-3">
                  <div class="input-group-append input-group-text">
                    <i class="fas fa-envelope"></i>
                  </div>
        
                  <input id="" type="text" class="form-control" name="correo_opinion" value="{{$value->correo_opinion}}" required readonly>
                
                </div>

                  {{-- foto del usuario realizo la opinion --}}
                <hr class="pb-2">

                <div class="form-group my-2 text-center">

                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Adjuntar foto
                    <input type="file" name="foto" id="">
                  </div>
                  <br>
                    @if ($value["foto_opinion"] == "")
                      <img src="{{url('/')}}/img/administradores/admin.png" class="previsualizarImg_foto img-fluid rounded-circle py-2 w-25">
                    @else
                      <img src="{{url('/')}}/{{$value["foto_opinion"]}}" class="previsualizarImg img-fluid rounded-circle py-2 w-25">
                  @endif
                 

                  <p class="help-block small">Dimensiones: 200px * 200px | Peso Max. 2MB | Formato: JPG o PNG

                  </p>
                </div>

                {{-- contenido de la opinion --}}
                <div class="input-group mb-3">
                 
                  <hr class="pb-2">
            
                  <textarea name="contenido_opinion" class="form-control" required readonly>{{$value->contenido_opinion}}</textarea>
                
                </div>
                  {{-- fecha de la opinion --}}
                  <div class="input-group mb-3">
                    <div class="input-group-append input-group-text">
                      <i class="fas fa-calendar"></i>
                    </div>
          
                    <input id="" type="datetime" class="form-control" name="fecha_opinion" value="{{$value->fecha_opinion}}" required readonly>
                  
                  </div>
                {{-- aprobacion de la opinion --}}
                <div class="input-group mb-3">
                  <div class="input-group-append input-group-text">
                    <i class="fas fa-check"></i>
                  </div>
        
                  <select class="form-control" name="aprobacion_opinion" required>
  
                    @if ($value["aprobacion_opinion"] == "1")
  
                    <option value="1" selected>Aprobado</option>
                    <option value="0">Por Aprobar</option>
  
                    @else
  
                    <option value="0" selected>Por Aprobar</option>
                    <option value="1">Aprobado</option>
  
                    @endif
                  </select>
                </div>

                {{-- Administrador que contesto la opinion --}}
                <div class="input-group mb-3">
                  <div class="input-group-append input-group-text">
                    <i class="fas fa-user"></i>
                  </div>
                  <select class="form-control" name="id_adm" required>
                  @foreach ($administradores as $element)
                    @if ($element["id"] == $value["id_adm"])
                        <option value="{{$value->id_adm}}" selected>{{$element->name}}</option>
                     @else
                     <option value="{{$element->id}}">{{$element->name}}</option>
                    @endif
                 
                  @endforeach
                  </select>
                </div>
                {{-- respuesta de la opinion --}}
                <div class="input-group mb-3">
                                
                  <hr class="pb-2">
                  @if ($value["respuesta_opinion"] != "")
                    <textarea name="respuesta_opinion" class="form-control" required>{{$value->respuesta_opinion}}</textarea>
                  @else
                    <textarea name="respuesta_opinion" class="form-control" required></textarea>

                  @endif
              
                </div>
           
                  {{-- fecha de la respuesta --}}
                  <div class="input-group mb-3">
                    <div class="input-group-append input-group-text">
                      <i class="fas fa-calendar"></i>
                    </div>
                    @if ($value["fecha_respuesta"] != NULL)
                    <input id="" type="datetime" class="form-control" name="fecha_respuesta" value="{{$value->fecha_respuesta}}">
                    @else
                    <input id="" type="datetime" class="form-control" name="fecha_respuesta">
                    @endif
                  </div>
                </div>
              <!-- Modal footer -->
              <div class="modal-footer d-flex justify-content-betwee">

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
    $("#editarOpinion").modal()
    </script>

  @endif

@endif
@if(Session::has("no-validacion"))

<script>

  notie.alert({
    type: 2,
    text: 'Hay campos no válidos en el formulario!',
    time:7
  })
</script>

@endif
@if(Session::has("error"))

<script>

  notie.alert({
    type: 3,
    text: 'Error en el gestor de Opiniones!',
    time:7
  })
  </script>
@endif
@if(Session::has("ok"))

<script>

  notie.alert({
    type: 1,
    text: 'La opinion ha sido eliminada correctamente.',
    time:7
  })
  </script>
@endif

@if(Session::has("ok-editar"))

<script>

  notie.alert({
    type: 1,
    text: 'La Opinion ha sido actualizada correctamente.',
    time:7
  })
  </script>
@endif
@if(Session::has("no-borrar"))

<script>

  notie.alert({
    type: 3,
    text: '¡Error al borrar la opinion!',
    time:7
  })
  </script>
@endif
@endsection