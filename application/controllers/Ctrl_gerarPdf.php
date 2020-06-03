<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//include_once'./phpqrcode/qrlib.php';
require_once './fpdf/fpdf.php';

class Ctrl_gerarPdf extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }

    /**
     * função para teste
     */
    public function TesteGerarFpdf() {

//        $dados = elements(array('nome_tutor', 'num_edital', 'mes_ano'), $this->input->post());
        $dados = elements(array('nome_coordenador', 'nome_projeto', 'mes_ano'), $this->input->post());
        $tutores = array('thiago.ramos',
                    'ana.areas',
                    'christiane.lombello',
                    'sandra.momm',
                    'sandra.momm',
                    'paulo.lopes',
        );
//        $this->pdf_relatorio_tutor($dados);
        $this->pdf_parecer_coord($dados, $tutores);
    }

    /**
     * RELATÓRIO DE TUTORES
     * Gera o relatório de tutores em PDF.
     * 
     * @param array $dados
     *  nome_tutor, num_edital, mes_ano
     */
    public function pdf_relatorio_tutor() {

        $dados['nome_tutor'] = urldecode($this->uri->segment(3));
        $dados['num_edital'] = urldecode($this->uri->segment(4));
        $dados['mes_ano'] = $this->uri->segment(5);
        
        if(!in_array(NULL,$dados)){

            /********** template **********/
            $fpdf = new FPDF();
            $fpdf->AddPage('P', 'A4');
            $fpdf->SetAutoPageBreak(false); //essa config possibilita definir margin inferior
            $fpdf->Image('images/cabecalho_doc.png', 25, 8, 120);

            $bordas = 0; //exibe as bordas de todas as cells para melhor configurar
            $fpdf->SetRightMargin(17); //define o espaçamento na margem direita
            
            //trecho da introdução
            $fpdf->SetFont('times', 'B', 12);
            $fpdf->SetXY(25, 42);
            $fpdf->MultiCell(0, 10, utf8_decode("RELATÓRIO MENSAL DE ATIVIDADES DE TUTORES"), $bordas, 'C');

            //datas
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $mes_ano = strftime('%B/%Y', strtotime($dados['mes_ano']));
            $fpdf->SetX(25);
            $fpdf->MultiCell(0, 13, utf8_decode('Mês: ').$mes_ano , $bordas, 'R');
            
            $texto1 = utf8_decode( "Eu, " . mb_strtoupper($dados['nome_tutor']) . ", na condição de"
                    . " Bolsista selecionado pelo Edital UAB-UFABC nº ". $dados['num_edital'] . ", declaro"
                    . " que desenvolvi as seguintes atividades descritas no termo de compromisso do Bolsista"
                    . " anexo VII da Portaria Capes nº 183 de 21 de outubro de 2016 conforme abaixo selecionadas:");

            $fpdf->SetX(25);
            $fpdf->SetFont('times', '', 12);
            $fpdf->MultiCell(0, 6, $texto1, $bordas, 'J');
            
            $texto2 = array(
                     'Mediei a comunicação de conteúdos entre o professor e os cursistas;',
                     'Acompanhei as atividades discentes, conforme cronograma do curso;',
                     'Apoiei o professor da disciplina no desenvolvimento das atividades docentes;',
                     'Estabeleci contato permanente com os alunos e mediei as atividades discentes;',
                     'Colaborei com a coordenação do curso na avaliação dos estudantes;',
                     'Participei das atividades de capacitação e atualização promovidas pela Instituição de
Ensino;',
                     'Elaborei relatórios mensais de acompanhamento dos alunos e encaminhei à
coordenadoria de tutoria e/ou curso;',
                     'Participei do processo de avaliação da disciplina sob orientação do professor
responsável;',
                     'Mantive regularidade de acesso ao Ambiente Virtual de Aprendizagem (AVA) e dei
retorno às solicitações dos cursistas no prazo máximo de 24 horas;',
                     'Apoiei operacionalmente a coordenação do curso nas atividades presenciais nos polos,
em especial na aplicação de avaliações.',
                     'Auxiliei os professores nas atividades presenciais e virtuais;',
                     'Atendi e orientei os alunos nas questões teórico-metodológicas do curso;',
                     'Apliquei as avaliações presenciais nos Polos ou em outro local definido pela
coordenação do curso, conforme as necessidades do programa.'
                    );
            
            foreach ($texto2 as $value) {
                $fpdf->Ln(2);
                $fpdf->SetX(25);
                $fpdf->SetFont('ZapfDingbats', '', 12);
                $fpdf->Cell(5, 6, chr(113), $bordas, 0);
                $fpdf->SetFont('times', '', 12);
                $fpdf->MultiCell(163, 6, utf8_decode($value), $bordas,'J');
            }
            
            $data_atual = utf8_encode(strftime('%d de %B de %Y.'));
            $data = utf8_decode("Santo André, ".$data_atual);
            $fpdf->SetXY(25,240);
            $fpdf->MultiCell(168, 6, $data, $bordas,'R');

            $x1 = 72;//início da linha
            $x2 = 145;//fim da linha
            $y1= $y2 = 260;
            $fpdf->Line($x1, $y1, $x2, $y2);
            //nome do tutor
            $fpdf->SetXY(25,262);
            $fpdf->SetFont('arial', 'B', 12);
            $fpdf->MultiCell(0, 6, 'Tutor UAB/UFABC ' . mb_strtoupper($dados['nome_tutor']), $bordas, 'C');

            $fpdf->Image('images/rodape_doc.png', 65, 278, 90);
            /********** fim template **********/

            //gera o pdf final		
            $fpdf->Output(utf8_decode('Relatório-'.$mes_ano .'-'. $dados['nome_tutor']) . '.pdf', 'I');

        } else {
            echo "<div style='color: red; text-align: center; padding-top: 100px'><h3>Dados incorretos para gerar o documento</h3></div>";
        }
    }
    
    /**
     * PARECER DA COORDENAÇÃO
     * Gera documento PDF com o parecer da coordenação sobre as atividades realizadas pelos tutores
     * 
     * @param array $dados nome_coordenador, mes_ano, nome_projeto, numero_projeto
     * 
     *  array $tutores lista com nomes dos tutores
     * 
     */
    public function pdf_parecer_coord(/*$dados=null, $tutores=null*/) {
        
        $dados['mes_ano'] = $this->input->post('mes_ano');
        $dados['nome_coordenador'] = $this->input->post('nome_coordenador');
        $dados['numero_projeto'] = $this->input->post('numero_projeto');
        $dados['nome_projeto'] = $this->input->post('nome_projeto');
        
        $tutores = $this->input->post('tutor');
        
        if (!in_array(NULL,$dados) && !in_array(NULL, $tutores)) {

            /********** template **********/
            $fpdf = new FPDF();
            $fpdf->AddPage('P', 'A4');
            $fpdf->SetAutoPageBreak(false); //essa config possibilita definir margin inferior
            $fpdf->Image('images/cabecalho_doc.png', 25, 8, 120);

            $bordas = 0; //exibe as bordas de todas as cells para melhor configurar
            $fpdf->SetRightMargin(17); //define o espaçamento na margem direita
            
            //trecho da introdução
            $fpdf->SetFont('times', 'B', 12);
            $fpdf->SetXY(25, 42);
            $fpdf->MultiCell(0, 8, utf8_decode("PARECER DA COORDENAÇÃO DE CURSO \n RELATÓRIO MENSAL DE ATIVIDADES DE TUTORES"), $bordas, 'C');
            $fpdf->Ln(3);
            
            //datas
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $mes_ano = strftime('%B/%Y', strtotime($dados['mes_ano']));
            
            $texto1 = utf8_decode("       Na condição de Coordenador do curso de ". $dados['nome_projeto'] ." da UFABC, "
                    . "oferecido em parceria com a UAB/CAPES, atesto e ratifico o trabalho realizado pelos "
                    . "tutores relacionados abaixo durante o mês de $mes_ano. \n"
                    . "     Os detalhes do trabalho realizado por cada tutor estão apresentados no relatório mensal "
                    . "individual de tutoria que foi encaminhado por todos a esta coordenação.");
            $fpdf->SetX(25);
            $fpdf->SetFont('times', '', 12);
            $fpdf->MultiCell(0, 6, $texto1, $bordas, 'J');
            $fpdf->Ln(2);
            
            //lista de tutores com parecer favorável
            foreach ($tutores as $tutor) {
                $fpdf->Ln(2);
                $fpdf->SetX(25);
                $fpdf->MultiCell(168, 6, utf8_decode($tutor), $bordas,'J');
            }
            
            $data_atual = utf8_encode(strftime('%d de %B de %Y.'));
            $data = utf8_decode("Santo André, ".$data_atual);
            $fpdf->SetXY(25,240);
            $fpdf->MultiCell(168, 6, $data, $bordas,'R');

            $x1 = 72;//início da linha
            $x2 = 145;//fim da linha
            $y1= $y2 = 260;
            $fpdf->Line($x1, $y1, $x2, $y2);
            //nome do tutor
            $fpdf->SetXY(25,262);
            $fpdf->SetFont('arial', 'B', 12);
            $fpdf->MultiCell(0, 6, utf8_decode("Coordenador do curso de $dados[nome_projeto] UAB/UFABC"), $bordas, 'C');

            $fpdf->Image('images/rodape_doc.png', 65, 278, 90);
            /********** fim template **********/

            //gera o pdf final		
            $fpdf->Output(utf8_decode('Parecer-'.$mes_ano .'-'. $dados['nome_coordenador']) . '.pdf', 'I');
        } else {
            echo "<div style='color: red; text-align: center; padding-top: 100px'><h3>Dados incorretos para gerar o documento</h3></div>";
        }
    }
}
