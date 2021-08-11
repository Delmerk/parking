<div class="widget-content-area">
    <div class="widget-one">
        @include('common.messages')

        <div class="row">
            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <label for="">Nombre</label>
                {{-- muchoo de lo que se hace dentro de livewire son peticiones al servidor. pero podemos configurar para que las peticiones-request sean cuando pierda el foco una caja y eso se hace con .lazy --}}
                <input type="text" wire:model.lazy="description" class="form-control" placeholder="Nombre">
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <label for="">Tipo</label>
                <select name="" id="" wire:model="tipo">
                    <option value="Elegir" disabled>Elegir</option>
                    @foreach ($tipo as $t)
                        <option value="{{ $t->id }}">{{ $t->description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <label for="Estatus">Estatus</label>
                <select name="" id="" wire:model="estatus">
                    <option value="DISPONIBLE">DISPONIBLE</option>
                    <option value="OCUPADO">OCUPADO</option>
                </select>
            </div>

            <div class="row">

            </div>

            <div class="col-lg-5 mt-2 text-left">
                <button type="button" class="btn btn-dark mr-1" wire:click="doAction(1)"><i
                        class="mbri-left"></i>Regresar</button>
            </div>

            <div class="col-lg-5 mt-2 text-left">
                {{-- Si hacemos un submit dentro de una accion para prevenir el flujo del proceso, por defecto del boton le ponemos un .prevent  y con esto se evita que se ejecute el flujo del proceso por defecto que es el submit y se refresque la pagina --}}
                <button type="button" class="btn btn-primary ml-2" wire:click.prevent="StoreOrUpdate()"><i
                        class="mbri-success"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>
