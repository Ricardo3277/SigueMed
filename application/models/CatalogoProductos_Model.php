<?php
class CatalogoProductos_Model extends CI_Model{
    private $table;
   
    
     public function __construct() {
        parent::__construct();
        $this->table = "catalogoproductos";
        $this->load->database();

    }
    
  
     public function ConsultarCatalogoporproducto($IdProducto){
        $condition = "IdProducto =" . $IdProducto;
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
            $row = $query->row();
            $this->LoadRow($row);
            return $query->row_array();
        }else{
            return false;
        }
    }
    
    public function ConsultarProductosPorServicio($IdServicio)
    {
        $this->db->select($this->table.'.*, DescripcionServicio');
        $this->db->from($this->table.',servicio');
        $this->db->where($this->table.'.IdServicio=servicio.IdServicio');
        $this->db->where($this->table.'.IdServicio', $IdServicio);
        $this->db->order_by('DescripcionProducto','asc');
        
        $query= $this->db->get();
        
        return $query->result_array();
    }
    
    public function ConsultarProductoPorId($IdProducto)
    {
        $this->db->select($this->table.'.*');
        $this->db->from($this->table);
  
        $this->db->where($this->table.'.IdProducto', $IdProducto);
        $this->db->order_by('DescripcionProducto','asc');
        
        $query= $this->db->get();
        
        return $query->row();
    }
    
    public function CargarListaProductosPorServicio($IdServicio)
    {
        $this->db->select($this->table.'.*, DescripcionServicio');
        $this->db->from($this->table.',Servicio');
        $this->db->where($this->table.'.IdServicio=Servicio.IdServicio');
        $this->db->where($this->table.'.IdServicio', $IdServicio);
        $this->db->order_by('DescripcionProducto','asc');
        
        $query= $this->db->get();
        
        return $query->result_array();
        
       
    }
    
    /*
     * DESCRIPCION: Consultar los productos que tengan subproductos y movimientos de inventario
     * RETURN: Array con la información de los Productos y su existencia
     */
    public function ConsultarProductosInventario($IdClinica)
    {
        $query = $this->db->query('call ConsultarExistenciaInventario('.$IdClinica.')');
        return $query->result_array();
        
    }
    
    public function ConsultarResumenProductosServicio()
    {
        $this->db->select('DescripcionServicio, DescripcionProducto, SUM(Cantidad) as TotalProducto');
        $this->db->from($this->table);
        $this->db->join('servicio s',$this->table.'.IdServicio = s.IdServicio');
        $this->db->join('detallenotaremision dn',$this->table.'.IdProducto = dn.IdProducto');
        $this->db->join('notaremision nr','nr.IdNotaRemision = dn.IdNotaRemision');
        $this->db->group_by('DescripcionServicio, DescripcionProducto');
        $this->db->where('nr.IdCorteCaja',NULL);
        $this->db->where('nr.IdEstatusNotaRemision <> 2');
        $this->db->order_by('DescripcionServicio','ASC');
        $this->db->order_by('DescripcionProducto','ASC');
        
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
