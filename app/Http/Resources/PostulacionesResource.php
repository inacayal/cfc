<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;

class PostulacionesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected $formattedCollection;
    public function __construct(Collection $collect){
        $data = [];
        $meses = [
            "",
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
            ];
        foreach ($collect as $item){
            $casting = [
                "Datos de la beca" => [
                    "Disciplina de la Beca"=>$item->disciplina->descripcion,
                    "Categoria de la beca"=>$item->idCategoria->descripcion,
                    "Mes de inicio de la beca"=> $meses[(int)$item->inicio_beca],
                    "Duración de la beca" => $item->duracion_beca." meses",
                    "Lugar de Formacion"=> ucwords($item->lugar_formacion),
                    "Tipo de formación"=> ucwords($item->tipo_formacion)
                  ],
                  "Plan de Transferencia" =>[
                    "Mes de inicio de transferencia" => $meses[$item->inicio_transferencia],
                    "Duracion de la Transferencia" => $item->duracion_transferencia." meses",
                    "Provincia de la transferencia" => $item->lugarTransferencia->nombre,
                    "Localidad de la transferencia" => $item->localidad_transferencia,
                    "Nombre del plan de transferencia" => $item->nombre_transferencia,
                    "Descripcion de la transferencia" => $item->descripcion_transferencia,
                  ],
                  "Institución / Docente Particular" =>[
                    "Nombre del tutor / Institución" => $item->nombre_tutor,
                    "Correo del tutor / Institución"=>$item->correo_institucion,
                    "Teléfono del tutor / Institución" => $item->telefono_institucion,
                    "Provincia del tutor / Institución" => $item->provincia->nombre,
                    "Localidad del tutor / Institución" => $item->localidad,
                    "Código Postal del tutor / Institución" => $item->codigo_postal,
                    "Breve descripción de la formación" => $item->descripcion_formacion
                  ],
                  "Plan de Gastos" =>[
                    "Materiales"=> $item->materiales,
                    "Viaticos"=> $item->viaticos,
                    "Alojamiento" => $item->alojamiento,
                    "Equipamento"=> $item->equipamento,
                    "Pasajes" => $item->pasajes,
                    "Alquiler" => $item->alquiler,
                    "Honorarios"=> $item->honorarios,
                    "Otros"=> $item->otros,
                    "descripcion_Materiales"=> $item->descripcion_materiales,
                    "descripcion_Viaticos"=> $item->descripcion_viaticos,
                    "descripcion_Alojamiento"=> $item->descripcion_alojamiento,
                    "descripcion_Equipamento"=> $item->descripcion_equipamento,
                    "descripcion_Pasajes"=> $item->descripcion_pasajes,
                    "descripcion_Alquiler"=> $item->descripcion_alquiler,
                    "descripcion_Honorarios"=> $item->descripcion_honorarios,
                    "descripcion_Otros"=> $item->descripcion_otros,
                    "Total"=> $item->total
                    ]
                ];
            $data[$item->id]=$casting;
        }
        $this->formattedCollection = $data;
    }
    public function formatCollection () {
        return $this->formattedCollection;
    }
}