<?php

namespace App\Http\Livewire;
// manejar para almacenar el id del user que esta haciendo el movimiento
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Cajas;

class CajaController extends Component
{
    use WithPagination;

    public $tipo = 'Elegir', $concepto, $monto, $comprobante;
    public $selected_id, $search;
    public $action = 1, $pagination = 5;

    public function render()
    {
        // si se escribe algo search
        if (strlen($this->search) > 0) {


            $info = Cajas::leftjoin('users as u', 'u.id', 'cajas.user_id')
                ->select('cajas.*', 'u.name')
                ->where('u.name', 'like' . '%' . $this->search . '%')
                ->orwhere('cajas.concepto', 'like', '%' . $this->search . '%')
                ->paginate($this->pagination);
            return view('livewire.cajas.component', [
                'info' => $info
            ]);
            // return view('livewire.cajas.component', [
            //     'info' => Cajas::where('tipo', 'like', '%' . $this->search . '%')
            //         ->orWhere('concepto', 'like', '%' . $this->search . '%')
            //         ->paginate($this->pagination),
            // ]);
        } else {
            /* // caso contrario solo retornamos el componente importado con 5 registros
            $cajas = Caja::leftjoin('users as u', 'u.id', 'cajas.user_id')
            ->select('cajas.*','u.name')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);
            return view('livewire.movimientos.component', [
                'info' => $cajas
            ]); */

            // UNIMOS LA TABLA DE CAJAS CON USUARIOS 
            $cajas = Cajas::leftjoin('users as u', 'u.id', 'cajas.user_id')
                ->select('cajas.*', 'u.name')
                ->orderBy('id', 'desc')
                ->paginate($this->pagination);

            return view('livewire.cajas.component', [
                'info' => $cajas
            ]);
        }
    }



    //método paginado para realizar la búsqueda de los registros
    public function updatingSearch()
    {
        $this->gotoPage(1);
    }

    //método para realizar la accion deseada crear editar o eliminar o modificar
    public function doAction($action)
    {
        $this->resetInput();
        $this->action = $action;
    }

    //método para limpiar las propiedades 
    public function resetInput()
    {
        $this->concepto = '';
        $this->tipo = 'Elegir';
        $this->monto = '';
        $this->comprobante = '';
        $this->selected_id = null;
        $this->action = 1;
        $this->search = '';
    }

    //método editar
    public function edit($id) //pasamos el valor del id del registro
    {
        $record = Cajas::find($id);
        $this->selected_id = $id;
        $this->tipo = $record->tipo;
        $this->concepto = $record->concepto;
        $this->monto = $record->monto;
        $this->comprobante = $record->comprobante;
        $this->action = 3;
    }

    //método para almacenar o actualizar
    public function StoreOrUpdate()
    {
        $this->validate([
            'tipo' => 'not_in:Elegir'
        ]);

        $this->validate([
            'tipo' => 'required',
            'monto' => 'required',
            'concepto' => 'required'
        ]);

        // condicional para crear o actualizar en Tabla Cajas
        if ($this->selected_id <= 0) {
            $record = Cajas::create([
                'monto' => $this->monto,
                'tipo' => $this->tipo,
                'concepto' => $this->concepto,
                'user_id' => Auth::user()->id // auth()->user()->id
            ]);

            if ($this->image1) {
                $image = $this->image1;
                $fileName = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $moved = \Image::make($image)->save('images/movs/' . $fileName);

                if ($moved) {
                    $record->comprobante = $fileName;
                    $record->save();
                }
            }
        } else {
            $record = Cajas::find($this->selected_id);
            dd($record);
            $record->update([
                'monto' => $this->monto,
                'tipo' => $this->tipo,
                'concepto' => $this->concepto,
                'user_id' => Auth::user()->id
            ]);
            if ($this->image1) {
                $image = $this->image1;
                $fileName = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $moved = \Image::make($image)->save('images/movs/' . $fileName);

                if ($moved) {
                    $record->comprobante = $fileName;
                    $record->save();
                }
            }
        }

        if ($this->selected_id) {
            $this->emit('msgok', 'Movimiento de Caja Actualizado con Éxito');
        } else {
            $this->emit('msgok', 'Movimiento de Caja fué creado con Éxito');
        }

        $this->resetInput();
    }

    //escuchar eventos y ejecutar acción solicitada de eliminar
    protected $listeners = [
        'fileUpload' => 'handleFileUpload',
        'deleteRow' => 'destroy'
    ];

    public function handleFileUpload($imageData)
    {
        $this->imagen1 = $imageData;
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cajas::where('id', $id);
            $record->delete();
            $this->resetInput();
            $this->emit('msgok', 'El registro fue eliminado con éxito');
        }
    }
}
