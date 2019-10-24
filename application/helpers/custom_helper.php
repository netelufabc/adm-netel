<?php

/**
 * Verifica se o peão está logado, caso não, redireciona para a página inicial
 */
function IsLogged() {
    if (!isset($_SESSION['id']) || $_SESSION['id'] < 1 || $_SESSION['id'] == null) {
        redirect('Ctrl_main');
    }
}

/**
 * Verifica se o usuário atual é coordenador ou assistente do projeto.
 * Caso não seja um dos dois, redireciona para Ctrl_main.
 * Admins e administrativos sempre tem acesso.
 * @param int $user_id
 * @param string $coordenador
 * @param array $lista_assistentes
 * @return void não retorna nada, deixa passar se true ou redireciona se false.
 */
function IsProjectOwnerOrAssist($user_id, $coordenador, $lista_assistentes) {
    if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
        return;
    }

    if ($coordenador->user_id == $user_id) {
        return;
    }

    foreach ($lista_assistentes as $assistentes) {
        if ($assistentes->id == $user_id) {
            return;
        }
    }
    redirect('Ctrl_main');
}

/**
 * Controla quais papéis tem permissão para executar o arquivo.
 * Admin Role = 1 sempre tem permissão.
 * Sem argumentos considera que apenas o admin role= 1 pode acessar.
 * Pode colocar multiplas roles, exemplo: AllowRoles(2,3,4);
 * Caso não tenha permissão, limpa a session e manda para página de login;
 */
function AllowRoles() {
    $allowed = false;
    foreach (func_get_args() as $role) {
        if ($_SESSION['role'] == $role) {
            $allowed = true;
            break;
        } else {
            $allowed = false;
        }
    }

    if (!$allowed) {
        if ($_SESSION['role'] == 1) {
            return;
        } else {
            session_destroy();
            redirect('Ctrl_main');
        }
    } else {
        return;
    }
}

/**
 * Retorna true se um dos argumentos for a role do usuário (int).
 * Pode colocar multiplas roles, exemplo: HasRole(2,3,4);
 * Caso não tenha permissão, retorna false.
 */
function HasRole() {
    $allowed = false;
    foreach (func_get_args() as $role) {
        if ($_SESSION['role'] == $role) {
            $allowed = true;
            break;
        } else {
            $allowed = false;
        }
    }
    return $allowed;
}

/**
 * Gera string aletória para os nomes de arquivo
 * @param int $length tamanho da string
 * @return string string aleatória com $lenght elementos
 */
function generateRandomString($length = 16) {
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

/**
 * Verifica se o valor informado é igual a null ou é uma string vazia (""). 
 * Se for, retorna null, senão retorna o próprio valor.
 * Usado para set null quando a string é vazia.
 * @param mixed $value
 * @return mixed
 */
function SetValueNotEmpty($value) {
    if ($value != null && $value != "") {
        return $value;
    } else {
        return null;
    }
}

/**
 * Cria o menu superior a partir do ID e ROLE do usuário (na session)
 * @param int $user_id
 * @param int $user_role
 * @return array ou null se ID == null
 */
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
            case 4:
                $menu = array(
                    'Ctrl_assistente/List_projects' => 'Listar Projetos UAB',
                );
                break;
            case 5:
                $menu = array(
                    'Ctrl_tutor/List_projects' => 'Listar Projetos UAB',
                );
                break;
            case 6:
                $menu = array(
                    'Ctrl_tutor/List_projects' => 'Listar Projetos UAB',
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
    $assunto = 'Sistema ADM-NETEL: ' . $assunto;
    $corpo_msg = '<h3>Mensagem enviada pelo Sistema Adm-NETEL - UFABC</h3><br/><br/>' . $corpo_msg . "<br><br>Este é um e-mail automático, não responda.<br>Acesse o sistema em netel.ufabc.edu.br.";
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
