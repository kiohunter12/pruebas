@extends('layouts.app')
@section('title','Registrar Expediente')
@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-medium mb-4">Registrar Expediente</h3>

            <form method="POST" action="{{ route('expedientes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Solicitante Nombre</label>
                        <input name="solicitante_nombre" value="{{ old('solicitante_nombre') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">DNI</label>
                            <input name="solicitante_dni" value="{{ old('solicitante_dni') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input name="solicitante_telefono" value="{{ old('solicitante_telefono') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="solicitante_email" type="email" value="{{ old('solicitante_email') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de trámite</label>
                        <select name="tipo_tramite" class="mt-1 block w-full border rounded px-3 py-2" required>
                            <option value="">Seleccione...</option>
                            @foreach($tiposTramite as $tt)
                                <option value="{{ $tt->id }}" @selected(old('tipo_tramite') == $tt->id)>{{ $tt->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gerencia</label>
                        <select name="gerencia_id" id="gerencia_id" class="mt-1 block w-full border rounded px-3 py-2" required>
                            <option value="">Seleccione...</option>
                            @foreach($gerencias as $g)
                                <option value="{{ $g->id }}" @selected(old('gerencia_id') == $g->id)>{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asunto</label>
                        <input name="asunto" value="{{ old('asunto') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Documentos</label>
                        <div id="document-list" class="space-y-2 mt-2">
                            <div class="flex gap-2 items-start document-item">
                                <input type="text" name="documentos[0][nombre]" placeholder="Nombre del documento" class="flex-1 border rounded px-3 py-2" required>
                                <select name="documentos[0][tipo_documento]" class="border rounded px-3 py-2">
                                    @foreach($tiposDocumento as $k => $v)
                                        <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="file" name="documentos[0][archivo]" class="border rounded px-3 py-2" required>
                                <button type="button" class="remove-doc text-red-600">Eliminar</button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" id="add-document" class="px-3 py-2 bg-gray-100 rounded">Agregar documento</button>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Registrar</button>
                        <a href="{{ route('expedientes.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function(){
        let idx = 1;
        document.getElementById('add-document').addEventListener('click', function(){
            const container = document.getElementById('document-list');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-start document-item';
            div.innerHTML = `
                <input type="text" name="documentos[${idx}][nombre]" placeholder="Nombre del documento" class="flex-1 border rounded px-3 py-2" required>
                <select name="documentos[${idx}][tipo_documento]" class="border rounded px-3 py-2">
                    @foreach($tiposDocumento as $k => $v)
                        <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                    @endforeach
                </select>
                <input type="file" name="documentos[${idx}][archivo]" class="border rounded px-3 py-2" required>
                <button type="button" class="remove-doc text-red-600">Eliminar</button>
            `;
            container.appendChild(div);
            idx++;
        });

        document.getElementById('document-list').addEventListener('click', function(e){
            if (e.target && e.target.classList.contains('remove-doc')) {
                const item = e.target.closest('.document-item');
                if (item) item.remove();
            }
        });
    })();
</script>
@endpush

@endsection
