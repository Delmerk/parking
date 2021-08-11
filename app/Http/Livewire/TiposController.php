<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Tipo;

class TiposController extends Component
{
    use WithPagination;

    //public properties
    // Variables o propiedades públicas. Campos de table - filas-search-acciones y pagination
    public  $description, $image;            //campos de la tabla tipos
    public  $selected_id, $search;   //para búsquedas y fila seleccionada
    public  $action = 1;             //manejo de ventanas - movernos entre formularios editar o crear
    protected $paginationTheme = 'bootstrap';
    private $pagination = 1;         //paginación de tabla

    // public $create = 2;
    // public $edit = 2;
    // public $destroy = '';


    //primer método que se ejecuta al inicializar el componente
    // El primero en ejecutarse al renderizar el componente o iniciarse, sirve por ejemplo para cuando se crea la instancia de este controller nosotros en el mount poder definir variables ocn su información 
    public function mount()
    {
    }


    //método que se ejecuta después de mount al inciar el componente
    // se ejecuta despues del mount - index - accion principal.
    public function render()
    {
        //si la propiedad buscar tiene al menos un caracter, devolvemos el componente y le inyectamos los registros de una búsqueda con like y paginado a  5 
        if (strlen($this->search) > 0) {
            $info = Tipo::where('description', 'like', '%' .  $this->search . '%')
                ->paginate($this->pagination);
            return view('livewire.tipos.component', [
                'info' => $info,
            ]);
        } else {
            // caso contrario solo retornamos el componente inyectado con 5 registros
            return view('livewire.tipos.component', [
                'info' => Tipo::paginate($this->pagination),
            ]);
        }
    }

    // Busquedas con paginación - buscando dentro del paginado - para que no se vaya al inicio o se quede en una pagina.
    //permite la búsqueda cuando se navega entre el paginado
    public function updatingSearch(): void
    {
        $this->gotoPage(1);
    }


    // movernos entre formularios-ventanas   -ocultar o mostrar
    //activa la vista edición o creación
    public function doAction($action)
    {
        $this->action = $action;
    }


    // limpiar todas las variables
    //método para reiniciar variables
    private function resetInput()
    {
        $this->description = '';
        $this->selected_id = null;
        $this->action = 1;
        $this->search = '';
    }

    // Mostrar la info del registro a editar - Buscar o generar exception que se volcaria en las vistas
    //buscamos el registro seleccionado y asignamos la info a las propiedades
    public function edit($id)
    {
        $record = Tipo::findOrFail($id);
        $this->description = $record->description;
        $this->selected_id = $record->id;
        $this->action = 2;
    }


    //método para registrar y/o actualizar info
    public function StoreOrUpdate()
    {
        //validación campos requeridos
        $this->validate([
            'description' => 'required|min:4' //validamos que descripción no sea vacío o nullo y que tenga al menos 4 caracteres
        ]);

        //valida si existe otro cajón con el mismo nombre (edicion de tipos)
        if ($this->selected_id > 0) {
            $existe = Tipo::where('description', $this->description)
                ->where('id', '<>', $this->selected_id)
                ->select('description')
                ->get();

            if ($existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Tipo');
                $this->resetInput();
                return;
            }
        } else {
            //valida si existe otro cajón con el mismo nombre (nuevos registros)
            $existe = Tipo::where('description', $this->description)
                ->select('description')
                ->get();

            if ($existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Tipo');
                $this->resetInput();
                return;
            }
        }










        if ($this->selected_id <= 0) {
            //creamos el registro
            $tipo =  Tipo::create([
                'description' => $this->description
            ]);
            if ($this->image) {
                $image = $this->image;
                $fileName = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $moved = \Image::make($image)->save('images/' . $fileName);

                if ($moved) {
                    $tipo->imagen = $fileName;
                    $tipo->save();
                }
            }
        } else {
            //buscamos el tipo
            $record = Tipo::find($this->selected_id);
            //actualizamos el registro
            $record->update([
                'description' => $this->description
            ]);
            if ($this->image) {
                $image = $this->image;
                $fileName = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $moved = \Image::make($image)->save('images/' . $fileName);

                if ($moved) {
                    $record->imagen = $fileName;
                    $record->save();
                }
            }
        }


        //enviamos feedback al usuario
        if ($this->selected_id)
            session()->flash('message', 'Tipo Actualizado');
        else
            session()->flash('message', 'Tipo Creado');

        //limpiamos las propiedades
        $this->resetInput();
    }


    //escuchar eventos y ejecutar acción solicitada
    protected $listeners = [
        'deleteRow'     => 'destroy',
        'fileUpload' => 'handleFileUpload'
    ];

    public function handleFileUpload($imageData)
    {
        $this->image = $imageData;
    }


    //método para eliminar un registro dado
    public function destroy($id)
    {
        if ($id) { //si es un id válido
            $record = Tipo::where('id', $id); //buscamos el registro
            $record->delete(); //eliminamos el registro
            $this->resetInput(); //limpiamos las propiedades
        }
    }
}
