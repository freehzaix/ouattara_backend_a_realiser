# Projet Backend à réaliser de El Hadj

En cours de réalisation...

### Déjà fait:
    - Guides
    - Faqs
    - Type_documents
    - Model_actes
    - Evenements
    - Activites
    - Compte_Rendus
    - Informations
    - Tampon
    - Conclusion

## Instructions
    - composer require yajra/laravel-datatables-oracle:"^11"
    - php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider"

<!-- DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>



    public function chargerListeDeDocumentLu($user_id){
        $user = User::find($user_id);
        
        // Récupérer la liste des documents envoyés et reçus par cet utilisateur
        $documentsEnvoyes = Document::where('user_id', $user_id)->where('estLuOuPas', 1)->get();
        $documentsRecus = Document::where('user_id_2', $user_id)->where('estLuOuPas', 1)->get();

        
        return Datatables::of($documentsEnvoyes->merge($documentsRecus))
        ->addColumn('numero', function () use (&$index) {
            $index++;
            return $index;
        })
        ->addColumn('img_pdf', function ($document) {
            $ok="";
            return '<button class="btn btn-danger btn-sm"><i class="fa-solid fa-file-pdf"></i></button>';
        })
        ->addColumn('titre', function ($document) {
            return $document->titre;
        })
        ->addColumn('nomfichier', function ($document) {
            return $document->nomfichier;
        })
        ->addColumn('date_cloture', function ($document) {
            return $document->date_cloture ? with(new Carbon($document->date_cloture))->format('d/m/Y') : '';
        })
        ->addColumn('destinataire', function ($document) use ($user_id){
            if ($document->user_id == $user_id) {
                $destinataireId = $document->user_id_2;
            } else {
                $destinataireId = $document->user_id;
            }
            $destinataire = User::find($destinataireId);
            return $destinataire->name.' '.$destinataire->prenom;
        })
        ->addColumn('etat', function ($document) use ($user_id) { 
            $etat = "";
            if($document->user_id == $user_id){//celui qui envoi le document
                $etat = "Document lu par la source";
            }else{// celui qui reçu
                $etat = "Document lu";
            }
            return $etat;
        })
        ->rawColumns(['img_pdf'])
        ->make(true);
    }

## jeanluc@freehzaix.com