@extends('app')

@section('title', 'Padron de Nacimientos')

@section('content')


    <div class="container">
        <h3>Cargar archivo .txt</h3>

        <form action="{{ route('subirArchivo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="archivo">Selecciona el archivo .txt</label>
                <input type="file" name="archivo" id="archivo" class="form-control" accept=".txt" required>
            </div>

            <button type="submit" class="btn btn-primary">Cargar</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>


@endsection

@push('scripts')
    <script>
        // TODO script 
    </script>
@endpush