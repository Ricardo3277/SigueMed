<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paciente_Controller
 *
 * @author SigueMed
 */
class Paciente_Controller extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('Paciente_Model');
        $this->load->model('CatalogoEscolaridad_Model');
        $this->load->model('CatalogoEstadoCivil_Model');
        $this->load->model('CatalogoReligion_Model');
        $this->load->model('CatalogoOcupacion_Model');
        $this->load->model('CatalogoRecursosMedicos_Model');
        $this->load->model('FamiliarResponsable_Model');
        $this->load->model('SeguimientoMedico_Model');
        $this->load->model('CatalogoRespuestaSeguimiento_Model');
        $this->load->helper('form');

    }

    public function ConsultarPacientes()
    {

        $data['Pacientes'] = $this->Paciente_Model->ConsultarPacientes();

        $data['title']= "Consulta Inventario";
        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('Paciente/ConsultaPacientes',$data);
        $this->load->view('templates/FooterContainer');
    }

    public function Load_EditarPaciente($IdPaciente)
    {
        $data['Paciente'] = $this->Paciente_Model->ConsultarPacientePorId($IdPaciente);

        $data['title']='Editar Paciente';
        $data['PacienteSubmitAction'] = 'guardar';
        $data['PacienteSubmitTitle'] = 'Guardar';
        $data['PacienteCancelActionEnabled'] = False;

        $data['PacienteActionsEnabled'] = true;

        $data['FamiliarResponsableSubmitAction'] = 'guardarFR';
        $data['FamiliarResponsableSubmitTitle'] = 'Guardar';
        $data['FamiliarResponsableCancelActionEnabled'] = False;

        $data['FamiliarResponsableActionsEnabled'] = true;

        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('Paciente/FormPaciente',$data);
        $this->load->view('Paciente/PacienteCard',$data);
        $this->load->view('Paciente/CardFamiliarResponsable',$data);

        $this->load->view('templates/FormFooter',$data);
        $this->load->view('templates/FooterContainer');

    }

    public function EditarPaciente($IdPaciente)
    {
        $action = $this->input->post('action');

        if ($action == 'guardar')
        {


           $result = $this->Paciente_Model->ActualizarPaciente_Post($IdPaciente);

           if ($result==1)
           {
               echo '<script> alert("Los datos del paciente han sido actualizados exitosamente."); </script>';
               $this->ConsultarPacientes();
           }
        }
        else if($action=='cerrar')
        {
            $this->ConsultarPacientes();

        }

    }

   public function CargarEscolaridad_ajax()
   {
       $Escolaridad = $this->CatalogoEscolaridad_Model->ConsultarEscolaridad();


        $output='<option value="">Selecciona una opci??n</option>';
        foreach($Escolaridad as $item)
        {
            $output .= '<option value="'.$item['IdEscolaridad'].'">'.$item['DescripcionEscolaridad'].'</option>';
        }
        echo $output;
   }

   public function CargarEstadoCivil_ajax()
   {
       $EstadoCivil = $this->CatalogoEstadoCivil_Model->ConsultarEstadoCivil();


        $output='<option value="">Selecciona una opci??n</option>';
        foreach($EstadoCivil as $item)
        {
            $output .= '<option value="'.$item['IdEstadoCivil'].'">'.$item['DescripcionEstadoCivil'].'</option>';
        }
        echo $output;

   }

   public function CargarReligion_ajax()
   {
       $Religion = $this->CatalogoReligion_Model->ConsultarReligion();


        $output='<option value="">Selecciona una opci??n</option>';
        foreach($Religion as $item)
        {
            $output .= '<option value="'.$item['IdReligion'].'">'.$item['DescripcionReligion'].'</option>';
        }
        echo $output;

   }

   public function CargarOcupacion_ajax()
   {
       $Ocupacion = $this->CatalogoOcupacion_Model->ConsultarOcupacion();


        $output='<option value="">Selecciona una opci??n</option>';
        foreach($Ocupacion as $item)
        {
            $output .= '<option value="'.$item['IdOcupacion'].'">'.$item['DescripcionOcupacion'].'</option>';
        }
        echo $output;

   }

    public function CargarRecursosMedicos_ajax()
   {
       $RecursosMedicos = $this->CatalogoRecursosMedicos_Model->ConsultarRecursosMedicos();


        $output='<option value="">Selecciona una opci??n</option>';
        foreach($RecursosMedicos as $item)
        {
            $output .= '<option value="'.$item['IdRecursosMedicos'].'">'.$item['DescripcionRecursosMedicos'].'</option>';
        }
        echo $output;

   }

   public function AgregarNuevoFamiliar_ajax()
   {
       if($this->input->post('IdPaciente')!== null)
       {
           $FamiliarResponsable = array(
            'IdPaciente'=>$this->input->post('IdPaciente'),
            'Parentesco'=>$this->input->post('Parentesco'),
            'Nombre'=>$this->input->post('Nombre'),
            'Apellidos'=>$this->input->post('Apellidos'),
            'Telefono'=>$this->input->post('Telefono'),
            'FechaNacimiento'=>$this->input->post('FechaNacimiento'),
            'IdEscolaridad'=>$this->input->post('IdEscolaridad'),
            'Escolaridad'=>$this->input->post('Escolaridad'),
            'IdEstadoCivil'=>$this->input->post('IdEstadoCivil'),
            'IdReligion'=>$this->input->post('IdReligion'),
            'Religion'=>$this->input->post('Religion'),
            'IdOcupacion'=>$this->input->post('IdOcupacion'),
            'Ocupacion'=>$this->input->post('Ocupacion'),
            'DondeVive'=>$this->input->post('DondeVive'),
            'Calle'=>$this->input->post('Calle'),
            'Colonia'=>$this->input->post('Colonia'),
            'CodigoPostal'=>$this->input->post('CodigoPostal')


        );

        $this->FamiliarResponsable_Model->AgregarFamiliarResponsable($FamiliarResponsable);

        echo 1;
       }
    }

    public function ConsultarFamiliaresPaciente()
    {
        $IdPaciente = $this->input->post('IdPaciente');

        if ($IdPaciente !=="")
        {
            $Familiares = $this->FamiliarResponsable_Model->ConsultarFamiliaresPaciente($IdPaciente);
            echo json_encode($Familiares);
        }
        else
        {
            echo json_encode("0");
        }
    }

    public function ConsultarFamiliarResponsable()
    {
        $IdFamiliarResponsable = $this->input->post('IdFamiliar');

        if ($IdFamiliarResponsable!=="")
        {
            $Familiar = $this->FamiliarResponsable_Model->ConsultarFamiliarPorId($IdFamiliarResponsable);
            echo json_encode($Familiar);
        }
        else
        {
            echo json_encode("0");
        }
    }

    public function ModificarFamiliar_ajax()
    {
        if($this->input->post('IdFamiliarResponsable')!== null)
       {
           $FamiliarResponsable = array(

            'Parentesco'=>$this->input->post('Parentesco'),
            'Nombre'=>$this->input->post('Nombre'),
            'Apellidos'=>$this->input->post('Apellidos'),
            'Telefono'=>$this->input->post('Telefono'),
            'FechaNacimiento'=>$this->input->post('FechaNacimiento'),
            'IdEscolaridad'=>$this->input->post('IdEscolaridad'),
            'Escolaridad'=>$this->input->post('Escolaridad'),
            'IdEstadoCivil'=>$this->input->post('IdEstadoCivil'),
            'IdReligion'=>$this->input->post('IdReligion'),
            'Religion'=>$this->input->post('Religion'),
            'IdOcupacion'=>$this->input->post('IdOcupacion'),
            'Ocupacion'=>$this->input->post('Ocupacion'),
            'DondeVive'=>$this->input->post('DondeVive'),
            'Calle'=>$this->input->post('Calle'),
            'Colonia'=>$this->input->post('Colonia'),
            'CodigoPostal'=>$this->input->post('CodigoPostal')


        );

        $this->FamiliarResponsable_Model->ActualizarFamiliarResponsable($this->input->post('IdFamiliarResponsable'),$FamiliarResponsable);

        echo 1;
       }

    }

    public function ConsultarPacientePorId_ajax()
    {
        $IdPaciente = $this->input->post('IdPaciente');
        $Paciente = $this->Paciente_Model->ConsultarPacientePorId($IdPaciente);

        echo json_encode($Paciente);

    }
    /*------------------------------------------------SEGUIMIENTO PACIENTES----------------------------------*/

    public function Load_ConsultarSeguimientoPacientes()
    {
        $data['title']='Consulta Seguimientos de Pacientes';

        $this->load->view('templates/MainContainer',$data);
        $this->load->view('templates/HeaderContainer',$data);
        $this->load->view('Paciente/CardConsultaSeguimientoPacientes',$data);
        $this->load->view('templates/FormFooter',$data);
        $this->load->view('templates/FooterContainer');
    }

    public function ConsultarSeguimientoPacientes_ajax()
    {

        $SeguimientosPaciente = $this->SeguimientoMedico_Model->ConsultarSeguimientosPendientes();

        for ($i=0;$i<sizeof($SeguimientosPaciente);$i++)
        {
            switch($SeguimientosPaciente[$i]['IdEstatusSeguimiento'])
            {
                case 1:
                    $SeguimientosPaciente[$i]['Respuesta1']='<button type="button" class="btn btn-primary" id="btnllamar" onclick="ConfirmarSeguimientoPaciente('.$SeguimientosPaciente[$i]['IdSeguimientoMedico'].','.$SeguimientosPaciente[$i]['IdEstatusSeguimiento'].')">Llamar</button>';
                    break;
                case 2:
                    if($SeguimientosPaciente[$i]['Respuesta2']== null && ($SeguimientosPaciente[$i]['IdEstatusSeguimiento']==2))
                    {
                        $SeguimientosPaciente[$i]['Respuesta2']='<button type="button" class="btn btn-primary" id="btnllamar" onclick="ConfirmarSeguimientoPaciente('.$SeguimientosPaciente[$i]['IdSeguimientoMedico'].','.$SeguimientosPaciente[$i]['IdEstatusSeguimiento'].')">Llamar</button>';
                    }
                    else if ($SeguimientosPaciente[$i]['Respuesta3']== null && ($SeguimientosPaciente[$i]['IdEstatusSeguimiento']==2))
                    {
                        $SeguimientosPaciente[$i]['Respuesta3']='<button type="button" class="btn btn-primary" id="btnllamar" onclick="ConfirmarSeguimientoPaciente('.$SeguimientosPaciente[$i]['IdSeguimientoMedico'].','.$SeguimientosPaciente[$i]['IdEstatusSeguimiento'].')">Llamar</button>';
                    }
                    break;
                case 3:
                    break;
            }

        }

        echo json_encode($SeguimientosPaciente);


    }

    public function ConsultarRespuestasSeguimiento_ajax()
    {
        $RespuestasSeguimiento = $this->CatalogoRespuestaSeguimiento_Model->ConsultarCatalogoRespuestasSeguimiento();

        echo json_encode($RespuestasSeguimiento);
    }

    public function Load_AgregarNuevoPaciente()
    {

      $data['title']='Registrar Nuevo Paciente';


      $this->load->view('templates/MainContainer',$data);
      $this->load->view('templates/HeaderContainer',$data);
      $this->load->view('Paciente/FormNuevoPaciente');
      $this->load->view('Paciente/CardRegistroPaciente',$data);
      $this->load->view('templates/FormFooter',$data);
      $this->load->view('templates/FooterContainer');

    }

  public function AgregarNuevoPaciente()
  {
    $Nombre = rtrim($this->input->post('NombrePaciente'));
    $Apellidos = rtrim($this->input->post('ApellidosPaciente'));
    $Telefono = $this->input->post('TelefonoPaciente');
    $FechaNacimiento = $this->input->post('FechaNacimientoPaciente');
    $RFC = ($this->input->post('RFCPaciente')!==null?$this->input->post('RFCPaciente'):"") ;
    $email = ($this->input->post('emailPaciente')!==null?$this->input->post('emailPaciente'):"") ;
    $DondeVive = ($this->input->post('DondeVivePaciente')!==null?$this->input->post('DondeVivePaciente'):0) ;
    $Calle=($this->input->post('callePaciente')!==null?$this->input->post('callePaciente'):"") ;
    $Colonia =($this->input->post('Colonia')!==null?$this->input->post('Colonia'):"") ;
    $CP = ($this->input->post('CP')!==null?$this->input->post('CP'):"") ;
    $Sexo = $this->input->post('Sexo');

    $DatosPaciente = array(
      'Nombre'=>$Nombre,
      'Apellidos'=>$Apellidos,
      'NumCelular'=> $Telefono,
      'FechaNacimiento'=>$FechaNacimiento,
      'RFC'=>$RFC,
      'email'=>$email,
      'DondeVive'=> $DondeVive,
      'Sexo'=>$Sexo,
      'Colonia'=>$Colonia,
      'Calle'=>$Calle,
      'CP'=>$CP
    );
    $result = $this->Paciente_Model->AgregarPaciente($DatosPaciente);

    $data['title']='Registrar Nuevo Paciente';

    $data['swal']=true;
    $data['swalMessage']="title:'El Paciente ha sido registrado',
    type: 'success',
    showConfirmButton: true,
    confirmButtonText:'<i class=\"icon-calendar3\"></i> Agendar Cita',
    showCancelButton: true,
    cancelButtonText: '<i class=\"icon-phone-hang-up\"></i> Programar Seguimiento'";

    $data['swalAction'] = ".then((result)=> {
      if (result.value) {
        window.location.href ='".site_url("Agenda/VistaAgenda")."';
      }
      else {
        window.location.href ='".site_url("Paciente/SeguimientoPaciente")."';
      }
    });";
    $this->load->view('templates/MainContainer',$data);
    $this->load->view('templates/HeaderContainer',$data);
    $this->load->view('templates/FormFooter',$data);
    $this->load->view('templates/FooterContainer');


    //echo "<script>window.location.href(".site_url("Paciente/NuevoPaciente").");Swal.fire({title:'El Paciente ha sido registrado',type: 'success',showConfirmButton: true});</script>";
    // code...
  }
}
