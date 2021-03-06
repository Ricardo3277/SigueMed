<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotaMedica_Controller
 *
 * @author SigueMed
 */

require_once(dirname(__FILE__)."/Agenda_Controler.php");

class NotaMedica_Controller extends Agenda_Controler {

    public function __construct() {
        parent::__construct();
        //Cargar herramientas para form
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url_helper');

        //Cargar Modelos usados por el Controlador para el manejo de las Notas Medicas
        $this->load->model('NotaMedica_Model');
        $this->load->model('Paciente_Model');
        $this->load->model('CitaServicio_Model');
        $this->load->model('AntecedenteNotaMedica_Model');
        $this->load->model('CatalogoDiagnosticos_Model');
        $this->load->model('Servicio_Model');
        $this->load->model('CatalogoProductos_Model');
        $this->load->model('ProductosNotaMedica_Model');
        $this->load->model('DiagnosticoNotaMedica_Model');
        $this->load->model('SeguimientoMedico_Model');


    }

    public function Load_RegistrarSomatometria($IdCita)
    {
        $Cita = $this->CitaServicio_Model->ConsultarCitaPorId($IdCita);
        $Paciente = $this->Paciente_Model->ConsultarPacientePorId($Cita->IdPaciente);
        $data['Paciente'] = $Paciente;
        $data['Cita']= $Cita;

        $data['title']='Somatometria Paciente';
        $data['PacienteSubmitAction'] = '';
        $data['PacienteCancelAction'] = '';
        $data['PacienteCancelTitle'] = '';
        $data['PacienteActionsEnabled'] = false;
        $data['SomatometriaActionsEnabled'] = true;
        $data['SomatometriaSubmitAction']='confirmar';
        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('NotaMedica/FormRegistrarSomatometria',$data);
        $this->load->view('Paciente/PacienteCard',$data);
        $this->load->view('NotaMedica/SomatometriaCard',$data);
        $this->load->view('templates/FormFooter',$data);
        $this->load->view('templates/FooterContainer');
    }
    /*
     * Function: RegistrarSomatometria
     * Descripttion:La funci??n mostrara la vista para Registrar los datos de Somatometria del paciente que agendo la Cita <$IdCita> y crear?? una nueva nota m??dica
     */
    public function RegistrarSomatometria($IdCita)
    {
        $action = $this->input->post('action');

        if ($action =='confirmar')
        {
            $Cita = $this->CitaServicio_Model->ConsultarCitaPorId($IdCita);
            $this->Paciente_Model->ActualizarPaciente_post($Cita->IdPaciente);

            //Crear Nota Medica
            $this->CrearNuevaNotaMedica($IdCita);
            $this->CitaServicio_Model->ActualizarEstatusCita($IdCita, REGISTRADA);
        }

        //$this->CitasDeHoy();
        redirect(site_url('Agenda/CitasHoy'));



    }
    /*
     * Function: NuevaNotaMedica
     * Description: La funci??n recibir?? el Id del Paciente y del servicio para mostrar el formato NotaMedica.php para la creaci??n de una nueva nota
     */
    public function CrearNuevaNotaMedica($IdCita)
    {

        //$this->load->view('templates/header');
        //Cargar Informaci??n Ultima Nota del Paciente por Servicio
        $Cita = $this->CitaServicio_Model->ConsultarCitaPorId($IdCita);
        $IdUltimaNota = $this->NotaMedica_Model->ConsultarUltimaNotaMedicaPorPaciente($Cita->IdPaciente,$Cita->IdServicio);

        if(!isset($Cita->IdNotaMedica))
        {
            $DatosSomatometria = array(
            'PesoPaciente'=>$this->input->post('Peso'),
            'TallaPaciente'=> $this->input->post('Talla'),
            'PresionPaciente'=> $this->input->post('TA'),
            'FrCardiacaPaciente'=> $this->input->post('FC'),
            'FrRespiratoriaPaciente'=> $this->input->post('FR'),
            'TemperaturaPaciente'=> $this->input->post('Temperatura')
             );

            $IdEmpleado =  $this->session->userdata('IdEmpleado');
            $IdClinica = $this->session->userdata('IdClinica');
            $NuevaNotaMedica = $this->NotaMedica_Model->CrearNuevaNotaMedica($IdCita,$DatosSomatometria,$IdEmpleado,$IdClinica,$IdUltimaNota);
            $this->CitaServicio_Model->AsignarNotaMedica($IdCita, $NuevaNotaMedica);
        }

    }

    public function Load_ElaborarNotaMedica($IdNotaMedica)
    {
        $NotaMedica = $this->NotaMedica_Model->ConsultarNotaMedicaPorId($IdNotaMedica);
        $data['NotaMedica'] = $NotaMedica;
        $data['Paciente'] = $this->Paciente_Model->ConsultarPacientePorId($NotaMedica->IdPaciente);
        $data['Antecedentes'] = $this->AntecedenteNotaMedica_Model->ConsultarAntecedentesNota($IdNotaMedica);
        $data['Servicios'] = $this->Servicio_Model->ConsultarServicios();

        $Servicio = $this->Servicio_Model->ConsultarServicioPorId($NotaMedica->IdServicio);


        $data['title']='Nota Medica - '.$Servicio->DescripcionServicio;
        $data['PacienteSubmitAction'] = '';
        $data['PacienteActionsEnabled'] = false;
        $data['SomatometriaActionsEnabled'] = false;
        $data['SomatometriaSubmitAction']='';
        $data['ProductosNotaActionsEnabled']= true;
        $data['ProductosNotaSubmitAction']='guardar';

        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('NotaMedica/FormNotaMedica',$data);
        $this->load->view('Paciente/PacienteCard',$data);
        $this->load->view('Paciente/CardFamiliarResponsable',$data);
        $this->load->view('NotaMedica/SomatometriaCard',$data);
        $this->load->view('NotaMedica/CardAntecedentes',$data);
        $this->load->view('NotaMedica/CardNotaMedica',$data);
        $this->load->view('NotaMedica/CardDiagnosticoNotaMedica',$data);
        $this->load->view('NotaMedica/CardSeguimiento',$data);
        $this->load->view('NotaMedica/CardProductosNotaMedica',$data);
        $this->load->view('templates/FormFooter',$data);
        $this->load->view('templates/FooterContainer');

    }

    public function ElaborarNotaMedica($IdNotaMedica)
    {

        try
        {
        $action = $this->input->post('action');
        log_message('debug', 'Accion Nota Medica:'.$action);

        if ($action =='guardar')
        {
                $DatosNotaMedica = array(
                'PesoPaciente'=>$this->input->post('Peso'),
                'TallaPaciente'=> $this->input->post('Talla'),
                'PresionPaciente'=> $this->input->post('TA'),
                'FrCardiacaPaciente'=> $this->input->post('FC'),
                'FrRespiratoriaPaciente'=> $this->input->post('FR'),
                'TemperaturaPaciente'=> $this->input->post('Temperatura'),
                'DiagnosticoGeneral' => $this->input->post('DiagnosticoGeneral'),
                'PadecimientoActual'=> $this->input->post('PadecimientoActual'),
                'ExploracionFisica'=> $this->input->post('ExploracionFisica'),
                'Paraclinicos'=> $this->input->post('Paraclinicos'),
                'PlanesTratamiento'=> $this->input->post('PlanesTratamiento'),
                'IdEmpleado'=> $this->input->post('IdEmpleado'),
                'IdEstatusNotaMedica'=>NM_ATENDIDA

             );


            $this->db->trans_begin();

            $Antecedentes = $this->AntecedenteNotaMedica_Model->ConsultarAntecedentesNota($IdNotaMedica);


            foreach($Antecedentes as $Antecedente)
            {
                $DescripcionAntecedente = $this->input->post('Antecedente'.$Antecedente['IdAntecedenteNotaMedica']);
                $this->AntecedenteNotaMedica_Model->ActualizarAntecedente($Antecedente['IdAntecedenteNotaMedica'],$DescripcionAntecedente);

            }

            $this->NotaMedica_Model->ActualizarNotaMedica($IdNotaMedica,$DatosNotaMedica);


            $NotaMedica = $this->NotaMedica_Model->ConsultarNotaMedicaPorId($IdNotaMedica);

            $this->Paciente_Model->ActualizarPaciente_Post($NotaMedica->IdPaciente);

            $Cita = $this->CitaServicio_Model->ConsultarCitaPorNotaMedica($IdNotaMedica);


             $IdProductos = $this->input->post('IdProducto');
             $cantidadProductos = $this->input->post('cantidad');
             $precioProductos = $this->input->post('precio');
             $DescuentoProductos = $this->input->post('descuento');


             if (isset($IdProductos))
             {

                for ($i=0;$i<sizeof($IdProductos); $i++)
                {
                    $NuevoProducto = array(
                        'IdProducto'=>$IdProductos[$i],
                        'cantidad'=>$cantidadProductos[$i],
                        'precio'=> $precioProductos[$i],
                        'descuento'=>$DescuentoProductos[$i]
                    );

                $this->ProductosNotaMedica_Model->AgregarProductoNotaMedica($IdNotaMedica,$NuevoProducto);
                }

             }

             $IdDiagnosticos = $this->input->post('IdDiagnostico');

             if(isset($IdDiagnosticos))
             {
                 for($i=0; $i<sizeof($IdDiagnosticos);$i++)
                 {
                     $Diagnosticos[]= array(
                         'IdDiagnostico'=>$IdDiagnosticos[$i],
                         'IdNotaMedica'=>$IdNotaMedica
                     );
                 }

                 $this->DiagnosticoNotaMedica_Model->AgregarDiagnosticosNotaMedicaBatch($Diagnosticos);

             }

             $descripcionesSeguimiento = $this->input->post('ColDescSeguimiento');
             $fechasSeguimiento = $this->input->post('ColFechaSeguimiento');

             if (isset($descripcionesSeguimiento))
             {
                 for($i=0; $i<sizeof($descripcionesSeguimiento);$i++)
                 {
                     $Seguimientos[] = array(
                         'DescripcionSeguimiento'=> $descripcionesSeguimiento[$i],
                         'IdNotaMedica'=> $IdNotaMedica,
                         'IdPaciente' => $NotaMedica->IdPaciente,
                         'IdEstatusSeguimiento'=>1,
                         'IdElaboradoPor'=> $this->session->userdata('IdEmpleado'),
                         'FechaSeguimiento' => $fechasSeguimiento[$i]);
                 }

                 $this->SeguimientoMedico_Model->AgregarSeguimientoNotaMedicaBatch($Seguimientos);



             }

             $this->CitaServicio_Model->ActualizarEstatusCita($Cita->IdCitaServicio,ATENDIDA);

             if($this->db->trans_status()===FALSE)
             {
                 $this->db->trans_rollback();
                 echo "<script>alert('Hubo un error al guardar la informaci??n');</script>";
             }
             else
             {
                 $this->db->trans_commit();
                 echo "<script>alert('La Nota Medica ha sido guardada con ??xito');</script>";
             }

        }

        redirect(site_url('Agenda/CitasHoy'));

        }
        catch(Exception $e)
        {
            $this->db->trans_rollback();
            echo "<script>alert('Hubo un error al guardar la informaci??n');</script>";
            log_message('error', $e->getMessage());
        }

    }

    public function ConsultarProductosPorServicio()
    {

        if($this->input->post('servicio_id'))
        {

            $Productos=  $this->CatalogoProductos_Model->ConsultarProductosPorServicio($this->input->post('servicio_id'));

            $output='<option value="">Selecciona un Producto</option>';
            foreach($Productos as $producto)
            {
                $output .= '<option value="'.$producto['IdProducto'].'" data-proveedor="'.$producto['PrecioProveedor'].'">'.$producto['DescripcionProducto'].'</option>';
            }
            echo $output;
        }

    }

    public function ConsultarProductoPorId()
    {
        if($this->input->post('producto_id'))
        {

            $Producto_detail = $this->CatalogoProductos_Model->ConsultarProductoPorId($this->input->post('producto_id'));

            echo json_encode($Producto_detail);
        }
    }

    public function CargarCBDiagnosticoServicio()
    {
        if($this->input->post('servicio_id'))
        {
            $CategoriasDiagnostico = $this->CatalogoDiagnosticos_Model->ConsultarDiagnosticosPorServicio($this->input->post('servicio_id'));

            $output='<option value="">Selecciona una Categoria</option>';
            foreach ($CategoriasDiagnostico as $Categoria)
            {
                $output .= '<option value="'.$Categoria['IdDiagnostico'].'">'.$Categoria['DescripcionDiagnostico'].'</option>';
            }

            echo $output;
        }
    }

    public function ConsultarNotaMedica($IdNotaMedica)
    {

        $NotaMedica = $this->NotaMedica_Model->ConsultarNotaMedicaPorId($IdNotaMedica);
        $data['NotaMedica'] = $NotaMedica;
        $data['Paciente'] = $this->Paciente_Model->ConsultarPacientePorId($NotaMedica->IdPaciente);
        $data['Antecedentes'] = $this->AntecedenteNotaMedica_Model->ConsultarAntecedentesNota($IdNotaMedica);
        $data['Servicios'] = $this->Servicio_Model->ConsultarServicios();
        $data['Diagnosticos']= $this->DiagnosticoNotaMedica_Model->ConsultarDiagnosticosNotaMedica($NotaMedica->IdNotaMedica);

        $data['title']='Consulta Nota Medica #'.$NotaMedica->IdNotaMedica;
        $data['PacienteSubmitAction'] = '';
        $data['PacienteActionsEnabled'] = false;
        $data['SomatometriaActionsEnabled'] = false;
        $data['SomatometriaSubmitAction']='';
        $data['ProductosNotaActionsEnabled']= false;
        $data['ProductosNotaSubmitAction']='';

        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('NotaMedica/FormNotaMedica',$data);
        $this->load->view('Paciente/PacienteCard',$data);
        $this->load->view('NotaMedica/SomatometriaCard',$data);
        $this->load->view('NotaMedica/CardAntecedentes',$data);
        $this->load->view('NotaMedica/CardDiagnosticoNotaMedica',$data);

        $this->load->view('templates/FormFooter',$data);
        $this->load->view('templates/FooterContainer');

    }
    }

?>
