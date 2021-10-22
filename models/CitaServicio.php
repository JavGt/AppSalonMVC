<?php 

namespace Model;

class CitaServicio extends ActiveRecord {
    // tablas y columnas de la BD
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = [
        'id',
        'servicioId',
        'citaId'
    ];

    public $id;
    public $servicioId;
    public $citaId;

    public function __construct( $args = [] ) {
        $this->id = $args['id'] ?? NULL ;
        $this->servicioId = $args['servicioId'] ?? '';
        $this->citaId = $args['citaId'] ?? '';
    }   
}