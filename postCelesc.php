<?php


$header_position = 0;
$my_array = array();

var_dump($_FILES);

$f = fopen('php://input','r');

$position = 0;

fseek($f, ftell($f));
while (($line = stream_get_line($f, 1024 * 1024, "\n")) !== false) {
    if ($header_position > 0){   //ignora o header     
        $celesc = new Celesc();
        $celesc->IdTipoRegistro= substr($line, $position, 1);
        $position = 1;
        $celesc->CdUnidadeConsumidora= substr($line, $position, 13); 
        $position = 14;
        $celesc->ValorLancamento= substr($line, $position, 9); 
        $position = 23;
        $celesc->DataGeracaoRegistro= substr($line, $position, 8); 
        $position = 31;
        $celesc->CmdMovimento= substr($line, $position, 2); 
        $position = 33;
        $celesc->CdCtaContabil= substr($line, $position, 8); 
        $position = 41;
        $celesc->Cobertura_Ocorrencia= substr($line, $position, 2); 
        $position = 43;
        $celesc->Descricao_Cobertura_Ocorrencia= substr($line, $position, 30); 
        $position = 73;
        $celesc->NumCliente= substr($line, $position, 10); 
        $position = 83;
        $celesc->IdClienteContratante= substr($line, $position, 6); 
        $position = 89;
        $celesc->CPF_CPNJ= substr($line, $position, 12); 
        $position = 101;
        $celesc->MesInicioVigencia= substr($line, $position, 8); 
        $position = 109;
        $celesc->MesFimVigencia= substr($line, $position, 8);
        $position = 117; 
        $celesc->ComplCNPJ= substr($line, $position, 2); 
        $position = 119;
        $celesc->Espacos= substr($line, $position, 2); 
        $position = 121;
        $celesc->CdUnidadeConsumidoraAnterior= substr($line, $position,13); 
        $position = 134;
        $celesc->NmClienteAnterior= substr($line, $position, 10); 
        $position = 144;
        $celesc->NmSequencialRegistro= substr($line, $position, 6);
        $position = 0;        
        $my_array[] = $celesc;
    }

 
    $header_position += 1;
 }

//Classe Celesc com os campos conforme a DFD 
class Celesc{
    public $IdTipoRegistro;
    public $CdUnidadeConsumidora;
    public $ValorLancamento;
    public $DataGeracaoRegistro;
    public $CmdMovimento;
    public $CdCtaContabil;
    public $Cobertura_Ocorrencia;
    public $Descricao_Cobertura_Ocorrencia;
    public $NumCliente;
    public $IdClienteContratante;
    public $CPF_CPNJ;
    public $MesInicioVigencia;
    public $MesFimVigencia;
    public $ComplCNPJ;
    public $Espacos;
    public $CdUnidadeConsumidoraAnterior;
    public $NmClienteAnterior;
    public $NmSequencialRegistro;
}

//remove ultimo elemento que é o footer
$a = array_pop($my_array);
echo   json_encode($my_array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), "\n\n";
 

?>
