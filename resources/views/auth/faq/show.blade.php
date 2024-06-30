@extends('layout.base')

@section('titlePage')
    FAQ
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
        @error('question')
            <div class="btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br />
        @enderror
        @error('reponse')
            <div class="btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br /> <br />
        @enderror

        @if (session('status'))
            <div class="btn btn-success swalDefaultSuccess">
                {{ session('status') }}
            </div>
            <br /> <br />
        @endif

        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modifier le FAQ - n°{{ $faq->id }}</h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 100px;">
                              
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <form method="POST" action="{{ route('faq.edit') }}">
                            @csrf
                            <div class="modal-body">
                                <input type="text" name="id" value="{{ $faq->id }}" style="display: none;">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="question">Question</label>
                                        <input type="text" class="form-control" name="question" id="question"
                                            placeholder="Question ?" value="{{ $faq->question }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="reponse">Réponse</label>
                                        <input type="text" class="form-control" name="reponse" id="reponse"
                                            placeholder="Réponse." value="{{ $faq->reponse }}">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="modal-footer justify-content-between">
                                
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

@endsection
