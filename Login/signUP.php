<!DOCTYPE html>
<?php 
    //Página de registro.
    //@author: Oscar González Martínez
    //Versión: 0.1
    //Proyecto "Aforro Enerxético"
    include_once '../Class/Persona.class.php';    
    include_once "../Class/Validacion.class.php";
    include_once "../DAO/DAO.class.php";
    include_once "../Class/Erro.class.php";
    
 //Inicialización de variables 
 $registerLogin = $registerName = $registerSurname = $registerPassWord = $registerVerifyPassword = $registerEmail = $registerVerifyEmail = $registerAddress = "";
 $registerRol = "Usuario";
 //$registerLoginError = $registerNameError = $registerSurnameError = $registerPassWordError = $registerVerifyPasswordError = $registerEmailError = $registerVerifyEmail = "";
 $registerError = array();
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registro de Usuario</title>
        <style>
            .container {
                border: solid 1px;
                margin: auto;
                width: 600px;
            }
        </style>
    </head>
    <body>
        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" >
        <div class="container">            
            <!-- Nombre de Login -->
            <label for="registerLogin">Login</label>            
            <input type="text" name="registerLogin" value="<?php if(isset($_POST['registerLogin'])) { echo $_POST['registerLogin'];} ?>"/>            
            <br/>
            <!-- Nombre del Usario -->
            <label for="registerName">Nombre</label>
            <input type="text" name="registerName" value="<?php if(isset($_POST['registerName'])) { echo $_POST['registerName']; } ?>" />
            <br/>            
            <!-- Apellido del Usario -->
            <label for="registerSurName">Apellidos</label>
            <input type="text" name="registerSurName" value="<?php if(isset($_POST['registerSurName'])) { echo $_POST['registerSurName']; } ?>" />
            <br/>            
            <!-- Campo Password. Por seguridad en caso de fallo no recupera el valor de la conrtaseña -->
            <label for="registerPassWord">Contraseña</label>            
            <input type="password" name="registerPassword"/>
            <br/>            
            <!-- Verificar PassWord -->
            <label for="registerVerifyPassWord">Verificar Contraseña</label>
            <input type="password" name="registerVerifyPassword"/>
            <br/>            
            <!-- Email -->
            <label for="registerEmail">Correo Electronico </label>
            <input type="registerEmail" name="registerEmail" value="<?php if (isset($_POST['registerEmail'])) { echo $_POST['registerVerifyEmail'];}?>"/>
            <br/>            
            <!-- Verificar Email -->
            <label for="registerVerifyEmail">Verificar Correo Electronico </label>
            <input type="registerVerifyEmail" name="registerVerifyEmail" value="<?php if (isset($_POST['registerEmail'])) { echo $_POST['registerVerifyEmail'];}?>"/>
            <br/>
            <label for="registerAddress">Dirección</label>
            <input type="text" name="registerAddress" id="registerAdress" value="<?php if (isset($_POST['registerAddress'])) {echo $_POST['registerAddress']; } ?>">
            <br/>
            <!-- Input y Reset -->
            <input type="submit" value="Confirmar" name="registerSubmit"/>
            <input type="reset" value="Borrar"/>
            
            
        </div>
        </form>
        <?php
        // put your code here
        if (isset($_POST['registerSubmit'])){           
           
            
            //Validación de Login            
            if (isset($_POST['registerLogin'])){
                if (Validacion::validarLogin($_POST['registerLogin'])){
                    $registerLogin = $_POST['registerLogin'];
                } else {
                    Erro::addError("registerLoginError" ,"Login Invalido");                    
                }
            }
            //Validación de Nombre
            if (isset($_POST['registerName'])){
                if (Validacion::validarNombreUsuario($_POST['registerName'])){
                    $registerName = $_POST['registerName'];
                } else {
                    Erro::addError("registerNameError","Nombre invalido");                    
                }
            }
            //Validación de Apellido
            if (isset($_POST['registerSurName'])){
                if (Validacion::validarNombreUsuario($_POST['registerSurName'])){
                    $registerName = $_POST['registerSurName'];
                } else {
                    Erro::addError("registerSurNameError","Nombre invalido");                    
                }
            }
            //Validación de Password y encriptación
            if (isset($_POST['registerPassword'])) {
                //Comprueba si se ha introducido la validacion.
                if (isset($_POST['registerVerifyPassword'])) {
                    //Comprueba que ambas cadenas son iguales
                    if (Validacion::comparaString($registerPassWord,$registerVerifyPassword)){
                        //Si todo es correcto, se genera la la contraseña cifrada.
                        $registerPassWord = Persona::generate_hash($registerPassWord);
                    } else {
                      Erro::addError("registerPassWordError","Las contraseñales son distintas");                      
                    }                   
                    
                } else {
                    //Se genera un error si no se ha introducido.
                    Erro::addError("registerVerifyPasswordError","Verifique la contraseña");                    
                }                
            } else {
                //Se genera un error si no se ha introducido la conrtaseña.
                Erro::addError(registerPassWord ,"Introduzca Password");
            }
            
            //Validación email.
            
            if (isset($_POST['registerEmail'])){
                //Comprueba que el campo validar email tenga valor
                if (isset($_POST['registerVerifyEmail'])){
                    //Comprueba que los Campos Mail y Verificar Email sean iguales.
                    if (Validacion::comparaString($registerEmail,$registerVerifyEmail)){
                        $registerEmail = $_POST['registerEmail'];
                    } else {
                        //Error cuando los campos no son iguales.

                        Erro::addError("registerVerifyEmailError", "Los campos no son iguales");                        
                    }                    
                } else {
                    //Error cuando el campo verificar email está vacío
                    Erro::addError("registerVerifyEmail","Confirme Email");                    
                }                
            } else {
                //error cuando el campo email está vacío.
                Erro::addError("registerEmailError","Introduzca Email");                
            }
            
            if (Erro::countErros() == 0){
                $user = new Usuario($registerLogin,$registerName,$registerPassWord,$registerSurname,$registerEmail,$registerRol,$registerAddress);
                DAO::insertUser($user);
            }
        }
        ?>
    </body>
</html>
