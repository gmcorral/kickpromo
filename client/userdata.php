<?php
	// Activate session
	//session_start(); 
?>

<!-- Global Header -->
<?php include 'header.php'; ?>


<!-- Call to Action Panel -->
<div class="row"">   

  <h4>Panel usuario</h4>
    
  <dl class="sub-nav">
  <dt>Menú:</dt>
  <dd ><a href="clientarea.php">Gestión de promos</a></dd>
  <dd class="active"><a href="userdata.php">Datos de usuario</a></dd>
  </dl>
      
    <div class="large-8 small-8 columns panel">
      <form class="custom">                 
        <!-- User info -->
        
        <div class="small-3 large-3 columns">
          <label for="left-label" class="right inline">Nombre</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="text" id="usr_email" placeholder="Nombre">
        </div>
        
        <div class="large-offset-3 columns"></div>
        
        <div class="small-3 large-3 columns">
          <label for="left-label" class="right inline">Correo</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="text" id="usr_email" placeholder="Correo">
        </div>

        <div class="large-offset-3 columns"></div>

        <!-- Sexo -->
        <div class="small-3 large-3 columns">
          <label for="right-label" class="right inline">Sexo</label>
        </div>

        <div class="small-3 large-6 columns">
        <label for="radio1">
            <input name="radio1" type="radio" id="radio1" style="display:none;">
              <span class="custom radio"></span> Hombre

            <input name="radio1" type="radio" id="radio1" style="display:none;">
              <span class="custom radio"></span> Mujer
          </label>
        </div>  
        <div class="large-offset-3 columns"></div>
        <div class="small-3 large-3 columns">
          <label for="left-label" class="right inline">Zona horaria</label>
        </div>

        <div class="small-3 large-6 columns">
          <label for="customDropdown1"></label>
            <select id="customDropdown1" class="medium">
              <option >(GMT-05:00) Eastern Time (US & Canada)</option>
              <option >(GMT-08:00) Pacific Time (US & Canada)</option>
              <option >(GMT-06:00) Central Time (US & Canada)</option>
              <option >(GMT-07:00) Mountain Time (US & Canada)</option>
              <option >(GMT-07:00) Arizona</option>
              <option >(GMT-05:00) Indiana (East)</option>
              <option >(GMT-11:00) Midway Island</option>
              <option >(GMT-10:100) Hawaii</option>
              <option >(GMT-09:00) Alaska</option>
              <option >(GMT-11:00) Samoa</option>
              <option >(GMT-08:00) Tijuana</option>
              <option >(GMT-07:00) Chihuahua</option>
              <option >(GMT-07:00) Mazatlan</option>
              <option >(GMT-06:00) Guadalajara</option>
              <option >(GMT-06:00) Mexico City</option>
              <option >(GMT-06:00) Monterrey</option>
              <option >(GMT-06:00) Saskatchewan</option>
              <option >(GMT-05:00) Bogota</option>
              <option >(GMT-05:00) Lima</option>
              <option >(GMT-05:00) Quito</option>
              <option >(GMT-04:30) Caracas</option>
              <option >(GMT-04:00) La Paz</option>
              <option >(GMT-04:00) Santiago</option>
              <option >(GMT-03:30) Newfoundland</option>
              <option >(GMT-03:00) Brasilia</option>
              <option >(GMT-03:00) Buenos Aires</option>
              <option >(GMT-03:00) Georgetown</option>
              <option >(GMT-01:00) Azores</option>
              <option >(GMT-01:00) Cape Verde Is.</option>
              <option >(GMT) Casablanca</option>
              <option >(GMT) Dublin</option>
              <option >(GMT) Edinburgh</option>
              <option >(GMT) Lisbon</option>
              <option >(GMT) London</option>
              <option >(GMT) Monrovia</option>
              <option >(GMT+01:00) Amsterdam</option>
              <option >(GMT+01:00) Belgrade</option>
              <option >(GMT+01:00) Berlin</option>
              <option >(GMT+01:00) Bern</option>
              <option >(GMT+01:00) Bratislava</option>
              <option >(GMT+01:00) Brussels</option>
              <option >(GMT+01:00) Budapest</option>
              <option >(GMT+01:00) Copenhagen</option>
              <option >(GMT+01:00) Ljubljana</option>
              <option >(GMT+01:00) Madrid</option>
              <option >(GMT+01:00) Paris</option>
              <option >(GMT+01:00) Prague</option>
              <option >(GMT+01:00) Rome</option>
              <option >(GMT+01:00) Sarajevo</option>
              <option >(GMT+01:00) Skopje</option>
              <option >(GMT+01:00) Stockholm</option>
              <option >(GMT+01:00) Vienna</option>
              <option >(GMT+01:00) Warsaw</option>
              <option >(GMT+01:00) Zagreb</option>
              <option >(GMT+02:00) Athens</option>
              <option >(GMT+02:00) Bucharest</option>
              <option >(GMT+02:00) Cairo</option>
              <option >(GMT+02:00) Harare</option>
              <option >(GMT+02:00) Helsinki</option>
              <option >(GMT+02:00) Istanbul</option>
              <option >(GMT+02:00) Jerusalem</option>
              <option >(GMT+02:00) Kyiv</option>
              <option >(GMT+02:00) Pretoria</option>
              <option >(GMT+02:00) Riga</option>
              <option >(GMT+02:00) Sofia</option>
              <option >(GMT+02:00) Tallinn</option>
              <option >(GMT+02:00) Vilnius</option>
              <option >(GMT+03:00) Baghdad</option>
              <option >(GMT+03:00) Kuwait</option>
              <option >(GMT+03:00) Minsk</option>
              <option >(GMT+03:00) Nairobi</option>
              <option >(GMT+03:00) Riyadh</option>
              <option >(GMT+03:30) Tehran</option>
              <option >(GMT+04:00) Abu Dhabi</option>
              <option >(GMT+04:00) Baku</option>
              <option >(GMT+04:00) Moscow</option>
              <option >(GMT+04:00) Muscat</option>
              <option >(GMT+04:00) St. Petersburg</option>
              <option >(GMT+04:00) Tbilisi</option>
              <option >(GMT+04:00) Volgograd</option>
              <option >(GMT+04:00) Yerevan</option>
              <option >(GMT+04:30) Kabul</option>
              <option >(GMT+05:00) Islamabad</option>
              <option >(GMT+05:00) Karachi</option>
              <option >(GMT+05:00) Tashkent</option>
              <option >(GMT+05:30) Chennai</option>
              <option >(GMT+05:30) Kolkata</option>
              <option >(GMT+05:30) Mumbai</option>
              <option >(GMT+05:30) New Delhi</option>
              <option >(GMT+05:45) Kathmandu</option>
              <option >(GMT+06:00) Almaty</option>
              <option >(GMT+06:00) Astana</option>
              <option >(GMT+06:00) Dhaka</option>
              <option >(GMT+06:00) Ekaterinburg</option>
              <option >(GMT+06:00) Sri Jayawardenepura</option>
              <option >(GMT+06:30) Rangoon</option>
              <option >(GMT+07:00) Bangkok</option>
              <option >(GMT+07:00) Hanoi</option>
              <option >(GMT+07:00) Jakarta</option>
              <option >(GMT+07:00) Novosibirsk</option>
              <option >(GMT+08:00) Beijing</option>
              <option >(GMT+08:00) Chongqing</option>
              <option >(GMT+08:00) Hong Kong</option>
              <option >(GMT+08:00) Krasnoyarsk</option>
              <option >(GMT+08:00) Kuala Lumpur</option>
              <option >(GMT+08:00) Perth</option>
              <option >(GMT+08:00) Singapore</option>
              <option >(GMT+08:00) Taipei</option>
              <option >(GMT+08:00) Ulaan Bataar</option>
              <option >(GMT+08:00) Urumqi</option>
              <option >(GMT+09:00) Irkutsk</option>
              <option >(GMT+09:00) Osaka</option>
              <option >(GMT+09:00) Seoul</option>
              <option >(GMT+09:00) Tokyo</option>
              <option >(GMT+09:30) Adelaide</option>
              <option >(GMT+09:30) Darwin</option>
              <option >(GMT+10:00) Brisbane</option>
              <option >(GMT+10:00) Canberra</option>
              <option >(GMT+10:00) Guam</option>
              <option >(GMT+10:00) Hobart</option>
              <option >(GMT+10:00) Melbourne</option>
              <option >(GMT+10:00) Port Moresby</option>
              <option >(GMT+10:00) Sydney</option>
              <option >(GMT+10:00) Yakutsk</option>
              <option >(GMT+11:00) New Caledonia</option>
              <option >(GMT+11:00) Vladivostok</option>
              <option >(GMT+12:00) Auckland</option>
              <option >(GMT+12:00) Fiji</option>
              <option >(GMT+12:00) Kamchatka</option>
              <option >(GMT+12:00) Magadan</option>
              <option >(GMT+12:00) Marshall Is.</option>
            </select>
        </div>

        <div class="large-offset-3 columns"></div>
        
      </form>
      
      <hr />
    
      <form> 
        <div class="large-offset-3 columns"></div>

        <!-- Old password -->
        <div class="small-3 large-3 columns">
          <label for="right-label" class="right inline">Contraseña actual</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="password" id="usr_passwd1" placeholder="Contraseña">
        </div>  
          
        <div class="large-offset-3 columns"></div>
        
        <!-- Password -->
        <div class="small-3 large-3 columns">
          <label for="right-label" class="right inline">Contraseña nueva</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="password" id="usr_passwd1" placeholder="Contraseña">
        </div>  
          
        <div class="large-offset-3 columns"></div>

        <!-- Repeat Password -->
        <div class="small-3 large-6 large-offset-3 columns">
          <input type="password" id="usr_passwd2" placeholder="Repetir Contraseña">
        </div>  
          
        <div class="large-offset-3 columns"></div>  
      
        <div class="small-12 large-12 columns">
          <a href="#" class="small button">Guardar</a>
        </div>
      </form>    
    </div>
    <div class="large-3 small-4 large-offset-1 columns">
    <div>
      <a href="index.php" class="small button">Cerrar Sesión</a>
    </div> 
    <ul class="side-nav">
      <li><a href="clientarea.php">Gestión de promos</a></li>
      <li class="divider"></li>
      <li class="active"><a href="userdata.php">Datos de usuario</a></li>
    </ul>    
    <h4 class="text-center">Tips</h4>
    <ul class="circle">
      <li>Has probado a echar un ojo en tutorial, nos hemos esforzado en que esté todo claro.</li>
      <li>Trataremos de contestarte lo antes posible. </li>
    </ul>
  </div> 
</div> 
    

  
<!-- Global Footer --> 
<?php include 'footer.php'; ?>
  
<script type="text/javascript">


</script>