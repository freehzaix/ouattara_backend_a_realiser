@extends('layout.base')

@section('titlePage')
    {{ $modelActe->nom_fichier }}
@endsection

@section('contenu')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('titlePage')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">@yield('titlePage')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <embed src="data:application/pdf;base64,{{ $document->fichier_scanner }}" type="application/pdf" width="100%" height="600px" />
                <a class="btn btn-info" href="{{ route('type-document.index') }}">Retour</a>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.content-wrapper -->


@endsection

