<?php
class Reserva
{
    public $id;
    public $idUsuario;
    public $idTramo;
    public $fecha;

    public function __construct()
    {
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of idTramo
     */
    public function getIdTramo()
    {
        return $this->idTramo;
    }

    /**
     * Set the value of idTramo
     */
    public function setIdTramo($idTramo): self
    {
        $this->idTramo = $idTramo;

        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     */
    public function setFecha($fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Convertir el objeto Tramo a su representación JSON
     */
    public function toJSON()
    {
        return
            [
                'id' => $this->getId(),
                'idUsuario' => $this->getIdUsuario(),
                'idTramo' => $this->getIdTramo(),
                'fecha' => $this->getFecha()
            ];
    }
}
