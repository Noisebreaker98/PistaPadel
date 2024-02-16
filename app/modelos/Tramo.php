<?php 
class Tramo {
    public $id;
    public $hora;

    public function __construct() {
        
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
     * Get the value of hora
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set the value of hora
     */
    public function setHora($hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Convertir el objeto Tramo a su representación JSON
     */
    public function toJSON() {
        return
            [
            'id' => $this->getId(),
            'hora' => $this->getHora()
        ]; 
    }
}

?>