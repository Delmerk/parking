<?php

namespace App\Http\Livewire;

use App\Cajon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Tipo;

class CajonController extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tipo = 'Elegir', $description, $estatus = 'DISPONIBLE', $tipos; //tipos es para retornar el array
    // MANIPULAR REGISTROS DE la table
    public $selected_id, $search;
    public $action = 1, $pagination = 5;

    public function mount()
    {
    }
    // render devuelve vista, se ejecuta al momento de montar nuestro componente
    public function render()
    {
        // crear inforamación  o listar la info a pasar como tipos


        // $this->tipos=Tipo::all();
        $tipos = Tipo::all(); //sacamos todos los registros de la tabla tipos

        // validar si hemos seleccionado algun registro de la table para actuar y devolver info pertinente y en el else solo devolver 5 regsitro paginados
        if (strlen($this->search) > 0) { //el user tecleo-escribio algo en la caja de texto

            // cons devolver unir cajon con tipos 
            $info = Cajon::leftjoin('tipos as t', 't.id', 'cajones.tipo_id')->select('cajones.*', 't.description as tipo') //tipo seria el que usamos en el select en el wire:model
                ->where('cajones.description', 'like', '%' . $this->search . '%')
                ->orWhere('cajones.description', 'like', '%' . $this->search . '%')->paginate($this->pagination);
            $info =  [
                'info' => $info,
            ];
            return view('livewire.cajones.component', $info);
        } else {
            $info = Cajon::leftjoin('tipos as t', 't.id', 'cajones.tipo_id')->select('cajones.*', 't.description as tipo')->orderBy('cajones.id', 'desc')->paginate($this->pagination);
            $info =  [
                'info' => $info,
            ];
            // dd($info);
            // Laravel 8>
            // return view('livewire.cajones')->extends('layouts.template')->section('content');
            return view('livewire.cajones.component', $info);
        }
    }



    // POsiciona en la pagina número 1 de nuestro componente
    // paginado por busqueda
    public function updatingSearch()
    {
        $this->gotoPage(1);
    }

    //Movernos entre vistas, o formularios
    public function doAction($id)
    {
        $this->action = $id;
    }


    // LIMPIAR VARIABLES
    public function resetInput()
    {
        $this->description = '';
        $this->tipo = 'Elegir';
        $this->estatus = 'DISPONIBLE';
        $this->selected_id = null;
        $this->action = '1';
        $this->search = '';
    }


    // MOSTRAR LA INFO DEL REGISTRO A EDITAR
    public function edit($id)
    {
        $record = Cajon::findOrFail($id);
        $this->selected_id = $id;
        $this->tipo = $record->tipo_id;
        $this->description = $record->description;
        $this->estatus = $record->estatus;
        $this->action = 2;
    }



    public function storeOrUpdate()
    {
        // Que el seleccione o Elegir no sea valido
        $this->validate(
            [
                'tipo' => 'not_in:Elegir',
            ]
        );
        $this->validate(
            [
                'tipo' => 'required',
                'description' => 'required|min:4',
                'estatus' => 'required',
            ]
        );


        if ($this->selected_id <= 0) {
            $cajon = Cajon::create(
                [
                    'description' => $this->description,
                    'tipo_id' => $this->tipo,
                    'estatus' => $this->estatus
                ]
            );
        } else {
            $record = Cajon::find($this->selected_id);
            $record->update(
                [
                    'description' => $this->description,
                    'tipo_id' => $this->tipo,
                    'estatus' => $this->estatus,
                ]
            );
        }

        // emit se captan desde javascript para alerts, etc - emisión del evento
        // ESCUCHAR DESDE JS a PHP o lo contrario
        if ($this->selected_id) {
            $this->emit('msgok', 'Cajon actualizado con Éxito');
        } else {
            $this->emit('msgok', 'Cajon creado con Éxito');
        }
        $this->resetInput();
    }



    // DESTROY - ELIMINAR
    public function destroy($id)
    {
        if ($id) {
            $record = Cajon::where('id', $id);
            $record->delete();
            $this->resetInput();
            $this->emit('msgok', 'Registro eliminado con Éxito');
        }
    }
    protected $listeners = [
        'deleteRow' => 'destroy'
    ];
}
