<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="cfdi a excel, cfdis, cfdi, convertir cfdi a excel, convertidor de cfdi a excel">
    <meta name="author" content="Daniel Alberto Vera Angulo">
    <title>ClouDav | CFDI a EXCEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 align-self-start">
                <h1 class="text-center mt-4 mb-0" style='font-family: "Roboto Mono", monospace; font-weight: 700;'> <span style="color: #0075BF;">CFDI</span> a <span style="color: #21a366;">EXCEL</span></h1>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center flex-column w-100">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 600px; width: 100%;">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width: 600px; width: 100%;">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width: 600px; width: 100%;">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <ul class="mb-0 p-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center align-content-center align-self-baseline">
                <div class="card" style="max-width: 600px; width: 100%;">
                    <div class="card-body">
                        <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="mb-3">
                                <label for="formFileLg" class="form-label">Nombre del archivo: (Opcional)</label>
                                <input name="filename" class="form-control form-control-lg" type="text">
                            </div>
                            <div class="mb-3">
                                <label for="formFileLg" class="form-label">Sube todos los cfdis (xml) necesarios</label>
                                <input name="files[]" class="form-control form-control-lg" id="formFileLg" type="file" multiple="multiple">
                            </div>
                            <div class="mb-3">
                                @if (empty($fileName))
                                    <button type="submit" value="submit" class="btn btn-success">Generar Excel</button>
                                @endif

                                @if (!empty($fileName))
                                    <a href="/storage/{{  $fileName . '.xlsx' }}" download="{{ $fileName . '.xlsx' }}" class="btn btn-info ml-4">Descargar Excel</a>

                                    <a href="{{ route('files.remove', ['filename' => $fileName]) }}" class="btn btn-danger ml-4">Reiniciar</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center align-self-end">
                <div class="mb-4">
                    Hecho con ❤️ en <a href="https://danielvera987.github.io/danielvera" target="_blank">ClouDav</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>
