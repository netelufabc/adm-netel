<?php

function criamenu($user_id, $user_role) {

    if ($user_id != NULL) {
        switch ($user_role) {
           
            case 1:
                $menu = array(
                    'Ctrl_sysadmin/Info' => 'System Info',
                    'Ctrl_sysadmin/Login_as' => 'Login As',
                    'Ctrl_administrativo/List_all_solic' => 'Listar Solicitações',
                    'Ctrl_administrativo' => 'Listar Projetos UAB',
                    'Ctrl_administrativo/List_users' => 'Listar Usuários',
                    'Ctrl_administrativo/New_user' => 'Cadastrar Usuários',
                    'Ctrl_administrativo/New_project' => 'Cadastrar Novo Projeto',
                );
                break;
            case 2:
                $menu = array(
                    'Ctrl_administrativo/List_all_solic' => 'Listar Solicitações',
                    'Ctrl_administrativo' => 'Listar Projetos UAB',
                    'Ctrl_administrativo/List_users' => 'Listar Usuários',
                    'Ctrl_administrativo/New_user' => 'Cadastrar Usuários',
                    'Ctrl_administrativo/New_project' => 'Cadastrar Novo Projeto',
                );
                break;
            case 3:
                $menu = array(
                    'Ctrl_coordenador/List_projects' => 'Listar Projetos UAB',
                );
                break;
            default:

                break;
        }
        return($menu);
    } else {
        return NULL;
    }
}

/* * ********E-MAIL******** */

/**
 * by FAMT
 * 
 * TODOS OS PARÂMETROS SÃO OPCIONAIS (pode ser '' ou NULL)
 * @param array $endEmail e endEmailCC: PODEM SER VAZIOS (''); neste caso será enviado para destinatário default (netel@ufabc.edu.br).
 *
 *  corpo_msg: 
 * @return boolean
 * 
 * Método para enviar e-mail; 
 */
function enviar_mail($assunto = NULL, $corpo_msg = NULL, $endEmail = array(), $endEmailCC = array()) {
    require_once './phpmailer/PHPMailerAutoload.php';

    $contaemail = 'netel@ufabc.edu.br';
    $pass = 'Netel2019.';
    $assunto = 'Sistemaç ADM-NETEL: ' . $assunto;
    $corpo_msg = '<h3>Mensagem enviada pelo Sistema Adm-NETEL - UFABC</h3><br/><br/>' . $corpo_msg;
    $destDefault = 'fabio.akira@ufabc.edu.br';

    // O BLOCO ABAIXO INICIALIZA O ENVIO
    $mail = new PHPMailer; // INICIA A CLASSE PHPMAILER
    $mail->IsSMTP(); //ESSA OP��O HABILITA O ENVIO DE SMTP
    $mail->Host = 'smtp.ufabc.edu.br'; //'smtp.gmail.com'; //SERVIDOR DE SMTP, USE smtp.SeuDominio.com OU smtp.hostsys.com.br 
    $mail->Port = 587; //gmail 465 ou ufabc 587
    $mail->SMTPAuth = true; //ATIVA O SMTP AUTENTICADO
    $mail->SMTPSecure = 'tls'; //gmail ssl e ufabc tls
    $mail->Username = "$contaemail"; //    $mail->Username = "suporte.tidia@gmail.com"; //"suporte.tidia@ufabc.edu.br"; //EMAIL PARA SMTP AUTENTICADO (pode ser qualquer conta de email do seu domínio)
    $mail->Password = $pass; //    SENHA DO EMAIL PARA SMTP AUTENTICADO
    $mail->From = "$contaemail"; //    $mail->From = "suporte.tidia@gmail.com"; //E-MAIL DO REMETENTE 
    $mail->FromName = "Sistema ADM-NETEL - UFABC"; //NOME DO REMETENTE
    $mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
    $mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
    $mail->Subject = utf8_decode($assunto); //ASSUNTO DA MENSAGEM
    $mail->Body = utf8_decode($corpo_msg); //$dados_mail['corpo_msg'];
    //insere os destinatarios principais
    if (count($endEmail) != 0) {//se tiver especificado os destinatarios, não enviara para o destinatario padrao netel@ufabc
        foreach ($endEmail as $address) {
            $mail->addAddress($address);
        }
    } else {//destinatario padrao netel@ufabc
        $mail->addAddress($destDefault);
    }

    //insere os enderecos para copia
    foreach ($endEmailCC as $address) {
        $mail->addCC($address);
    }
    $enviou = $mail->Send();
    $mail->ClearAllRecipients();  //Limpa os destinat�rios e os anexos (DEMORA MUITO)
    $mail->ClearAttachments();

    if ($enviou) {
        return TRUE;
    } else {
//            echo $mail->print_debugger();
        echo $mail->ErrorInfo;
        return FALSE;
    }
}
