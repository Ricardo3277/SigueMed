<!DOCTYPE html>
<html>
<head>
    <title>NOTA MEDICA</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        .box 
        {
            width:100%;
            max-width: 650px;
            margin:0 auto;
            
        }
    </style>
    
</head>
<body>
    
    <?php
        if (isset($errorMessage)) {
            echo "<div class='message'>";
            echo $errorMessage;
            echo "</div>";
        }
    ?>
    <?php echo validation_errors(); ?>
    
    <?php echo form_open('NotaMedica_Controller/ElaborarNotaMedica/'.$NotaMedica->IdNotaMedica); ?>
    
     <div>
        <label for="Nombre">Nombre</label>
        <input type="text" name="Nombre" id="Nombre" value="<?php echo $Paciente->Nombre; ?>"/>

        <label for="Apellidos">Apellidos</label>
        <input type="text" name="Apellidos" id="Apellidos" value="<?php echo $Paciente->Apellidos; ?>"/><br/>
        
        <label for="Edad">Edad</label>
        <input type="text" name="Edad" id ="Edad" value="<?php 
         
            $edad = (time()-strtotime($Paciente->FechaNacimiento))/ (60*60*24*365.35); 
            echo floor($edad) ?>"/>
        
        <label for="FechaNacimiento">Fecha Nacimiento</label>
        <input type="text" name="FechaNacimiento" id="FechaNacimiento" value="<?php echo $Paciente->FechaNacimiento; ?>"/><br/>
        
        <label for="calle">Calle</label>
        <input type="text" name="Calle" value="<?php echo $Paciente->Calle; ?>"/>
        
        <label for="colonia">Colonia</label>
        <input type="text" name="Colonia" value="<?php echo $Paciente->Colonia; ?>"/>
        
        <label for="cp">Código Postal</label>
        <input type="text" name="CP" value="<?php echo $Paciente->CP; ?>"/><br/>
        
        <label for="EstadoCivil">Estado Civil:</label>
        <input type="text" name="EstadoCivil" value="<?php echo $Paciente->EstadoCivil; ?>"/>
        
        <label for="ViveCon">Vive con:</label>
        <input type="text" name="ViveCon" value="<?php echo $Paciente->ViveCon; ?>"/>
        
        <label for="Escolaridad">Escolaridad</label>
        <input type="text" name="Escolaridad" value="<?php echo $Paciente->Escolaridad; ?>"/><br/>
        
        <label for="IdServiciosMedicos">Recursos Medicos</label>
        <input type="text" name="IdServiciosMedicos" value=""/>
        
        <label for="Celular">Celular:</label>
        <input type="text" name="Celular" value="<?php echo $Paciente->NumCelular; ?>"/>
        
    </div>
    
    <!--Div Somatometria-->
    <div>
        <label for="Peso">Peso</label>
        <input type="text" name="Peso" id="Peso" value="<?php echo $NotaMedica->PesoPaciente; ?>" />Kg.

        <label for="Talla">Talla</label>
        <input type="text" name="Talla" id="Talla" value="<?php echo $NotaMedica->TallaPaciente; ?>"/>mts.
        
        <label for="TA">T/A</label>
        <input type="text" name="TA" id="TA" value="<?php echo $NotaMedica->PresionPaciente; ?>"/>Mm/Hg.
        
        <label for="Temperatura">T</label>
        <input type="text" name="Temperatura" id="Temperatura" value="<?php echo $NotaMedica->TemperaturaPaciente; ?>"/>°C.
        
        <label for="FC">F/C</label>
        <input type="text" name="FC" id="FC" value="<?php echo $NotaMedica->FrCardiacaPaciente; ?>"/>L/m.
        
        <label for="FR">F/R</label>
        <input type="text" name="FR" id="FR" value="<?php echo $NotaMedica->FrRespiratoriaPaciente; ?>"/>R/m.
        
         
    </div>
    
    
    <!--Div Antecedentes -->
    
    <div>
         <?php
    
    if ($Antecedentes!=FALSE)
    {
        foreach ($Antecedentes as $AntecedenteNota)
        {
            echo "<label for='Antecendete".$AntecedenteNota['IdAntecedenteNotaMedica']."'>".$AntecedenteNota['DescripcionAntecedente']."</label></br>";
            echo "<input type='text' name='Antecedente".$AntecedenteNota['IdAntecedenteNotaMedica']."' id='Antecedente".$AntecedenteNota['IdAntecedenteNotaMedica']."' value='".$AntecedenteNota['DescripcionAntecedenteNotaMedica']."'/></br>";

            // put your code here
        }
    }
    else
    {
        echo "No carga antecedentes";
        echo $NotaMedica->IdNotaMedica;
        echo count($Antecedentes);
    }
    
    ?>
    </div>
    
    <!--DIV PRODUCTOS-->
    <div class="form-group">
        <select name="servicio" id="servicio" class="form-control input-lg">
            <option value="">Selecciona un Servicio</option>
 
            <?php foreach ($Servicios as $servicio)
            {
                echo '<option value ="'.$servicio['IdServicio'].'">'.$servicio['DescripcionServicio'].'</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <select name="producto" id="producto" class="form-control input-lg">
            <option value="">Selecciona un Producto</option>
            
        </select>
    </div>
    
    
    <!--Div Botones-->
    <div>
        <button type="submit" name="action" value="guardar">Guardar</button>
        <button type="submit" name="action" value="cancelar">Cancelar</button>
    </div>
   
    
    </form>
</body>
<script type='text/javascript' language='javascript'>
    $(document).ready(function()
    {
       $("#servicio").change(function(){
           
          var servicio_id = $('#servicio').val();
          alert(servicio_id);
          if(servicio_id!='')
          {

              $.ajax({
                  url:"<?php echo site_url();?>/NotaMedica_Controller/ConsultarProductosPorServicio",
                  method:"POST",
                  data:{servicio_id:servicio_id},
                  success: function(data)
                    {
                        $('#producto').html(data);
                    }
              });
              
          }
          
       }); 
    });
</script>


</html>
