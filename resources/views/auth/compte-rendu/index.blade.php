@extends('layout.base')

@section('titlePage')
    Compte Rendus
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
        @error('nom_fichier')
            <div class="ml-5 btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br />
        @enderror
        @error('fichier_scanner')
            <div class="ml-5 mt-3 btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br /> <br />
        @enderror
        @error('empreinte_fichier')
            <div class="ml-5 mt-3 btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br /> <br />
        @enderror
        @error('documentId')
            <div class="ml-5 mt-3 btn btn-danger swalDefaultError">
                {{ $message }}
            </div>
            <br /> <br />
        @enderror

        @if (session('status'))
            <div class="ml-5 btn mt-3 btn-success swalDefaultSuccess">
                {{ session('status') }}
            </div>
            <br /> <br />
        @endif

        @if (session('warning'))
            <div class="ml-5 mt-3 btn btn-warning swalDefaultSuccess">
                {{ session('warning') }}
            </div>
            <br /> <br />
        @endif

        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des compte rendus</h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-default">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fichier scanné</th>
                                    <th>Date de conseil</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compteRendus as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ str_replace(' ', '_', $item->nom_fichier) }}</td>
                                        <td><span class="tag tag-success">{{ $item->created_at->locale('fr')->diffForHumans() }}</span></td>
                                        <td>
                                            <a href="{{ route('compte-rendu.show', $item->id) }}" type="button"
                                                class="btn btn-info btn-sm" target="_blank">Afficher</a>
                                            <!-- Bouton pour ouvrir le modal de confirmation -->
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $item->id }}">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal de confirmation de suppression pour chaque document -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirmation de
                                                        suppression</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer ce compte rendu ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Annuler</button>
                                                    <!-- Utilisation d'un formulaire pour la suppression -->
                                                    <form action="{{ route('compte-rendu.delete', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </tbody>
                        </table>
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

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un compte rendu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('compte-rendu.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nom_fichier">Nom du fichier</label>
                                <input type="text" class="form-control" name="nom_fichier" id="nom_fichier"
                                    placeholder="Donnez un nom au fichier">
                            </div>

                            <div class="form-group">
                                <label for="fichier_scanner">Fichier scanné</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="fichier_scanner"
                                            id="fichier_scanner">
                                        <label class="custom-file-label" for="fichier_scanner">Choisir un doculent
                                            PDF</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <script>
        document.getElementById('confirmDelete').addEventListener('click', function() {
            // Soumettre le formulaire de suppression après confirmation
            document.getElementById('deleteForm').submit();
        });
    </script>
@endsection